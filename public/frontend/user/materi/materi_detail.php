<?php
require_once __DIR__ . '/../../../../backend/AuthMiddleware.php';
require_once __DIR__ . '/../../../../backend/materi.php';
require_once __DIR__ . '/../../../../backend/submateri.php';
require_once __DIR__ . '/../../../../api/ytSearch.php';

use App\Materi\Materi;
use App\submateri\Submateri;
use App\Service\YouTubeSearchService;
use App\AuthMiddleware;

AuthMiddleware::authUser();

$materiId = (int)($_GET['id'] ?? 0);
if ($materiId <= 0) die('Invalid materi');

$materiObj = new Materi();
$subObj = new Submateri();
$yt = new YouTubeSearchService();

$materi = $materiObj->getMateriById($materiId)['data'] ?? [];
$submateri = $subObj->lihatSubmateriByMateri($materiId);

// rekomendasi video dengan keyword dari nama materi
$keyword = $materi['nama_materi'] ?? '';
$videos = [];

if (!empty($keyword)) {
    try {
        $ytResult = $yt->search($keyword);
        // Cek struktur response yang berbeda-beda
        if (isset($ytResult['results']['items'])) {
            $videos = $ytResult['results']['items'];
        } elseif (isset($ytResult['items'])) {
            $videos = $ytResult['items'];
        } elseif (isset($ytResult['results'])) {
            $videos = $ytResult['results'];
        } elseif (is_array($ytResult) && isset($ytResult[0]['title'])) {
            $videos = $ytResult;
        }
    } catch (Exception $e) {
        error_log('YouTube API error: ' . $e->getMessage());
        $videos = [];
    }
}
require_once __DIR__ . '/../../../assets/layout/header.php';


?> 


  <title><?= htmlspecialchars($materi['nama_materi'] ?? 'Materi') ?></title>

  <style>
    * {
      font-family: 'Inter', sans-serif;
    }
    .gradient-bg {
      background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    }
    .card-hover:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
    }
  </style>
</head>
<body class="gradient-bg min-h-screen">
   
<div class="container mx-auto px-4 py-8 max-w-6xl">
  
  <!-- Header Materi -->
  <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 mb-8">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-6">
        <a href="listMateri.php" 
           class="mr-4 mt-1 text-gray-500 hover:text-gray-800 transition-colors duration-200">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
        </a>
      <div class="flex-1">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">
          <?= htmlspecialchars($materi['nama_materi'] ?? 'Materi') ?>
        </h1>
        <div class="flex items-center space-x-4 mb-4">
          <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">
            <?= count($submateri) ?> Modul
          </span>
          <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
            <?= count($videos) ?> Video
          </span>
        </div>
      </div>
      <div class="mt-4 md:mt-0">
        <button onclick="endLearning()" class="px-5 py-2.5 bg-gradient-to-r from-rose-500 to-rose-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
          Selesai Belajar
        </button>
      </div>
    </div>
    
    <!-- Deskripsi Materi -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
      <h2 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        Deskripsi Materi
      </h2>
      <p class="text-gray-600 leading-relaxed">
        <?= nl2br(htmlspecialchars($materi['deskripsi_materi'] ?? 'Tidak ada deskripsi')) ?>
      </p>
    </div>
  </div>

  <!-- Daftar Submateri -->
  <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 mb-8">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold text-gray-800 flex items-center">
        <svg class="w-6 h-6 mr-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
        </svg>
        Modul Pembelajaran
      </h2>
      <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
        Mulai dari atas ke bawah
      </span>
    </div>
    
    <?php if (empty($submateri)): ?>
      <div class="text-center py-10">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <p class="text-gray-500">Tidak ada submateri tersedia.</p>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php foreach ($submateri as $index => $s): ?>
          <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl p-5 border border-gray-200 card-hover transition-all duration-300">
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <div class="flex items-center mb-3">
                  <div class="w-8 h-8 flex items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-bold text-sm mr-3">
                    <?= $index + 1 ?>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-800">
                    <?= htmlspecialchars($s['nama_subMateri'] ?? 'Submateri') ?>
                  </h3>
                </div>
                <div class="ml-11">
                  <div class="flex items-center space-x-4">
                    <button onclick="startLearning(<?= (int)($s['id_subMateri'] ?? 0) ?>)"
                            class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium rounded-lg hover:shadow-md transition-all duration-200 flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                      </svg>
                      Mulai
                    </button>
                    <a href="submateri_detail.php?id=<?= $s['id_subMateri'] ?? 0 ?>&materi_id=<?= $materiId ?>" 
                       class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:shadow-md transition-all duration-200 flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                      </svg>
                      Detail
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Section Rekomendasi Video (Paling Bawah) -->
  <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold text-gray-800 flex items-center">
        <svg class="w-6 h-6 mr-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
        </svg>
        Rekomendasi Video Belajar
      </h2>
      <span class="text-sm text-gray-500 bg-red-50 px-3 py-1 rounded-full">
        Keyword: "<?= htmlspecialchars($keyword) ?>"
      </span>
    </div>

    <?php if (empty($videos)): ?>
      <div class="text-center py-10">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        <p class="text-gray-600 mb-3">Tidak ada rekomendasi video untuk "<?= htmlspecialchars($keyword) ?>".</p>
        <p class="text-sm text-gray-500">Coba cari manual di YouTube dengan keyword di atas.</p>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($videos as $index => $v): ?>
          <?php 
          // Normalisasi data video
          $videoId = $v['video_id'] ?? $v['id']['videoId'] ?? $v['id'] ?? '';
          $title = htmlspecialchars($v['title'] ?? $v['snippet']['title'] ?? 'Video tanpa judul');
          $channel = htmlspecialchars($v['channel'] ?? $v['snippet']['channelTitle'] ?? 'Channel tidak diketahui');
          $thumbnail = $v['thumbnail'] ?? 
                      ($v['snippet']['thumbnails']['medium']['url'] ?? 
                      ($v['snippet']['thumbnails']['default']['url'] ?? ''));
          
          if (empty($videoId)) continue;
          ?>
          
          <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl overflow-hidden border border-gray-200 card-hover transition-all duration-300">
            <!-- Thumbnail -->
            <div class="relative overflow-hidden bg-gray-100">
              <?php if (!empty($thumbnail)): ?>
                <img src="<?= htmlspecialchars($thumbnail) ?>" 
                     alt="Thumbnail video" 
                     class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500">
              <?php else: ?>
                <div class="w-full h-48 flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                  <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                  </svg>
                </div>
              <?php endif; ?>
              <div class="absolute top-3 right-3 bg-black/70 text-white text-xs px-2 py-1 rounded">
                Video <?= $index + 1 ?>
              </div>
            </div>
            
            <!-- Video Info -->
            <div class="p-4">
              <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 h-12">
                <?= $title ?>
              </h3>
              <div class="flex items-center text-gray-600 text-sm mb-4">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                <?= $channel ?>
              </div>
              
              <div class="flex items-center justify-between">
                <a target="_blank" 
                   href="https://www.youtube.com/watch?v=<?= urlencode($videoId) ?>" 
                   class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-lg hover:shadow-md transition-all duration-200 flex items-center justify-center flex-1 mr-2">
                  <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                  </svg>
                  Tonton
                </a>
                
                <button onclick="startLearningWithVideo('<?= urlencode($title) ?>')"
                        class="px-3 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:shadow-md transition-all duration-200 flex items-center">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>

<!-- JS LOG BELAJAR -->
<script>
let currentLogId = null;
const MATERI_ID = <?= (int)$materiId ?>;

async function post(url, data) {
  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {'Content-Type':'application/x-www-form-urlencoded'},
      body: new URLSearchParams(data)
    });
    return await res.json();
  } catch (error) {
    console.error('Fetch error:', error);
    return { success: false, error: error.message };
  }
}

async function startLearning(subId) {
  if (!subId) {
    alert('Submateri ID tidak valid');
    return;
  }
  
  const r = await post('/api/log/start_log.php', {
    materi_id: MATERI_ID,
    submateri_id: subId
  });
  
  if (r.success) {
    currentLogId = r.log_id;
    
    // Sweet notification effect
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
    notification.innerHTML = `
      <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        Sesi belajar dimulai
      </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
      notification.remove();
    }, 3000);
    
  } else {
    alert('Gagal memulai sesi: ' + (r.error || 'Unknown error'));
  }
}

async function endLearning() {
  if (!currentLogId) {
    alert('Tidak ada sesi aktif');
    return;
  }
  
  if (!confirm('Apakah Anda yakin ingin menyelesaikan sesi belajar ini?')) {
    return;
  }
  
  const r = await post('/api/log/end_log.php', { log_id: currentLogId });
  
  if (r.success) {
    currentLogId = null;
    
    // Sweet notification effect
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
    notification.innerHTML = `
      <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        Belajar selesai, durasi tercatat
      </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
      notification.remove();
    }, 3000);
    
  } else {
    alert('Gagal mengakhiri sesi: ' + (r.error || 'Unknown error'));
  }
}

function startLearningWithVideo(videoTitle) {
  const notification = document.createElement('div');
  notification.className = 'fixed top-4 right-4 bg-purple-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
  notification.innerHTML = `
    <div class="flex items-center">
      <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
      </svg>
      Video "${decodeURIComponent(videoTitle).substring(0, 30)}..." ditambahkan ke aktivitas
    </div>
  `;
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.remove();
  }, 3000);
}

window.addEventListener('beforeunload', (e) => {
  if (!currentLogId) return;
  
  // Konfirmasi jika ada sesi aktif
  e.preventDefault();
  e.returnValue = 'Anda memiliki sesi belajar aktif. Apakah Anda yakin ingin meninggalkan halaman?';
  
  // Gunakan sendBeacon untuk mencatat akhir sesi saat halaman ditutup
  const data = new URLSearchParams({ log_id: currentLogId });
  navigator.sendBeacon('/api/log/end_log.php', data);
});

// Animasi CSS
const style = document.createElement('style');
style.textContent = `
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .animate-fade-in {
    animation: fadeIn 0.3s ease-out;
  }
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
`;
document.head.appendChild(style);
</script>

</body>
</html>