<?php
session_start();

// Auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../../../../config/nyambung.php';
require_once __DIR__ . '/../../../../backend/submateri.php';
require_once __DIR__ . '/../../../../backend/progresMateri.php';
require_once __DIR__ . '/../../../../backend/activityBelajar.php';
require_once __DIR__ . '/../../../assets/layout/header.php';

use App\submateri\Submateri;
use App\progres\ProgressMateri;

// get ids
$id_submateri = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$id_materi = isset($_GET['materi_id']) ? (int)$_GET['materi_id'] : 0;
$user_id = $_SESSION['user_id'];

if ($id_submateri <= 0 || $id_materi <= 0) {
    header("Location: listMateri.php?error=invalid_id");
    exit;
}

// init objects
$subObj = new Submateri();
$progressObj = new ProgressMateri();
$logObj = new LogBelajar($conn ?? null);

// fetch submateri
$sub = $subObj->lihatSubmateriById($id_submateri);
if (!$sub || ($sub['materi_id_materi'] ?? 0) != $id_materi) {
    header("Location: detail_materi.php?id=" . $id_materi . "&error=not_found");
    exit;
}

// navigation list (simple)
$list = $subObj->lihatSubmateriByMateri($id_materi) ?: [];
$ids = array_column($list, 'id_subMateri');
$total = count($ids);
$current_pos = array_search($id_submateri, $ids);
$current_position = ($current_pos === false) ? 1 : ($current_pos + 1);
$previous = ($current_pos > 0) ? $ids[$current_pos - 1] : null;
$next = ($current_pos !== false && $current_pos < $total - 1) ? $ids[$current_pos + 1] : null;

// mark opened (optional)
if (method_exists($progressObj, 'markSubmateriOpened')) {
    $progressObj->markSubmateriOpened($id_submateri);
}
?>

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
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .progress-bar {
        height: 8px;
        background: #E8EAF6;
        border-radius: 8px;
        overflow: hidden;
    }
    .progress-bar-inner {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #3B82F6, #60A5FA);
        transition: width 0.5s ease;
    }
    .content-container {
        min-height: 300px;
        line-height: 1.8;
        font-size: 16px;
        color: #374151;
    }
    .content-container p {
        margin-bottom: 1.5rem;
    }
    .content-container h1, 
    .content-container h2, 
    .content-container h3 {
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #1F2937;
    }
    .content-container ul, 
    .content-container ol {
        margin-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .content-container li {
        margin-bottom: 0.5rem;
    }
</style>
</head>
<body class="gradient-bg min-h-screen">
<div class="container mx-auto px-4 py-8 max-w-4xl">
    
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-6">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                            <?= htmlspecialchars($sub['nama_subMateri']) ?>
                        </h1>
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                Modul <?= $current_position ?> dari <?= $total ?>
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="materi_detail.php?id=<?= $id_materi ?>" 
                           class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200 flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
                
                <!-- Progress Section -->
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Progres Pembelajaran</span>
                        </div>
                        <span class="text-sm font-bold text-blue-600"><?= round(($current_position/$total)*100) ?>%</span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="progress-bar mb-4">
                        <div class="progress-bar-inner" style="width: <?= ($current_position/$total)*100 ?>%"></div>
                    </div>
                    
                    <!-- Progress Indicators -->
                    <div class="flex justify-between mt-2">
                        <?php for ($i = 1; $i <= $total; $i++): ?>
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 flex items-center justify-center mb-1 rounded-full <?= $i <= $current_position ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' : 'bg-gray-200 text-gray-600' ?>">
                                    <span class="text-xs font-bold">
                                        <?= $i ?>
                                    </span>
                                </div>
                                <?php if ($i == $current_position): ?>
                                    <div class="w-1 h-3 bg-blue-500 mt-1 rounded-full"></div>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-2xl shadow-lg mb-6 animate-fade-in" style="animation-delay: 100ms">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Isi Materi</h2>
            </div>
        </div>
        <div class="p-8 content-container">
            <?= nl2br(htmlspecialchars($sub['isi_materi'] ?? '')) ?>
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex flex-col sm:flex-row gap-4 animate-fade-in" style="animation-delay: 200ms">
        <?php if ($previous): ?>
            <a id="linkPrev" href="submateri_detail.php?id=<?= $previous ?>&materi_id=<?= $id_materi ?>" 
               class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 font-medium rounded-xl hover:shadow-md transition-all duration-200 border border-gray-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Sebelumnya
            </a>
        <?php else: ?>
            <button disabled class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 text-gray-400 font-medium rounded-xl cursor-not-allowed border border-gray-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Sebelumnya
            </button>
        <?php endif; ?>

        <?php if ($next): ?>
            <a id="linkNext" href="submateri_detail.php?id=<?= $next ?>&materi_id=<?= $id_materi ?>" 
               class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl hover:shadow-md transition-all duration-200">
                Selanjutnya
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </a>
        <?php else: ?>
            <a href="materi_detail.php?id=<?= $id_materi ?>" 
               class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium rounded-xl hover:shadow-md transition-all duration-200">
                Selesaikan Materi
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
        <?php endif; ?>
    </div>
    
</div>

<script>
const auto = (function(){
  // state
  let currentLogId = window.currentLogId || null;
  const MATERI_ID = <?= (int)$id_materi ?>;
  const SUB_ID = <?= (int)$id_submateri ?>;

  // minimal post helper (returns parsed json or throws)
  async function postJson(url, data) {
    const body = new URLSearchParams(data).toString();
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body
    });
    const text = await res.text();
    try { return JSON.parse(text); }
    catch (e) { throw new Error('Non-JSON response from ' + url + ': ' + text.slice(0,200)); }
  }

  // use global startLearning if exists, else local
  async function startLearningLocal(subId = SUB_ID) {
    try {
      const j = await postJson('/api/log/start_log.php', { materi_id: MATERI_ID, submateri_id: subId });
      if (j.success) {
        currentLogId = j.log_id;
        window.currentLogId = currentLogId;
        console.info('Auto-start OK', currentLogId);
        return currentLogId;
      } else {
        console.warn('start_log returned not success', j);
        return null;
      }
    } catch (err) {
      console.error('startLearningLocal error', err);
      return null;
    }
  }

  async function endLearningLocal() {
    if (!currentLogId) return false;
    try {
      const j = await postJson('/api/log/end_log.php', { log_id: currentLogId });
      if (j.success) {
        console.info('Auto-end OK', currentLogId);
        currentLogId = null;
        window.currentLogId = null;
        return true;
      } else {
        console.warn('end_log returned not success', j);
        return false;
      }
    } catch (err) {
      console.error('endLearningLocal error', err);
      return false;
    }
  }

  // Decide which to call (prefer existing functions if defined)
  async function startLearningWrapper(subId) {
    if (typeof window.startLearning === 'function') {
      try {
        await window.startLearning(subId);
        // assume that startLearning sets window.currentLogId
        currentLogId = window.currentLogId || currentLogId;
        return currentLogId;
      } catch (e) { console.warn('global startLearning failed, fallback', e); }
    }
    return startLearningLocal(subId);
  }

  async function endLearningWrapper() {
    if (typeof window.endLearning === 'function') {
      try {
        await window.endLearning();
        currentLogId = window.currentLogId || null;
        return true;
      } catch (e) { console.warn('global endLearning failed, fallback', e); }
    }
    return endLearningLocal();
  }

  /* ---------- Auto behaviors ---------- */

  // 1) Auto-start on page load (DOMContentLoaded)
  document.addEventListener('DOMContentLoaded', function() {
    // If there's already a log id in session (server might have set it), reuse it
    if (window.currentLogId) {
      currentLogId = window.currentLogId;
      console.info('Using existing currentLogId from window:', currentLogId);
      return;
    }

    // Otherwise start automatically
    startLearningWrapper(SUB_ID).catch(()=>{});
  });

  // 2) Auto-end before navigation on Prev/Next links
  function interceptLink(idSelector) {
    const el = document.getElementById(idSelector);
    if (!el) return;
    el.addEventListener('click', function(e){
      if (!currentLogId) return;
      e.preventDefault();
      const href = this.href;
      endLearningWrapper().finally(() => { window.location.href = href; });
    });
  }
  
  interceptLink('linkPrev');
  interceptLink('linkNext');

  // 3) Keyboard navigation
  document.addEventListener('keydown', function(e){
    if (e.key === 'ArrowLeft' && <?= $previous ? 'true' : 'false' ?>) {
      if (currentLogId) { 
        e.preventDefault(); 
        endLearningWrapper().finally(()=> { 
          window.location.href = 'submateri_detail.php?id=<?= $previous ?>&materi_id=<?= $id_materi ?>'; 
        }); 
      }
    }
    if (e.key === 'ArrowRight' && <?= $next ? 'true' : 'false' ?>) {
      if (currentLogId) { 
        e.preventDefault(); 
        endLearningWrapper().finally(()=> { 
          window.location.href = 'submateri_detail.php?id=<?= $next ?>&materi_id=<?= $id_materi ?>'; 
        }); 
      }
    }
    if (e.key === 'Escape') {
      if (currentLogId) { 
        e.preventDefault(); 
        endLearningWrapper().finally(()=> { 
          window.location.href = 'materi_detail.php?id=<?= $id_materi ?>'; 
        }); 
      }
    }
  });

  // 4) beforeunload fallback (best-effort) using Beacon API
  window.addEventListener('beforeunload', function() {
    if (!currentLogId) return;
    try {
      const data = new URLSearchParams({ log_id: currentLogId });
      navigator.sendBeacon('/api/log/end_log.php', data);
    } catch (e) { /* ignore */ }
  });

  // 5) Auto-end when user scrolls to bottom
  (function bottomAutoEnd(){
    const MIN_STAY_MS = 4000;
    let bottomTimer = null;
    const threshold = 0.90;

    function onScroll() {
      const pos = window.scrollY;
      const wh = window.innerHeight;
      const dh = document.body.scrollHeight;
      const pct = (pos / (dh - wh));
      if (pct >= threshold) {
        if (!bottomTimer) {
          bottomTimer = setTimeout(async () => {
            bottomTimer = null;
            if (currentLogId) {
              console.info('Reached bottom and stayed - auto ending session');
              await endLearningWrapper();
              // Notification
              const t = document.createElement('div');
              t.textContent = 'Modul Selesai';
              t.style.cssText = 'position:fixed;bottom:20px;right:20px;background:#10B981;color:white;padding:12px 16px;border-radius:8px;font-weight:bold;box-shadow:0 4px 6px rgba(0,0,0,0.1);z-index:1000;';
              document.body.appendChild(t);
              setTimeout(()=>t.remove(), 3000);
            }
          }, MIN_STAY_MS);
        }
      } else {
        if (bottomTimer) { clearTimeout(bottomTimer); bottomTimer = null; }
      }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
  })();

  // expose small API for debugging
  return {
    getCurrentLogId: () => currentLogId,
    start: () => startLearningWrapper(SUB_ID),
    end: () => endLearningWrapper()
  };
})(); // auto IIFE
</script>

</body>
</html>