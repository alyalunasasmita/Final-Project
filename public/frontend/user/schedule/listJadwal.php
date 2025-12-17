<?php
require_once __DIR__ . '/../../../../backend/AuthMiddleware.php';
require_once __DIR__ . '/../../../../backend/eventUser.php';

use App\Schedule\Schedule;
use App\AuthMiddleware;

$user = AuthMiddleware::authUser();
$userId = $user['id'];

$schedule = new Schedule($userId);
$data = $schedule->listSchedule();

require_once __DIR__ . '/../../../assets/layout/header.php';
require_once __DIR__ . '/../../../assets/layout/sidebar.php';
?>

<!-- Main Content - Full width after sidebar -->
<main class="w-full md:ml-64 min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 transition-all duration-300">
    <!-- Page Header -->
    <div class="w-full bg-white/80 backdrop-blur-sm border-b border-gray-200/50 px-4 py-4 md:px-8 md:py-6">
        <div class="w-full flex flex-col md:flex-row md:items-center justify-between">
            <div class="w-full md:w-auto">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    My Schedules
                </h1>
                <p class="text-gray-600 mt-2 text-sm md:text-base">Manage your learning schedule and deadlines</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-3 w-full md:w-auto">
                <!-- Add Schedule Button -->
                <a href="tambahJadwal.php" 
                   class="inline-flex items-center justify-center gap-2 px-4 md:px-5 py-2.5 md:py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:from-blue-600 hover:to-purple-600 
                          shadow-sm hover:shadow-md transition-all duration-200 font-medium group w-full md:w-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden md:inline">New Schedule</span>
                    <span class="md:hidden">New Schedule</span>
                </a>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            <!-- Today's Schedules -->
            <?php
            $today = date('Y-m-d');
            $todayCount = 0;
            foreach ($data as $row) {
                if ($row['tanggal'] == $today) {
                    $todayCount++;
                }
            }
            ?>
            <div class="w-full bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-xl p-4 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-2.5 rounded-lg bg-gradient-to-br from-green-100 to-green-50">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-600">Today</p>
                        <p class="text-xl font-bold text-gray-800"><?= $todayCount ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Upcoming Schedules -->
            <?php
            $upcomingCount = 0;
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            foreach ($data as $row) {
                if ($row['tanggal'] > $today && $row['tanggal'] <= date('Y-m-d', strtotime('+7 days'))) {
                    $upcomingCount++;
                }
            }
            ?>
            <div class="w-full bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-xl p-4 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-2.5 rounded-lg bg-gradient-to-br from-purple-100 to-purple-50">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-600">Upcoming</p>
                        <p class="text-xl font-bold text-gray-800"><?= $upcomingCount ?></p>
                    </div>
                </div>
            </div>
            
            <!-- All Schedules -->
            <div class="w-full bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-xl p-4 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-2.5 rounded-lg bg-gradient-to-br from-blue-100 to-blue-50">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-600">Total</p>
                        <p class="text-xl font-bold text-gray-800"><?= count($data) ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Past Schedules -->
            <?php
            $pastCount = 0;
            foreach ($data as $row) {
                if ($row['tanggal'] < $today) {
                    $pastCount++;
                }
            }
            ?>
            <div class="w-full bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-xl p-4 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-2.5 rounded-lg bg-gradient-to-br from-gray-100 to-gray-50">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs text-gray-600">Completed</p>
                        <p class="text-xl font-bold text-gray-800"><?= $pastCount ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Tabs -->
        <div class="w-full flex gap-2 mt-6 overflow-x-auto pb-2">
            <button class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-lg text-sm font-medium whitespace-nowrap shadow-sm flex-shrink-0">
                All Schedules
            </button>
            <button class="px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-300/50 rounded-lg text-sm font-medium whitespace-nowrap hover:bg-gray-50/80 flex-shrink-0">
                Today (<?= $todayCount ?>)
            </button>
            <button class="px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-300/50 rounded-lg text-sm font-medium whitespace-nowrap hover:bg-gray-50/80 flex-shrink-0">
                Upcoming (<?= $upcomingCount ?>)
            </button>
            <button class="px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-300/50 rounded-lg text-sm font-medium whitespace-nowrap hover:bg-gray-50/80 flex-shrink-0">
                Past (<?= $pastCount ?>)
            </button>
        </div>
    </div>

    <!-- Content Area -->
    <div class="w-full p-4 md:p-8">
        <?php if (empty($data)): ?>
            <!-- Empty State -->
            <div class="w-full max-w-md mx-auto text-center py-16 md:py-24">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-6 shadow-sm">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-3">No schedules yet</h3>
                <p class="text-gray-600 mb-8 max-w-sm mx-auto">Start organizing your study time by creating your first schedule. Stay on track with your learning goals!</p>
                <a href="tambahJadwal.php" 
                   class="inline-flex items-center justify-center gap-3 px-6 py-3.5 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:from-blue-600 hover:to-purple-600 
                          shadow-sm hover:shadow-md transition-all duration-200 font-medium group w-full md:w-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Your First Schedule
                </a>
                <p class="text-gray-500 text-sm mt-6">Stay organized and never miss a deadline!</p>
            </div>
        <?php else: ?>
            <!-- Schedule Cards Grid -->
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                <?php foreach ($data as $row): 
                    // Map tipe_event to colors and icons
                    $eventConfig = [
                        'ujian' => [
                            'color' => 'border-l-blue-500 bg-gradient-to-r from-blue-50/50 to-white',
                            'icon' => 'fas fa-users',
                            'iconColor' => 'text-blue-500',
                            'badgeColor' => 'bg-blue-100 text-blue-700 border-blue-200'
                        ],
                        'Deadline' => [
                            'color' => 'border-l-red-500 bg-gradient-to-r from-red-50/50 to-white',
                            'icon' => 'fas fa-flag-checkered',
                            'iconColor' => 'text-red-500',
                            'badgeColor' => 'bg-red-100 text-red-700 border-red-200'
                        ],
                        'Reminder' => [
                            'color' => 'border-l-green-500 bg-gradient-to-r from-green-50/50 to-white',
                            'icon' => 'fas fa-bell',
                            'iconColor' => 'text-green-500',
                            'badgeColor' => 'bg-green-100 text-green-700 border-green-200'
                        ],
                        'belajar' => [
                            'color' => 'border-l-purple-500 bg-gradient-to-r from-purple-50/50 to-white',
                            'icon' => 'fas fa-book',
                            'iconColor' => 'text-purple-500',
                            'badgeColor' => 'bg-purple-100 text-purple-700 border-purple-200'
                        ]
                    ];
                    
                    // Get event type from database, default to 'Reminder' if not valid
                    $tipe_event = $row['tipe_event'] ?? 'Reminder';
                    if (!array_key_exists($tipe_event, $eventConfig)) {
                        $tipe_event = 'Reminder'; // Default jika tidak valid
                    }
                    
                    $config = $eventConfig[$tipe_event];
                    
                    // Check if event is today
                    $isToday = ($row['tanggal'] == $today);
                    $isPast = ($row['tanggal'] < $today);
                ?>
                    <div class="w-full bg-white/90 backdrop-blur-sm border border-gray-200/50 rounded-xl hover:shadow-lg transition-all duration-300 <?= $config['color'] ?> border-l-4 transform hover:-translate-y-1">
                        <div class="w-full p-5">
                            <!-- Card Header -->
                            <div class="w-full flex items-start justify-between mb-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                                        <div class="p-2 rounded-lg <?= str_replace('text-', 'bg-', $config['iconColor']) ?>/10 border <?= str_replace('text-', 'border-', $config['iconColor']) ?>/20">
                                            <i class="<?= $config['icon'] ?> <?= $config['iconColor'] ?> text-sm"></i>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-medium <?= $config['badgeColor'] ?> border rounded-full flex-shrink-0">
                                            <?= $tipe_event ?>
                                        </span>
                                        <?php if ($isToday): ?>
                                            <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 border border-green-200 rounded-full animate-pulse flex-shrink-0">
                                                TODAY
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($isPast): ?>
                                            <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200 rounded-full flex-shrink-0">
                                                COMPLETED
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="w-full text-lg font-semibold text-gray-800 line-clamp-1 group-hover:text-blue-600 transition-colors">
                                        <?= htmlspecialchars($row['nama_schedule']) ?>
                                    </h3>
                                    <?php if (!empty($row['deskripsi'])): ?>
                                        <p class="w-full text-gray-600 text-sm mt-2 line-clamp-2">
                                            <?= htmlspecialchars($row['deskripsi']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Time and Date Info -->
                            <div class="w-full space-y-3 mb-5">
                                <!-- Time -->
                                <div class="flex items-center text-gray-700">
                                    <div class="p-1.5 rounded bg-blue-50 mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-500">Time</p>
                                        <p class="text-sm font-medium truncate">
                                            <?= date('h:i A', strtotime($row['jam_mulai'])) ?> - <?= date('h:i A', strtotime($row['jam_selesai'])) ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Date -->
                                <div class="flex items-center text-gray-700">
                                    <div class="p-1.5 rounded bg-purple-50 mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-500">Date</p>
                                        <p class="text-sm font-medium truncate">
                                            <?= date('l, F j, Y', strtotime($row['tanggal'])) ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Reminder -->
                                <?php if (!empty($row['remind_before_minutes'])): ?>
                                    <div class="flex items-center text-gray-700">
                                        <div class="p-1.5 rounded bg-green-50 mr-3 flex-shrink-0">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs text-gray-500">Reminder</p>
                                            <p class="text-sm font-medium truncate">
                                                <?= $row['remind_before_minutes'] ?> minutes before
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="w-full flex items-center gap-2 pt-5 border-t border-gray-100/50">
                                <a href="detailJadwal.php?id=<?= $row['id_schedule'] ?>" 
                                   class="flex-1 flex items-center justify-center gap-2 py-2.5 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Details
                                </a>
                                
                                <!-- Edit Button -->
                                <a href="editJadwal.php?id=<?= $row['id_schedule'] ?>" 
                                   class="flex items-center justify-center p-2.5 text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-200 group flex-shrink-0"
                                   title="Edit">
                                    <svg class="w-4 h-4 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                
                                <!-- Delete Button -->
                                 <form action="hapusJadwal.php" method="POST" class="inline m-0 p-0 delete-form">
                                    <input type="hidden" name="id" value="<?= $row['id_schedule'] ?>">
                                    <input type="hidden" class="schedule-name" value="<?= htmlspecialchars($row['nama_schedule']) ?>">
                                    <button type="button"
                                            class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors global-delete-btn flex-shrink-0"
                                            title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Floating Action Button for Mobile -->
            <div class="md:hidden fixed bottom-6 right-6 z-30">
                <a href="tambahJadwal.php" 
                   class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-500 text-white rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Custom Styles -->
<style>
/* Smooth animations */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.animate-pulse {
    animation: pulse 1.5s ease-in-out infinite;
}

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

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.15);
}

/* Smooth transitions */
* {
    transition: background-color 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .grid-cols-2, .grid-cols-3, .grid-cols-4 {
        grid-template-columns: 1fr !important;
    }
    
    main {
        margin-left: 0 !important;
    }
    
    .backdrop-blur-sm {
        backdrop-filter: blur(8px);
    }
    
    /* Mobile optimization */
    .w-full {
        width: 100vw !important;
        max-width: 100vw !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    /* Ensure content doesn't overflow on mobile */
    body {
        overflow-x: hidden !important;
    }
}

/* Tablet adjustments */
@media (min-width: 641px) and (max-width: 768px) {
    .sm\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}

/* Desktop adjustments */
@media (min-width: 769px) {
    main {
        width: calc(100% - 16rem) !important; /* 64 = 16rem */
        margin-left: 16rem !important;
    }
}

/* Large desktop */
@media (min-width: 1536px) {
    .xl\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tangkap semua delete buttons
    document.querySelectorAll('.global-delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            const scheduleName = form.querySelector('.schedule-name').value;
            
            if (confirm(`Hapus jadwal "${scheduleName}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
                form.submit();
            }
        });
    });
});
</script>