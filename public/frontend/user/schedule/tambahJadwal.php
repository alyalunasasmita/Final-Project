<?php
require_once __DIR__ . '/../../../../backend/AuthMiddleware.php';
require_once __DIR__ . '/../../../../backend/eventUser.php';

use App\Schedule\Schedule;
use App\AuthMiddleware;

// Auth check
$user = AuthMiddleware::authUser();
$userId = $user['id'];

// Proses CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $schedule = new Schedule($userId);

        $schedule->tambahSchedule(
            $_POST['nama'],
            $_POST['deskripsi'] ?? null,
            $_POST['tanggal'],
            $_POST['tipe_event'],
            $_POST['jam_mulai'],
            $_POST['jam_selesai'],
            $_POST['remind_before_minutes'] !== '' 
                ? (int) $_POST['remind_before_minutes'] 
                : null
        );

        // Log activity
        AuthMiddleware::logCRUD('create', 'schedule');

        header("Location: listJadwal.php");
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
require_once __DIR__ . '/../../../assets/layout/header.php';
require_once __DIR__ . '/../../../assets/layout/sidebar.php';
?>
<body class="bg-gradient-to-br from-gray-50 to-blue-50/30">
    <!-- Main Content - Full width after sidebar -->
    <main class="md:ml-64 min-h-screen transition-all duration-300">
        <!-- Status Header -->
        <div class="bg-white/80 backdrop-blur-sm border-b border-gray-200/50 px-4 py-4 md:px-8 md:py-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-calendar-plus text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Tambah Jadwal Baru</h1>
                        <div class="flex items-center gap-3 mt-2">
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
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

        <!-- Form Container -->
        <div class="p-4 md:p-8">
            <?php if (!empty($error)): ?>
                <div class="max-w-4xl mx-auto mb-6 p-4 bg-gradient-to-r from-red-50 to-orange-50 border border-red-200/50 text-red-700 rounded-xl backdrop-blur-sm">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">Gagal menambahkan jadwal</p>
                            <p class="text-sm mt-1"><?= htmlspecialchars($error) ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden">
                    <!-- Form Header -->
                    <div class="px-6 py-5 border-b border-gray-100/50 bg-gradient-to-r from-blue-50/80 to-purple-50/80">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center shadow-sm">
                                    <i class="fas fa-calendar-alt text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">Form Tambah Jadwal</h2>
                                    <p class="text-gray-600 text-sm">Isi data dengan lengkap untuk membuat jadwal baru</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Content -->
                    <form method="POST" action="" class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Kolom Kiri -->
                            <div class="space-y-6">
                                <!-- Nama -->
                                <div class="group">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            <span class="text-red-500">*</span> Nama Kegiatan
                                        </label>
                                        <span class="text-xs text-gray-500">Required</span>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-blue-500">
                                            <i class="fas fa-heading text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input type="text" 
                                               name="nama" 
                                               required
                                               class="pl-10 w-full px-4 py-3.5 bg-white/50 border border-gray-300/50 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-200 backdrop-blur-sm"
                                               placeholder="Contoh: Belajar Matematika Lanjutan">
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div class="group">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Deskripsi
                                        </label>
                                        <span class="text-xs text-gray-500">Optional</span>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute top-3 left-3 pointer-events-none group-focus-within:text-blue-500">
                                            <i class="fas fa-align-left text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <textarea name="deskripsi" 
                                                  rows="5"
                                                  class="pl-10 w-full px-4 py-3.5 bg-white/50 border border-gray-300/50 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-200 backdrop-blur-sm resize-none"
                                                  placeholder="Deskripsi detail tentang kegiatan ini..."></textarea>
                                    </div>
                                </div>

                                <!-- Tanggal -->
                                <div class="group">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            <span class="text-red-500">*</span> Tanggal
                                        </label>
                                        <span class="text-xs text-gray-500">Required</span>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-blue-500">
                                            <i class="fas fa-calendar-day text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input type="date" 
                                               name="tanggal" 
                                               required
                                               class="pl-10 w-full px-4 py-3.5 bg-white/50 border border-gray-300/50 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-200 backdrop-blur-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="space-y-6">
                                <!-- Tipe Event -->
                                <div class="group">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            <span class="text-red-500">*</span> Tipe Event
                                        </label>
                                        <span class="text-xs text-gray-500">Required</span>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-blue-500">
                                            <i class="fas fa-tag text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <select name="tipe_event" 
                                                required
                                                class="pl-10 w-full px-4 py-3.5 bg-white/50 border border-gray-300/50 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-200 backdrop-blur-sm appearance-none cursor-pointer">
                                            <option value="">-- Pilih Tipe Event --</option>
                                            <option value="belajar" class="py-2">📚 Belajar</option>
                                            <option value="deadline" class="py-2">⏰ Deadline</option>
                                            <option value="ujian" class="py-2">📝 Ujian</option>
                                            <option value="lainnya" class="py-2">📅 Lainnya</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Waktu Mulai & Selesai -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="group">
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700">
                                                <span class="text-red-500">*</span> Jam Mulai
                                            </label>
                                            <span class="text-xs text-gray-500">Required</span>
                                        </div>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-blue-500">
                                                <i class="fas fa-clock text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                            </div>
                                            <input type="time" 
                                                   name="jam_mulai" 
                                                   required
                                                   class="pl-10 w-full px-4 py-3.5 bg-white/50 border border-gray-300/50 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-200 backdrop-blur-sm">
                                        </div>
                                    </div>

                                    <div class="group">
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700">
                                                <span class="text-red-500">*</span> Jam Selesai
                                            </label>
                                            <span class="text-xs text-gray-500">Required</span>
                                        </div>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-blue-500">
                                                <i class="fas fa-clock text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                            </div>
                                            <input type="time" 
                                                   name="jam_selesai" 
                                                   required
                                                   class="pl-10 w-full px-4 py-3.5 bg-white/50 border border-gray-300/50 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-200 backdrop-blur-sm">
                                        </div>
                                    </div>
                                </div>

                                <!-- Reminder -->
                                <div class="group">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Pengingat (Reminder)
                                        </label>
                                        <span class="text-xs text-gray-500">Optional</span>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-blue-500">
                                            <i class="fas fa-bell text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <select name="remind_before_minutes"
                                                class="pl-10 w-full px-4 py-3.5 bg-white/50 border border-gray-300/50 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all duration-200 backdrop-blur-sm appearance-none cursor-pointer">
                                            <option value="">🔕 Tidak ada pengingat</option>
                                            <option value="5">🔔 5 menit sebelum</option>
                                            <option value="10">🔔 10 menit sebelum</option>
                                            <option value="15">🔔 15 menit sebelum</option>
                                            <option value="30">🔔 30 menit sebelum</option>
                                            <option value="60">🔔 1 jam sebelum</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons with Status -->
                        <div class="mt-8 pt-6 border-t border-gray-100/50">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="listJadwal.php" 
                                       class="px-6 py-3.5 border border-gray-300/50 text-gray-700 rounded-xl hover:bg-gray-50/80 transition-all duration-200 font-medium text-center backdrop-blur-sm hover:shadow-sm">
                                        <span class="flex items-center justify-center gap-2">
                                            <i class="fas fa-times"></i>
                                            Cancel
                                        </span>
                                    </a>
                                    <button type="submit" 
                                            class="px-8 py-3.5 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:from-blue-600 hover:to-purple-600 
                                                   shadow-sm hover:shadow-md transition-all duration-200 font-medium flex items-center justify-center gap-3 group relative">
                                        <div class="absolute -top-2 -right-2 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                        <i class="fas fa-save group-hover:rotate-12 transition-transform"></i>
                                        Submit Schedule
                                        <i class="fas fa-arrow-right text-sm opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200"></i>
                                    </button>
                                </div>
                            </div>
                            
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Custom Styles -->
    <style>
        /* Custom styles untuk form */
        input, textarea, select {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        
        
        
        
        .bg-gradient-to-r.from-blue-500.to-purple-500 {
            animation: progress 1s ease-out forwards;
        }
        
        /* Status badge glow */
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); }
            50% { box-shadow: 0 0 10px rgba(59, 130, 246, 0.8); }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            main {
                margin-left: 0 !important;
            }
            
            .lg\:grid-cols-2 {
                grid-template-columns: 1fr;
            }
            
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }
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
        
        
        .bg-green-500.animate-pulse {
            animation: status-pulse 1.5s ease-in-out infinite;
        }
    </style>
</body>
</html>