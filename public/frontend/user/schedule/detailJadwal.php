<?php
require_once __DIR__ . '/../../../../backend/AuthMiddleware.php';
require_once __DIR__ . '/../../../../backend/eventUser.php';

use App\Schedule\Schedule;
use App\AuthMiddleware;

// AUTH
$user = AuthMiddleware::authUser();
$userId = $user['id'];

// VALIDASI ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID tidak valid";
    exit;
}

$schedule = new Schedule($userId);
$data = $schedule->getScheduleById((int)$_GET['id']);

if (!$data) {
    echo "Data tidak ditemukan";
    exit;
}
require_once __DIR__ . '/../../../assets/layout/header.php';
require_once __DIR__ . '/../../../assets/layout/sidebar.php';

// Map tipe_event ke warna dan icon
$eventConfig = [
    'belajar' => [
        'color' => 'from-blue-500 to-blue-600',
        'bgColor' => 'bg-gradient-to-br from-blue-50 to-blue-100/50',
        'borderColor' => 'border-blue-200',
        'icon' => 'fas fa-book',
        'badgeColor' => 'bg-blue-100 text-blue-700 border-blue-200'
    ],
    'deadline' => [
        'color' => 'from-red-500 to-red-600',
        'bgColor' => 'bg-gradient-to-br from-red-50 to-red-100/50',
        'borderColor' => 'border-red-200',
        'icon' => 'fas fa-flag-checkered',
        'badgeColor' => 'bg-red-100 text-red-700 border-red-200'
    ],
    'ujian' => [
        'color' => 'from-purple-500 to-purple-600',
        'bgColor' => 'bg-gradient-to-br from-purple-50 to-purple-100/50',
        'borderColor' => 'border-purple-200',
        'icon' => 'fas fa-file-alt',
        'badgeColor' => 'bg-purple-100 text-purple-700 border-purple-200'
    ],
    'lainnya' => [
        'color' => 'from-gray-500 to-gray-600',
        'bgColor' => 'bg-gradient-to-br from-gray-50 to-gray-100/50',
        'borderColor' => 'border-gray-200',
        'icon' => 'fas fa-calendar',
        'badgeColor' => 'bg-gray-100 text-gray-700 border-gray-200'
    ]
];

$tipe_event = $data['tipe_event'] ?? 'lainnya';
if (!array_key_exists($tipe_event, $eventConfig)) {
    $tipe_event = 'lainnya';
}
$config = $eventConfig[$tipe_event];

// Format tanggal
$tanggal_formatted = date('l, d F Y', strtotime($data['tanggal']));
$jam_mulai = date('H:i', strtotime($data['jam_mulai']));
$jam_selesai = date('H:i', strtotime($data['jam_selesai']));

// Cek apakah event sudah lewat atau masih akan datang
$isPast = strtotime($data['tanggal'] . ' ' . $data['jam_selesai']) < time();
$isToday = date('Y-m-d', strtotime($data['tanggal'])) == date('Y-m-d');
?>
<body class="bg-gradient-to-br from-gray-50 to-blue-50/30">
    <!-- Main Content - Full width after sidebar -->
    <main class="md:ml-64 min-h-screen transition-all duration-300">
        <!-- Header Section -->
        <div class="bg-white/80 backdrop-blur-sm border-b border-gray-200/50 px-4 py-4 md:px-8 md:py-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br <?= $config['color'] ?> rounded-xl flex items-center justify-center shadow-sm">
                        <i class="<?= $config['icon'] ?> text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Jadwal</h1>
                        <p class="text-gray-600 mt-1 text-sm md:text-base">Informasi lengkap tentang jadwal Anda</p>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 <?= $isPast ? 'bg-gray-400' : ($isToday ? 'bg-green-500 animate-pulse' : 'bg-blue-500') ?> rounded-full"></div>
                        <span class="text-sm text-gray-600">
                            <?= $isPast ? 'Selesai' : ($isToday ? 'Hari Ini' : 'Akan Datang') ?>
                        </span>
                    </div>
                    <a href="listJadwal.php" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-gray-100 to-white text-gray-700 
                              border border-gray-300/50 rounded-xl hover:bg-gray-50 hover:shadow-sm 
                              transition-all duration-200 font-medium text-sm backdrop-blur-sm group">
                        <i class="fas fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-4 md:p-8">
            <div class="max-w-4xl mx-auto">
                <!-- Status Card -->
                <div class="mb-6 bg-gradient-to-r <?= $config['bgColor'] ?> border <?= $config['borderColor'] ?> rounded-xl p-5 backdrop-blur-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-white/80 rounded-xl border <?= $config['borderColor'] ?>">
                                <i class="<?= $config['icon'] ?> text-2xl <?= str_replace('from-', 'text-', explode(' ', $config['color'])[0]) ?>"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($data['nama_schedule']) ?></h2>
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <span class="px-3 py-1 text-sm font-medium <?= $config['badgeColor'] ?> border rounded-full">
                                        <?= ucfirst($tipe_event) ?>
                                    </span>
                                    <?php if ($isToday): ?>
                                        <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-700 border border-green-200 rounded-full animate-pulse">
                                            <i class="fas fa-calendar-day mr-1"></i>HARI INI
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($isPast): ?>
                                        <span class="px-3 py-1 text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200 rounded-full">
                                            <i class="fas fa-check-circle mr-1"></i>SELESAI
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($data['remind_before_minutes'])): ?>
                                        <span class="px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-700 border border-yellow-200 rounded-full">
                                            <i class="fas fa-bell mr-1"></i>REMINDER
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 text-right">
                            <p class="text-sm text-gray-600">ID Jadwal</p>
                            <p class="text-lg font-mono font-bold text-gray-800">#<?= $data['id_schedule'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- Detail Information Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Date & Time Card -->
                    <div class="bg-white/90 backdrop-blur-sm border border-gray-200/50 rounded-xl p-5 hover:shadow-sm transition-all duration-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2.5 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Waktu & Tanggal</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tanggal</p>
                                <div class="flex items-center gap-2 text-gray-800">
                                    <i class="fas fa-calendar-day text-blue-500"></i>
                                    <span class="font-medium"><?= $tanggal_formatted ?></span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Mulai</p>
                                    <div class="flex items-center gap-2 text-gray-800">
                                        <i class="fas fa-play-circle text-green-500"></i>
                                        <span class="font-medium"><?= $jam_mulai ?></span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Selesai</p>
                                    <div class="flex items-center gap-2 text-gray-800">
                                        <i class="fas fa-stop-circle text-red-500"></i>
                                        <span class="font-medium"><?= $jam_selesai ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <p class="text-sm text-gray-500 mb-1">Durasi</p>
                                <div class="flex items-center gap-2 text-gray-800">
                                    <i class="fas fa-hourglass-half text-purple-500"></i>
                                    <span class="font-medium"><?= $data['durasi'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Card -->
                    <div class="bg-white/90 backdrop-blur-sm border border-gray-200/50 rounded-xl p-5 hover:shadow-sm transition-all duration-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2.5 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg">
                                <i class="fas fa-align-left text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Deskripsi</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Nama Jadwal</p>
                                <p class="text-gray-800 font-medium"><?= htmlspecialchars($data['nama_schedule']) ?></p>
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <p class="text-sm text-gray-500 mb-2">Keterangan</p>
                                <?php if (!empty($data['deskripsi'])): ?>
                                    <div class="bg-gray-50/50 rounded-lg p-4 border border-gray-200/50">
                                        <p class="text-gray-700"><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-8 text-gray-400">
                                        <i class="fas fa-comment-slash text-2xl mb-2"></i>
                                        <p>Tidak ada deskripsi</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Settings & Reminder Card -->
                    <div class="bg-white/90 backdrop-blur-sm border border-gray-200/50 rounded-xl p-5 hover:shadow-sm transition-all duration-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2.5 bg-gradient-to-br from-green-500 to-green-600 rounded-lg">
                                <i class="fas fa-cog text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Pengaturan</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tipe Event</p>
                                <div class="flex items-center gap-2">
                                    <div class="p-2 rounded-lg <?= str_replace('bg-', 'bg-', $config['badgeColor']) ?> border <?= str_replace('border-', 'border-', explode(' ', $config['badgeColor'])[2]) ?>">
                                        <i class="<?= $config['icon'] ?> <?= str_replace('from-', 'text-', explode(' ', $config['color'])[0]) ?>"></i>
                                    </div>
                                    <span class="font-medium text-gray-800"><?= ucfirst($tipe_event) ?></span>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <p class="text-sm text-gray-500 mb-1">Pengingat</p>
                                <?php if (!empty($data['remind_before_minutes'])): ?>
                                    <div class="flex items-center gap-2 text-gray-800">
                                        <i class="fas fa-bell text-yellow-500"></i>
                                        <span class="font-medium"><?= $data['remind_before_minutes'] ?> menit sebelum</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Anda akan mendapatkan notifikasi sebelum jadwal dimulai</p>
                                <?php else: ?>
                                    <div class="text-center py-4 text-gray-400">
                                        <i class="fas fa-bell-slash text-lg mb-1"></i>
                                        <p class="text-sm">Tidak ada pengingat</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <p class="text-sm text-gray-500 mb-2">Status</p>
                                <div class="flex items-center gap-2">
                                    <?php if ($isPast): ?>
                                        <div class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium w-full text-center">
                                            <i class="fas fa-check-circle mr-2"></i> Sudah Selesai
                                        </div>
                                    <?php elseif ($isToday): ?>
                                        <div class="px-3 py-1.5 bg-gradient-to-r from-green-100 to-green-50 text-green-700 border border-green-200 rounded-lg text-sm font-medium w-full text-center animate-pulse">
                                            <i class="fas fa-calendar-day mr-2"></i> Berlangsung Hari Ini
                                        </div>
                                    <?php else: ?>
                                        <div class="px-3 py-1.5 bg-gradient-to-r from-blue-100 to-blue-50 text-blue-700 border border-blue-200 rounded-lg text-sm font-medium w-full text-center">
                                            <i class="fas fa-clock mr-2"></i> Akan Datang
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white/90 backdrop-blur-sm border border-gray-200/50 rounded-xl p-5">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar-check text-green-500"></i>
                                <span>Detail jadwal terakhir diakses: <?= date('d/m/Y H:i') ?></span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="listJadwal.php" 
                               class="px-5 py-2.5 border border-gray-300/50 text-gray-700 rounded-xl hover:bg-gray-50/80 transition-all duration-200 font-medium flex items-center gap-2">
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </a>
                            <a href="editJadwal.php?id=<?= $data['id_schedule'] ?>" 
                               class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium flex items-center gap-2">
                                <i class="fas fa-edit"></i>
                                Edit Jadwal
                            </a>
                            <form action="hapusJadwal.php" method="POST" style="display:inline" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                <input type="hidden" name="id" value="<?= $data['id_schedule'] ?>">
                                <button type="submit" 
                                        class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 font-medium flex items-center gap-2">
                                    <i class="fas fa-trash"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Custom Styles -->
    <style>
        /* Animations */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .animate-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        /* Glassmorphism effect */
        .backdrop-blur-sm {
            backdrop-filter: blur(8px);
        }
        
        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            main {
                margin-left: 0 !important;
            }
            
            .lg\:grid-cols-3 {
                grid-template-columns: 1fr;
            }
            
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }
        }
        
        /* Hover effects */
        .hover\:shadow-sm:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
    </style>
</body>
</html>