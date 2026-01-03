<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/eventUser.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';


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
require_once PUBLIC_PATH . '/partials/header.php';
// Hapus sidebar require
require_once PUBLIC_PATH . '/partials/sidebar.php';

// Map tipe_event ke warna dan icon
$eventConfig = [
    'belajar' => [
        'color' => 'from-[#2563EB] to-[#93C5FD]',
        'bgColor' => 'bg-gradient-to-br from-[#2563EB]/10 to-[#93C5FD]/10',
        'borderColor' => 'border-[#93C5FD]',
        'icon' => 'fas fa-book',
        'badgeColor' => 'bg-[#93C5FD] text-[#1F2937] border-[#2563EB]'
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
<body class="bg-[#F9FAFB] min-h-screen">
    <!-- Main Content - Full width setelah sidebar -->
    <main class="md:ml-[152px] lg:ml-[152px] xl:ml-[152px] min-h-screen transition-all duration-300">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-100 px-3 sm:px-4 md:px-6 py-4 sm:py-5 md:py-6">
            <div class="w-full max-w-full mx-auto">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br <?= $config['color'] ?> rounded-lg sm:rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <?php if($tipe_event == 'belajar'): ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                <?php elseif($tipe_event == 'deadline'): ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                <?php elseif($tipe_event == 'ujian'): ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                <?php else: ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                <?php endif; ?>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl md:text-2xl font-semibold text-[#1F2937]">Detail Jadwal</h1>
                            <p class="text-gray-600 mt-0.5 text-xs sm:text-sm md:text-base">Informasi lengkap tentang jadwal Anda</p>
                        </div>
                    </div>
                    <div class="mt-0 flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 <?= $isPast ? 'bg-gray-400' : ($isToday ? 'bg-[#10B981] animate-pulse' : 'bg-[#2563EB]') ?> rounded-full"></div>
                            <span class="text-xs sm:text-sm text-gray-600">
                                <?= $isPast ? 'Selesai' : ($isToday ? 'Hari Ini' : 'Akan Datang') ?>
                            </span>
                        </div>
                        <a href="listJadwal.php" 
                           class="group inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 text-gray-700 
                                  border border-gray-200 rounded-lg hover:bg-gray-200 hover:shadow-sm 
                                  transition-all duration-200 font-medium text-xs sm:text-sm whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-3 sm:p-4 md:p-6 lg:p-8">
            <div class="w-full max-w-full mx-auto">
                <!-- Status Card -->
                <div class="mb-4 sm:mb-6 bg-gradient-to-r <?= $config['bgColor'] ?> border <?= $config['borderColor'] ?> rounded-lg sm:rounded-xl p-4 sm:p-5">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="p-2.5 sm:p-3 bg-white/80 rounded-lg sm:rounded-xl border <?= $config['borderColor'] ?>">
                                <?php if($tipe_event == 'belajar'): ?>
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <?php elseif($tipe_event == 'deadline'): ?>
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <?php elseif($tipe_event == 'ujian'): ?>
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <?php else: ?>
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg md:text-xl font-semibold text-[#1F2937]"><?= htmlspecialchars($data['nama_schedule']) ?></h2>
                                <div class="flex flex-wrap items-center gap-2 mt-1.5 sm:mt-2">
                                    <span class="px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium <?= $config['badgeColor'] ?> border rounded-full">
                                        <?= ucfirst($tipe_event) ?>
                                    </span>
                                    <?php if ($isToday): ?>
                                        <span class="px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20 rounded-full">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            HARI INI
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($isPast): ?>
                                        <span class="px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200 rounded-full">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            SELESAI
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($data['remind_before_minutes'])): ?>
                                        <span class="px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium bg-yellow-100 text-yellow-700 border border-yellow-200 rounded-full">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                            REMINDER
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Information Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 md:gap-6 mb-4 sm:mb-6">
                    <!-- Date & Time Card -->
                    <div class="bg-white border border-gray-200 rounded-lg sm:rounded-xl p-4 sm:p-5 hover:shadow-sm transition-all duration-200">
                        <div class="flex items-center gap-3 mb-3 sm:mb-4">
                            <div class="p-2 sm:p-2.5 bg-gradient-to-br from-[#2563EB] to-[#93C5FD] rounded-lg">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm sm:text-base md:text-lg font-semibold text-[#1F2937]">Waktu & Tanggal</h3>
                        </div>
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Tanggal</p>
                                <div class="flex items-center gap-2 text-[#1F2937]">
                                    <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-medium text-sm sm:text-base"><?= $tanggal_formatted ?></span>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <div>
                                    <p class="text-xs sm:text-sm text-gray-500 mb-1">Mulai</p>
                                    <div class="flex items-center gap-2 text-[#1F2937]">
                                        <svg class="w-4 h-4 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium text-sm sm:text-base"><?= $jam_mulai ?></span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm text-gray-500 mb-1">Selesai</p>
                                    <div class="flex items-center gap-2 text-[#1F2937]">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                                        </svg>
                                        <span class="font-medium text-sm sm:text-base"><?= $jam_selesai ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-3 sm:pt-4 border-t border-gray-100">
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Durasi</p>
                                <div class="flex items-center gap-2 text-[#1F2937]">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium text-sm sm:text-base"><?= $data['durasi'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Card -->
                    <div class="bg-white border border-gray-200 rounded-lg sm:rounded-xl p-4 sm:p-5 hover:shadow-sm transition-all duration-200">
                        <div class="flex items-center gap-3 mb-3 sm:mb-4">
                            <div class="p-2 sm:p-2.5 bg-gradient-to-br from-[#10B981] to-[#34D399] rounded-lg">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm sm:text-base md:text-lg font-semibold text-[#1F2937]">Deskripsi</h3>
                        </div>
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Nama Jadwal</p>
                                <p class="text-[#1F2937] font-medium text-sm sm:text-base"><?= htmlspecialchars($data['nama_schedule']) ?></p>
                            </div>
                            <div class="pt-3 sm:pt-4 border-t border-gray-100">
                                <p class="text-xs sm:text-sm text-gray-500 mb-2">Keterangan</p>
                                <?php if (!empty($data['deskripsi'])): ?>
                                    <div class="bg-gray-50/50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                        <p class="text-gray-700 text-sm sm:text-base"><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-6 sm:py-8 text-gray-400">
                                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                        </svg>
                                        <p class="text-xs sm:text-sm">Tidak ada deskripsi</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Settings & Reminder Card -->
                    <div class="bg-white border border-gray-200 rounded-lg sm:rounded-xl p-4 sm:p-5 hover:shadow-sm transition-all duration-200">
                        <div class="flex items-center gap-3 mb-3 sm:mb-4">
                            <div class="p-2 sm:p-2.5 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm sm:text-base md:text-lg font-semibold text-[#1F2937]">Pengaturan</h3>
                        </div>
                        <div class="space-y-3 sm:space-y-4">
                            
                            <div class="pt-3 sm:pt-4 border-t border-gray-100">
                                <p class="text-xs sm:text-sm text-gray-500 mb-1">Pengingat</p>
                                <?php if (!empty($data['remind_before_minutes'])): ?>
                                    <div class="flex items-center gap-2 text-[#1F2937]">
                                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                        <span class="font-medium text-sm sm:text-base"><?= $data['remind_before_minutes'] ?> menit sebelum</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Anda akan mendapatkan notifikasi sebelum jadwal dimulai</p>
                                <?php else: ?>
                                    <div class="text-center py-4 text-gray-400">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                        <p class="text-xs sm:text-sm">Tidak ada pengingat</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="pt-3 sm:pt-4 border-t border-gray-100">
                                <p class="text-xs sm:text-sm text-gray-500 mb-2">Status</p>
                                <div class="flex items-center gap-2">
                                    <?php if ($isPast): ?>
                                        <div class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs sm:text-sm font-medium w-full text-center">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Sudah Selesai
                                        </div>
                                    <?php elseif ($isToday): ?>
                                        <div class="px-3 py-1.5 bg-gradient-to-r from-[#10B981]/10 to-[#10B981]/5 text-[#10B981] border border-[#10B981]/20 rounded-lg text-xs sm:text-sm font-medium w-full text-center">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Berlangsung Hari Ini
                                        </div>
                                    <?php else: ?>
                                        <div class="px-3 py-1.5 bg-gradient-to-r from-[#2563EB]/10 to-[#93C5FD]/5 text-[#2563EB] border border-[#2563EB]/20 rounded-lg text-xs sm:text-sm font-medium w-full text-center">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Akan Datang
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white border border-gray-200 rounded-lg sm:rounded-xl p-4 sm:p-5">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs sm:text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Detail jadwal terakhir diakses: <?= date('d/m/Y H:i') ?></span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 sm:gap-3">
                            <a href="listJadwal.php" 
                               class="px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium text-xs sm:text-sm flex items-center gap-2 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali
                            </a>
                            <a href="editJadwal.php?id=<?= $data['id_schedule'] ?>" 
                               class="px-3 sm:px-4 py-2 sm:py-2.5 bg-gradient-to-r from-[#2563EB] to-[#93C5FD] text-white rounded-lg hover:from-[#1D4ED8] hover:to-[#2563EB] transition-all duration-200 font-medium text-xs sm:text-sm flex items-center gap-2 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Jadwal
                            </a>
                            <form action="hapusJadwal.php" method="POST" style="display:inline" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                <input type="hidden" name="id" value="<?= $data['id_schedule'] ?>">
                                <button type="submit" 
                                        class="px-3 sm:px-4 py-2 sm:py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 font-medium text-xs sm:text-sm flex items-center gap-2 whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Responsive sidebar handling -->
    <script>
        const checkSidebar = () => {
            const wrapper = document.querySelector('main');
            if (window.innerWidth < 768) {
                wrapper.classList.remove('md:ml-[152px]', 'lg:ml-[152px]', 'xl:ml-[152px]');
            } else {
                wrapper.classList.add('md:ml-[152px]', 'lg:ml-[152px]', 'xl:ml-[152px]');
            }
        };

        // Initial check
        checkSidebar();

        // Adjust on resize
        window.addEventListener('resize', checkSidebar);
    </script>

    <!-- Custom Styles untuk responsivitas -->
    <style>
        /* Animations */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .animate-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        /* Custom scrollbar konsisten */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #93C5FD;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2563EB;
        }

        /* Focus styles konsisten */
        input:focus, textarea:focus, select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Responsive text sizes untuk mobile sangat kecil */
        @media (max-width: 359px) {
            .text-xs {
                font-size: 0.7rem;
            }
            
            .text-sm {
                font-size: 0.8rem;
            }
            
            .px-3 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            .py-2\.5 {
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }
        }

        /* Optimasi untuk tablet landscape */
        @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
            .lg\:grid-cols-3 {
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
            }
        }

        /* Optimasi untuk desktop kecil */
        @media (min-width: 1025px) and (max-width: 1280px) {
            main {
                margin-left: 152px;
            }
        }

        /* Untuk layar sangat besar */
        @media (min-width: 1536px) {
            .max-w-full {
                max-width: 90%;
            }
        }

        /* Glassmorphism effect untuk modern browser */
        @supports (backdrop-filter: blur(8px)) {
            .backdrop-blur-sm {
                backdrop-filter: blur(8px);
            }
        }

        /* Hover effects */
        .hover\:shadow-sm:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
    </style>
</body>
</html>