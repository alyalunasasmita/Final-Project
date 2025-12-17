<?php
session_set_cookie_params([
  'path' => '/',
  'httponly' => true,
  'samesite' => 'Lax'
]);
session_start();

require_once __DIR__ . '/../../../backend/AuthMiddleware.php';
require_once __DIR__ . '/../../../backend/Service/dashboardService.php'; 

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
    
} catch (\Exception $e) {
    header('Location: ../../frontend/login.php');
    exit;
}

require_once __DIR__ . '/../../assets/layout/header.php';
require_once __DIR__ . '/../../assets/layout/sidebar.php';
?>

<!-- MAIN CONTAINER - Full width layout -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    
    <!-- Mobile: content langsung, Desktop: setelah sidebar -->
    <div class="lg:ml-64 transition-all duration-300">
        
        <!-- FULL WIDTH CONTAINER - mengambil seluruh lebar setelah sidebar -->
        <div class="w-full">
            
            <!-- HEADER SECTION - Responsive -->
            <div class="bg-white shadow-sm border-b border-gray-200 px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="max-w-full mx-auto">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 truncate">
                                Selamat Datang, <span class="text-blue-600"><?= $nama; ?></span> 👋
                            </h1>
                            <p class="text-gray-600 mt-1 text-sm sm:text-base truncate" id="currentTime">
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
                                echo $formatter->format($now) . ' • Teruslah belajar dan berkembang!';
                                ?>
                            </p>
                        </div>
                        
                        <!-- EXPORT BUTTON - Responsive sizing -->
                        <button 
                            onclick="exportLaporan(this)" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:scale-95"
                        >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="truncate">Export Laporan</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT AREA - Full width dengan padding responsif -->
            <main class="px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6">
                <div class="max-w-full">
                    
                    <!-- STATS GRID - Responsive columns -->
                    <div class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
                        
                        <!-- TOTAL MATERI CARD -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl border border-gray-100 p-4 sm:p-6 transition-all duration-300">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-gradient-to-br from-blue-50 to-blue-100">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-50 text-blue-600">
                                    <?php echo htmlspecialchars($summary['materi_dipelajari'] ?? '0'); ?> dipelajari
                                </span>
                            </div>
                            <h3 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider mb-1 sm:mb-2">Total Materi</h3>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2 sm:mb-3"><?php echo htmlspecialchars($summary['total_materi'] ?? '0'); ?></p>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 sm:h-2">
                                <div class="bg-blue-500 h-1.5 sm:h-2 rounded-full" style="width: <?php 
                                    $total = $summary['total_materi'] ?? 1;
                                    $dipelajari = $summary['materi_dipelajari'] ?? 0;
                                    echo min(100, ($dipelajari / max(1, $total)) * 100);
                                ?>%"></div>
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
                                <span class="text-xs font-medium px-2 py-1 rounded-full <?php echo !empty($deadlines) ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'; ?>">
                                    <?php echo !empty($deadlines) ? count($deadlines) . ' aktif' : 'Tidak ada'; ?>
                                </span>
                            </div>
                            <h3 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider mb-1 sm:mb-2">Deadline</h3>
                            <?php if (!empty($deadlines)): ?>
                                <?php $nearest = $deadlines[0]; ?>
                                <div class="space-y-1 sm:space-y-2">
                                    <p class="text-base sm:text-lg font-semibold text-gray-800 line-clamp-2" title="<?php echo htmlspecialchars($nearest['nama_tugas']); ?>">
                                        <?php echo htmlspecialchars($nearest['nama_tugas']); ?>
                                    </p>
                                    <div class="flex items-center text-xs sm:text-sm text-gray-600">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-1.5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="font-medium truncate"><?php echo htmlspecialchars($nearest['tanggal']); ?></span>
                                    </div>
                                    <?php if (!empty($nearest['keterangan'])): ?>
                                        <p class="text-xs sm:text-sm text-gray-500 truncate"><?php echo htmlspecialchars($nearest['keterangan']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-1 sm:py-2">
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-1 sm:mb-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-600 font-medium text-sm sm:text-base">Tidak ada deadline</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- MATERI TERAKHIR CARD -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl border border-gray-100 p-4 sm:p-6 transition-all duration-300">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-2 sm:p-3 rounded-lg sm:rounded-xl bg-gradient-to-br from-green-50 to-green-100">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-green-50 text-green-600">
                                    Terbaru
                                </span>
                            </div>
                            <h3 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider mb-1 sm:mb-2">Materi Terakhir</h3>
                            <div class="min-h-[60px] sm:min-h-[80px]">
                                <?php $lastText = $lastMateri['last_materi'] ?? 'Belum ada aktivitas'; ?>
                                <p class="text-base sm:text-lg font-semibold text-gray-800 line-clamp-2">
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
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-medium px-2 py-1 rounded-full bg-purple-50 text-purple-600">
                                    <?php echo htmlspecialchars($summary['persen_jam'] ?? '0'); ?>%
                                </span>
                            </div>
                            <h3 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider mb-1 sm:mb-2">Total Jam Belajar</h3>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2 sm:mb-3"><?php echo htmlspecialchars($summary['total_durasi'] ?? '0'); ?></p>
                            
                        </div>
                    </div>

                    <!-- CHARTS SECTION - Stacked Vertically -->
                    <div class="space-y-4 sm:space-y-6">
                        
                        <!-- CHART 1: HARIAN -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl border border-gray-100 p-4 sm:p-6 transition-all duration-300">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-3 sm:gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 truncate">Aktivitas Belajar Harian</h3>
                                    <p class="text-gray-500 text-xs sm:text-sm mt-1 truncate">Durasi belajar 7 hari terakhir</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="p-2 rounded-lg bg-gradient-to-r from-blue-50 to-blue-100">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-blue-600">Grafik Garis</span>
                                </div>
                            </div>
                            <div class="w-full h-60 sm:h-72 md:h-80">
                                <canvas id="chartMingguan"></canvas>
                            </div>
                        </div>
                        
                        <!-- CHART 2: PER MATERI -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl border border-gray-100 p-4 sm:p-6 transition-all duration-300">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-3 sm:gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 truncate">Durasi Belajar per Materi</h3>
                                    <p class="text-gray-500 text-xs sm:text-sm mt-1 truncate">Total waktu yang dihabiskan untuk setiap materi</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="p-2 rounded-lg bg-gradient-to-r from-green-50 to-green-100">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-green-600">Grafik Batang</span>
                                </div>
                            </div>
                            <div class="w-full h-60 sm:h-72 md:h-80">
                                <canvas id="materiChart"></canvas>
                            </div>
                            <div class="mt-3 sm:mt-4 text-center">
                        </div>
                        
                    </div>

                    <!-- DEADLINE DETAILS SECTION (Optional) -->
                    <?php if (!empty($deadlines) && count($deadlines) > 1): ?>
                    <div class="mt-6 sm:mt-8 bg-white rounded-xl sm:rounded-2xl shadow-md border border-gray-100 p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-3 sm:gap-4">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 truncate">Deadline Mendatang</h3>
                            <span class="px-3 py-1 text-xs sm:text-sm font-medium rounded-full bg-red-50 text-red-600 whitespace-nowrap">
                                <?php echo count($deadlines); ?> deadline
                            </span>
                        </div>
                        <div class="overflow-x-auto -mx-4 sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full align-middle">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th scope="col" class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                                            <th scope="col" class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th scope="col" class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                            <th scope="col" class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($deadlines as $index => $task): ?>
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-2 h-2 rounded-full <?php echo $index === 0 ? 'bg-red-500' : 'bg-orange-400'; ?> mr-2 sm:mr-3"></div>
                                                    <span class="text-sm font-medium text-gray-800 truncate max-w-[150px] sm:max-w-none"><?php echo htmlspecialchars($task['nama_tugas']); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($task['tanggal']); ?></td>
                                            <td class="px-3 sm:px-4 py-3 text-sm text-gray-500 max-w-[100px] sm:max-w-none truncate"><?php echo htmlspecialchars($task['keterangan'] ?? '-'); ?></td>
                                            <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                                                <span class="px-2 sm:px-3 py-1 text-xs font-medium rounded-full <?php echo $index === 0 ? 'bg-red-50 text-red-600' : 'bg-yellow-50 text-yellow-600'; ?>">
                                                    <?php echo $index === 0 ? 'Segera' : 'Mendatang'; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </main>

        </div>
    </div>
</div>

<!-- CHARTS JS -->
<script src="/chart/chartUser.js"></script>
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

    let url = '/frontend/user/laporanCSV.php';
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
</script>

<?php require_once __DIR__ . '/../../assets/layout/footer.php'; ?>