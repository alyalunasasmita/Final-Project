<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/eventUser.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';


use App\Schedule\Schedule;
use App\AuthMiddleware;

$user = AuthMiddleware::authUser();
$userId = $user['id'];

$schedule = new Schedule($userId);
$data = $schedule->listSchedule();
require_once PUBLIC_PATH . '/partials/header.php';
require_once PUBLIC_PATH . '/partials/sidebar.php';
?>

<!-- Main Content - Full width after sidebar w-38 -->
<div class="min-h-screen bg-[#F9FAFB]">
    
    <!-- Desktop: setelah sidebar w-38 (152px) -->
    <div class="md:ml-[152px] lg:ml-[152px] xl:ml-[152px] transition-all duration-300">
        
        <!-- Full width container -->
        <div class="w-full">
            
            <!-- Minimal Header -->
            <div class="w-full px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-5 bg-white border-b border-gray-100">
                <div class="max-w-full">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 bg-[#F9FAFB] rounded-lg p-4">
    <div class="flex items-center gap-3 sm:gap-4">
        <div class="relative">
            <div class="w-2 h-10 bg-gradient-to-b from-[#2563EB] to-[#93C5FD] rounded-full"></div>
            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-3 h-0.5 bg-[#10B981] rounded-full"></div>
        </div>
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-[#1F2937]">Jadwal Saya</h1>
            <div class="flex items-center gap-2 mt-0.5">
                <div class="w-1.5 h-1.5 rounded-full bg-[#10B981] animate-pulse"></div>
                <p class="text-gray-500 text-xs sm:text-sm">Atur Jadwal Belajar Kamu Sendiri</p>
            </div>
        </div>
    </div>
    <div class="flex-shrink-0">
        <a href="tambahJadwal.php" 
           class="inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-white border border-[#2563EB] text-[#2563EB] rounded-lg hover:bg-[#2563EB] hover:text-white 
                  transition-all duration-200 text-xs sm:text-sm font-medium shadow-sm hover:shadow">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Tambah</span>
        </a>
    </div>
</div>
                    
                    <!-- Simple Stats (Max 2 cards) -->
                    <div class="w-full grid grid-cols-1 xs:grid-cols-2 gap-2 sm:gap-3 mt-4 sm:mt-6">
                        <?php
                        $today = date('Y-m-d');
                        $todayCount = 0;
                        $upcomingCount = 0;
                        
                        foreach ($data as $row) {
                            if ($row['tanggal'] == $today) {
                                $todayCount++;
                            }
                            if ($row['tanggal'] > $today) {
                                $upcomingCount++;
                            }
                        }
                        ?>
                        <!-- Today's Schedules -->
                        <div class="bg-white border border-gray-100 rounded-lg p-3 sm:p-4 hover:shadow-sm transition-shadow duration-200">
                            <div class="flex items-center">
                                <div class="p-1.5 sm:p-2 rounded-md bg-[#10B981]/10">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-2 sm:ml-3">
                                    <p class="text-xs text-gray-600">Hari Ini</p>
                                    <p class="text-base sm:text-lg font-medium text-[#1F2937]"><?= $todayCount ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upcoming Schedules -->
                        <div class="bg-white border border-gray-100 rounded-lg p-3 sm:p-4 hover:shadow-sm transition-shadow duration-200">
                            <div class="flex items-center">
                                <div class="p-1.5 sm:p-2 rounded-md bg-[#2563EB]/10">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-2 sm:ml-3">
                                    <p class="text-xs text-gray-600">Mendatang</p>
                                    <p class="text-base sm:text-lg font-medium text-[#1F2937]"><?= $upcomingCount ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="w-full p-3 sm:p-4 md:p-6">
                <?php if (empty($data)): ?>
                    <!-- Empty State Minimal -->
                    <div class="w-full max-w-xs sm:max-w-sm mx-auto text-center py-12 sm:py-16">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-medium text-[#1F2937] mb-2 sm:mb-3">Belum ada jadwal</h3>
                        <p class="text-gray-500 text-xs sm:text-sm mb-4 sm:mb-6">Buat jadwal pertama Anda untuk memulai</p>
                        <a href="tambahJadwal.php" 
                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#2563EB] text-white rounded-lg hover:bg-[#2563EB]/90 
                                  transition-all duration-200 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Buat Jadwal
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Minimal Schedule Cards Grid -->
                    <div class="w-full grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                        <?php foreach ($data as $row): 
                            // Simple color mapping menggunakan warna custom
                            $colorMap = [
                                'ujian' => 'bg-[#2563EB]/10 text-[#2563EB] border-[#2563EB]/20',
                                'Deadline' => 'bg-[#EF4444]/10 text-[#EF4444] border-[#EF4444]/20',
                                'Reminder' => 'bg-[#10B981]/10 text-[#10B981] border-[#10B981]/20',
                                'belajar' => 'bg-[#93C5FD]/10 text-[#2563EB] border-[#93C5FD]/20'
                            ];
                            
                            $tipe_event = $row['tipe_event'] ?? 'Reminder';
                            $badgeClass = $colorMap[$tipe_event] ?? $colorMap['Reminder'];
                            
                            $isToday = ($row['tanggal'] == $today);
                        ?>
                            <div class="bg-white border border-gray-200 rounded-lg hover:border-gray-300 transition-colors duration-200 hover:shadow-sm">
                                <div class="p-3 sm:p-4">
                                    <!-- Card Header -->
                                    <div class="flex items-start justify-between mb-2 sm:mb-3">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2 flex-wrap">
                                                <span class="px-1.5 sm:px-2 py-0.5 text-xs font-medium rounded border <?= $badgeClass ?> whitespace-nowrap">
                                                    <?= $tipe_event ?>
                                                </span>
                                                <?php if ($isToday): ?>
                                                    <span class="px-1.5 py-0.5 text-xs font-medium bg-[#10B981]/10 text-[#10B981] rounded border border-[#10B981]/20 whitespace-nowrap">
                                                        Hari Ini
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <h3 class="text-sm font-medium text-[#1F2937] line-clamp-2 mb-1">
                                                <?= htmlspecialchars($row['nama_schedule']) ?>
                                            </h3>
                                        </div>
                                    </div>

                                    <!-- Simple Info -->
                                    <div class="space-y-1.5 sm:space-y-2 mb-3 sm:mb-4">
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-3 h-3 mr-1.5 sm:mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-xs truncate">
                                                <?= date('H:i', strtotime($row['jam_mulai'])) ?>
                                            </span>
                                        </div>
                                        
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-3 h-3 mr-1.5 sm:mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-xs truncate">
                                                <?= date('j M Y', strtotime($row['tanggal'])) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Minimal Action Buttons -->
                                    <div class="flex items-center justify-between pt-2 sm:pt-3 border-t border-gray-100">
                                        <a href="detailJadwal.php?id=<?= $row['id_schedule'] ?>" 
                                           class="text-xs text-[#2563EB] hover:text-[#2563EB]/80 font-medium">
                                            Lihat
                                        </a>
                                        <div class="flex items-center gap-0.5 sm:gap-1">
                                            <a href="editJadwal.php?id=<?= $row['id_schedule'] ?>" 
                                               class="p-1 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-100"
                                               title="Edit">
                                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            
                                            <form action="hapusJadwal.php" method="POST" class="inline delete-form">
                                                <input type="hidden" name="id" value="<?= $row['id_schedule'] ?>">
                                                <input type="hidden" class="schedule-name" value="<?= htmlspecialchars($row['nama_schedule']) ?>">
                                                <button type="button"
                                                        class="p-1 text-gray-400 hover:text-red-500 rounded hover:bg-red-50 global-delete-btn"
                                                        title="Delete">
                                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Simple Mobile FAB -->
                    <div class="md:hidden fixed bottom-4 sm:bottom-6 right-4 sm:right-6 z-30">
                        <a href="tambahJadwal.php" 
                           class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-[#2563EB] text-white rounded-full shadow-lg hover:bg-[#2563EB]/90 transition-colors duration-200">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Responsive CSS -->
<style>
/* Line clamp utilities */
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom breakpoint untuk xs */
@media (min-width: 480px) {
    .xs\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Responsive padding adjustments */
@media (max-width: 480px) {
    .px-3 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .gap-3 {
        gap: 0.75rem;
    }
    
    .p-3 {
        padding: 0.75rem;
    }
}

/* Untuk layar sangat kecil */
@media (max-width: 360px) {
    .xs\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .text-sm {
        font-size: 0.8125rem;
    }
    
    .text-xs {
        font-size: 0.75rem;
    }
}

/* Scrollbar styling */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #F9FAFB;
}

::-webkit-scrollbar-thumb {
    background: #93C5FD;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #2563EB;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    document.querySelectorAll('.global-delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            const scheduleName = form.querySelector('.schedule-name').value;
            
            if (confirm(`Hapus jadwal "${scheduleName}"?`)) {
                form.submit();
            }
        });
    });
    
    // Responsive adjustment untuk layar kecil
    function adjustForSmallScreens() {
        const screenWidth = window.innerWidth;
        const cards = document.querySelectorAll('.bg-white.border');
        
        cards.forEach(card => {
            if (screenWidth < 480) {
                card.classList.add('text-sm');
                card.classList.remove('text-base');
            } else {
                card.classList.remove('text-sm');
                card.classList.add('text-base');
            }
        });
    }
    
    // Initial adjustment
    adjustForSmallScreens();
    
    // Adjust on resize
    window.addEventListener('resize', adjustForSmallScreens);
});
</script>