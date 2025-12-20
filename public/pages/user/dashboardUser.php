<?php
session_set_cookie_params([
  'path' => '/',
  'httponly' => true,
  'samesite' => 'Lax'
]);
session_start();
require_once __DIR__ . '/../../config.php';


require_once ROOT_PATH . '/backend/service/dashboardService.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';


use App\AuthMiddleware;
use App\DashboardService;

try {
    $userData = AuthMiddleware::authUser();
    $nama = htmlspecialchars($userData['nama']); 
    $userId = $userData['id'];
    
    // TAMBAH: Ambil data dashboard
    $dashboard = new DashboardService();
    $summary = $dashboard->getSummary($userId);
    $lastMateri = $dashboard->getLastStudiedMaterial($userId);
    $deadlines = $dashboard->deadline($userId, 3);
    $durasi = $dashboard -> durasiBelajar($userId);
    
} catch (\Exception $e) {
    echo $e->getMessage();
    //header('Location: ' . BASE_URL . "pages/auth/login.php");
    exit;
}

require_once PUBLIC_PATH . '/partials/header.php';
REQUIRE_ONCE PUBLIC_PATH . '/partials/sidebar.php';
?>

<!-- MAIN CONTAINER - Full width layout -->
<div class="min-h-screen">
    
    <!-- Desktop: setelah sidebar w-38 -->
    <div class="md:ml-[152px] lg:ml-[152px] xl:ml-[152px] transition-all duration-300">
        
        <!-- FULL WIDTH CONTAINER - mengambil seluruh lebar setelah sidebar -->
        <div class="w-full">
            
            <!-- HEADER SECTION - Responsive -->
           <div class="bg-white px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <div class="max-w-full">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
            <!-- Left content -->
            <div class="space-y-2.5">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-2.5 h-12 bg-gradient-to-b from-[#2563EB] to-[#93C5FD] rounded-full"></div>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#1F2937] leading-tight">
                            Selamat Datang, <span class="text-[#2563EB]"><?= $nama; ?></span> 👋
                        </h1>
                        <div class="flex flex-wrap items-center gap-3 mt-2.5">
                            <span class="text-lg text-gray-600" id="currentTime">
                                <?php 
                                $now = new DateTime();
                                $formatter = new IntlDateFormatter(
                                    'id_ID', 
                                    IntlDateFormatter::FULL, 
                                    IntlDateFormatter::SHORT,
                                    'Asia/Jakarta',
                                    IntlDateFormatter::GREGORIAN,
                                    'EEEE, dd MMMM yyyy • HH:mm'
                                );
                                echo $formatter->format($now);
                                ?>
                            </span>
                            <span class="text-base px-3.5 py-1.5 bg-[#10B981]/10 text-[#10B981] rounded-full font-semibold">
                                Teruslah belajar!
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right button -->
            <div class="flex-shrink-0">
                <button 
                    onclick="exportLaporan(this)" 
                    class="group w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 bg-white border-2 border-[#2563EB]/30 hover:border-[#2563EB] text-[#2563EB] font-semibold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-1"
                >
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-lg">Export</span>
                </button>
            </div>
        </div>
    </div>
</div>

            <!-- MAIN CONTENT AREA - Full width dengan padding responsif -->
            <main class="px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6">
                <div class="max-w-full">
                    
                    <!-- STATS GRID - Responsive columns -->
                    <div class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
                        
                        <!-- jadwal belajar terdekat -->
                       <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 transition-all duration-300 hover:shadow-xl">

  <!-- Top row: icon + badge -->
  <div class="flex items-start justify-between mb-6">
    <!-- Icon box -->
    <div class="p-4 rounded-2xl bg-red-50">
      <!-- clock icon -->
      <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 8v4l2 2m6-2a8 8 0 11-16 0 8 8 0 0116 0z" />
      </svg>
    </div>

    <!-- Badge kanan atas -->
    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-red-50 text-red-500">
      1 aktif
    </span>
  </div>

  <!-- Label -->
  <p class="text-sm tracking-widest text-gray-500 font-semibold mb-3">
    JADWAL BELAJAR
  </p>

  <!-- Title -->
  <h2 class="text-base sm:text-lg font-semibold text-[#1F2937] line-clamp-2">
    <?php echo htmlspecialchars($summary['nama_schedule'] ?? 'Belum ada jadwal'); ?>
  </h2>

  <!-- (Optional) Deskripsi kecil seperti contoh -->
  <p class="text-sm text-gray-500 font-medium mb-5">
    <?php echo htmlspecialchars($summary['deskripsi'] ?? 'Tidak ada deskripsi'); ?>
  </p>

  <!-- Date row (pakai icon kalender) -->
  <div class="flex items-center gap-2 text-gray-700">
    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>

    <span class="font-medium truncate">
      <?php echo htmlspecialchars($summary['tanggal'] ?? '-'); ?>
    </span>

    <!-- kalau kamu MAU nampilin jam juga (opsional) -->
    <?php if (!empty($summary['jam_mulai'])): ?>
      <span class="text-gray-400">•</span>
      <span class="font-medium truncate">
        <?php echo htmlspecialchars($summary['jam_mulai']); ?>
      </span>
    <?php endif; ?>
  </div>

</div>


                        <!-- DEADLINE CARD -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl border border-gray-100 p-4 sm:p-6 transition-all duration-300">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-gradient-to-br from-red-50 to-red-100">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-medium px-2 py-1 rounded-full <?php echo !empty($deadlines) ? 'bg-red-50 text-red-600' : 'bg-[#10B981]/10 text-[#10B981]'; ?>">
                                    <?php echo !empty($deadlines) ? count($deadlines) . ' aktif' : 'Tidak ada'; ?>
                                </span>
                            </div>
                            <h3 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider mb-1 sm:mb-2">Deadline Tugas</h3>
                            <?php if (!empty($deadlines)): ?>
                                <?php $nearest = $deadlines[0]; ?>
                                <div class="space-y-1 sm:space-y-2">
                                    <p class="text-base sm:text-lg font-semibold text-[#1F2937] line-clamp-2" title="<?php echo htmlspecialchars($nearest['title']); ?>">
                                        <?php echo htmlspecialchars($nearest['title']); ?>
                                    </p>
                                    
                                    <div class="flex items-center text-xs sm:text-sm text-gray-600">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-1.5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="font-medium truncate"><?php echo htmlspecialchars($nearest['deadline']); ?></span>
                                    </div>
                                    <?php if (!empty($nearest['keterangan'])): ?>
                                        <p class="text-xs sm:text-sm text-gray-500 truncate"><?php echo htmlspecialchars($nearest['status']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-1 sm:py-2">
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-1 sm:mb-2 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-600 font-medium text-sm sm:text-base">Tidak ada deadline</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- MATERI TERAKHIR CARD -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl border border-gray-100 p-4 sm:p-6 transition-all duration-300">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-gradient-to-br from-[#10B981]/10 to-[#10B981]/20">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-[#10B981]/10 text-[#10B981]">
                                    Terbaru
                                </span>
                            </div>
                            <h3 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider mb-1 sm:mb-2">Materi Terakhir diakses</h3>
                            <div class="min-h-[60px] sm:min-h-[80px]">
                                <?php $lastText = $lastMateri['nama_materi'] ?? 'Belum ada aktivitas'; ?>
                                <p class="text-base sm:text-lg font-semibold text-[#1F2937] line-clamp-2">
                                    <?php echo htmlspecialchars($lastText); ?>
                                </p>
                                <?php if ($lastText !== 'Belum ada aktivitas'): ?>
                                    <div class="flex items-center mt-1 sm:mt-2 text-xs sm:text-sm text-gray-500">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Baru dipelajari
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- TOTAL JAM BELAJAR CARD -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl border border-gray-100 p-4 sm:p-6 transition-all duration-300">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-gradient-to-br from-purple-50 to-purple-100">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-[#93C5FD]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-[#93C5FD]/10 text-[#2563EB]">
                                    dalam 7 hari
                            </div>
                            <h3 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider mb-1 sm:mb-2">Total Jam Belajar</h3>
                            <p class="text-2xl sm:text-3xl font-bold text-[#1F2937] mb-2 sm:mb-3"><?php echo htmlspecialchars($durasi['durasibelajar'] ?? '0'); ?></p>
                            
                        </div>
                    </div>

                    <!-- CHARTS SECTION - Stacked Vertically -->
                    <div class="space-y-4 sm:space-y-6">
                        
                        <!-- CHART 1: HARIAN -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl border border-gray-100 p-4 sm:p-6 transition-all duration-300">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-3 sm:gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg sm:text-xl font-bold text-[#1F2937] truncate">Aktivitas Belajar Harian</h3>
                                    <p class="text-gray-500 text-xs sm:text-sm mt-1 truncate">Durasi belajar 7 hari terakhir</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="p-2 rounded-lg bg-gradient-to-r from-blue-50 to-blue-100">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-[#2563EB]">Grafik Garis</span>
                                </div>
                            </div>
                            <div class="ww-full h-80 bg-white rounded-xl p-4">
                                <canvas id="chartMingguan"></canvas>
                            </div>
                        </div>
                        
                        <!-- CHART 2: PER MATERI -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl border border-gray-100 p-4 sm:p-6 transition-all duration-300">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-3 sm:gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg sm:text-xl font-bold text-[#1F2937] truncate">Durasi Belajar per Materi</h3>
                                    <p class="text-gray-500 text-xs sm:text-sm mt-1 truncate">Total waktu yang dihabiskan untuk setiap materi</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="p-2 rounded-lg bg-gradient-to-r from-[#10B981]/10 to-[#10B981]/20">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-[#10B981]">Grafik Batang</span>
                                </div>
                            </div>
                            <div class="w-full h-60 sm:h-72 md:h-80">
                                <canvas id="materiChart"></canvas>
                            </div>
                            <div class="mt-3 sm:mt-4 text-center">
                                <p class="text-xs sm:text-sm text-gray-500">Total durasi belajar berdasarkan materi</p>
                            </div>
                        </div>
                        
                    </div>

                    <!-- DEADLINE DETAILS SECTION (Optional) -->
                    
                </div>
            </main>

        </div>
    </div>
</div>

<script>
function exportLaporan(button) {
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `
        <svg class="animate-spin w-4 h-4 sm:w-5 sm:h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Memproses...
    `;

    const start = document.getElementById('start')?.value;
    const end   = document.getElementById('end')?.value;

    let url = '/pages/user/laporanCSV.php';
    if (start && end) url += `?start=${start}&end=${end}`;
    window.location.href = url;

    setTimeout(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    }, 800);
}

// Update waktu real-time
function updateTime() {
    const now = new Date();
    const timeElement = document.getElementById('currentTime');
    if (timeElement) {
        timeElement.textContent = now.toLocaleDateString('id-ID', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false 
        }) + ' • Teruslah belajar dan berkembang!';
    }
}

// Update setiap menit
setInterval(updateTime, 60000);

// Responsive adjustment untuk sidebar
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        // Pastikan sidebar di desktop adalah 152px (w-38 = 9.5rem = 152px)
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('w-64', 'w-56');
            sidebar.classList.add('w-38');
        }
    }
    
    // Inisialisasi charts
    initCharts();
});

// Handle window resize
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        if (window.innerWidth >= 768) {
            // Desktop: sidebar w-38
            sidebar.classList.remove('w-64', 'w-56');
            sidebar.classList.add('w-38');
        } else {
            // Mobile: lebar default
            sidebar.classList.remove('w-38');
            sidebar.classList.add('w-56');
        }
    }
});
</script>

<?php require_once PUBLIC_PATH . '/partials/footer.php'; ?>