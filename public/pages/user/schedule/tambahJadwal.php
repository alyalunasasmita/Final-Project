<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/eventUser.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

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
require_once PUBLIC_PATH . '/partials/header.php';
require_once PUBLIC_PATH . '/partials/sidebar.php';
?>
<body class="bg-[#F9FAFB] min-h-screen">
    <!-- Main Content - Full width after sidebar -->
    <main class="md:ml-[152px] lg:ml-[152px] xl:ml-[152px] min-h-screen transition-all duration-300">
        <!-- Status Header -->
        <div class="bg-white border-b border-gray-100 px-3 sm:px-4 md:px-6 py-4 sm:py-5 md:py-6">
            <div class="w-full max-w-full mx-auto">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-[#2563EB] to-[#93C5FD] rounded-lg sm:rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-calendar-plus text-white text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl md:text-2xl font-semibold text-[#1F2937]">Tambah Jadwal Baru</h1>
                            <p class="text-gray-500 text-xs sm:text-sm mt-0.5">Buat jadwal kegiatan baru Anda</p>
                        </div>
                    </div>
                    <div class="mt-0">
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

        <!-- Form Container -->
        <div class="p-3 sm:p-4 md:p-6 lg:p-8">
            <?php if (!empty($error)): ?>
                <div class="w-full max-w-full mx-auto mb-4 sm:mb-6 p-3 sm:p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-sm sm:text-base">Gagal menambahkan jadwal</p>
                            <p class="text-xs sm:text-sm mt-1"><?= htmlspecialchars($error) ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="w-full max-w-full mx-auto">
                <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl shadow-sm overflow-hidden">
                    <!-- Form Header -->
                    <div class="px-4 sm:px-5 md:px-6 py-4 sm:py-5 border-b border-gray-100 bg-gradient-to-r from-[#2563EB]/5 to-[#93C5FD]/5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#2563EB] to-[#93C5FD] rounded-lg sm:rounded-xl flex items-center justify-center shadow-sm">
                                    <i class="fas fa-calendar-alt text-white text-sm sm:text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-base sm:text-lg md:text-xl font-semibold text-[#1F2937]">Form Tambah Jadwal</h2>
                                    <p class="text-gray-600 text-xs sm:text-sm">Isi data dengan lengkap untuk membuat jadwal baru</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Content -->
                    <form method="POST" action="" class="p-4 sm:p-5 md:p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 md:gap-6">
                            <!-- Kolom Kiri -->
                            <div class="space-y-4 sm:space-y-5 md:space-y-6">
                                <!-- Nama -->
                                <div class="group">
                                    <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                        <label class="block text-xs sm:text-sm font-medium text-[#1F2937]">
                                            <span class="text-red-500">*</span> Nama Kegiatan
                                        </label>
                                        <span class="text-xs text-gray-500">Required</span>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-[#2563EB]">
                                            <svg class="w-4 h-4 text-gray-400 group-focus-within:text-[#2563EB] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" 
                                               name="nama" 
                                               required
                                               class="pl-10 w-full px-3 sm:px-4 py-2.5 sm:py-3.5 bg-white border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#2563EB]/30 focus:border-[#2563EB] transition-all duration-200"
                                               placeholder="Contoh: Belajar Matematika Lanjutan">
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div class="group">
                                    <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                        <label class="block text-xs sm:text-sm font-medium text-[#1F2937]">
                                            Deskripsi
                                        </label>
                                        <span class="text-xs text-gray-500">Optional</span>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute top-3 left-3 pointer-events-none group-focus-within:text-[#2563EB]">
                                            <svg class="w-4 h-4 text-gray-400 group-focus-within:text-[#2563EB] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </div>
                                        <textarea name="deskripsi" 
                                                  rows="4"
                                                  class="pl-10 w-full px-3 sm:px-4 py-2.5 sm:py-3.5 bg-white border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#2563EB]/30 focus:border-[#2563EB] transition-all duration-200 resize-none"
                                                  placeholder="Deskripsi detail tentang kegiatan ini..."></textarea>
                                    </div>
                                </div>

                                <!-- Tanggal -->
                                <div class="group">
                                    <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                        <label class="block text-xs sm:text-sm font-medium text-[#1F2937]">
                                            <span class="text-red-500">*</span> Tanggal
                                        </label>
                                        <span class="text-xs text-gray-500">Required</span>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-[#2563EB]">
                                            <svg class="w-4 h-4 text-gray-400 group-focus-within:text-[#2563EB] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <input type="date" 
                                               name="tanggal" 
                                               required
                                               class="pl-10 w-full px-3 sm:px-4 py-2.5 sm:py-3.5 bg-white border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#2563EB]/30 focus:border-[#2563EB] transition-all duration-200">
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="space-y-4 sm:space-y-5 md:space-y-6">

                                <!-- Waktu Mulai & Selesai -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    <div class="group">
                                        <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                            <label class="block text-xs sm:text-sm font-medium text-[#1F2937]">
                                                <span class="text-red-500">*</span> Jam Mulai
                                            </label>
                                            <span class="text-xs text-gray-500">Required</span>
                                        </div>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-[#2563EB]">
                                                <svg class="w-4 h-4 text-gray-400 group-focus-within:text-[#2563EB] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <input type="time" 
                                                   name="jam_mulai" 
                                                   required
                                                   class="pl-10 w-full px-3 sm:px-4 py-2.5 sm:py-3.5 bg-white border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#2563EB]/30 focus:border-[#2563EB] transition-all duration-200">
                                        </div>
                                    </div>

                                    <div class="group">
                                        <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                            <label class="block text-xs sm:text-sm font-medium text-[#1F2937]">
                                                <span class="text-red-500">*</span> Jam Selesai
                                            </label>
                                            <span class="text-xs text-gray-500">Required</span>
                                        </div>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-[#2563EB]">
                                                <svg class="w-4 h-4 text-gray-400 group-focus-within:text-[#2563EB] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <input type="time" 
                                                   name="jam_selesai" 
                                                   required
                                                   class="pl-10 w-full px-3 sm:px-4 py-2.5 sm:py-3.5 bg-white border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#2563EB]/30 focus:border-[#2563EB] transition-all duration-200">
                                        </div>
                                    </div>
                                </div>

                                <!-- Reminder -->
                                <div class="group">
                                    <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                        <label class="block text-xs sm:text-sm font-medium text-[#1F2937]">
                                            Pengingat (Reminder)
                                        </label>
                                        <span class="text-xs text-gray-500">Optional</span>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-[#2563EB]">
                                            <svg class="w-4 h-4 text-gray-400 group-focus-within:text-[#2563EB] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                        </div>
                                        <select name="remind_before_minutes"
                                                class="pl-10 w-full px-3 sm:px-4 py-2.5 sm:py-3.5 bg-white border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#2563EB]/30 focus:border-[#2563EB] transition-all duration-200 appearance-none cursor-pointer">
                                            <option value="">Tidak ada pengingat</option>
                                            <option value="5">5 menit sebelum</option>
                                            <option value="10">10 menit sebelum</option>
                                            <option value="15">15 menit sebelum</option>
                                            <option value="30">30 menit sebelum</option>
                                            <option value="60">1 jam sebelum</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons with Status -->
                        <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-100">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                
                                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                    <a href="listJadwal.php" 
                                       class="order-2 sm:order-1 w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium text-center text-sm hover:shadow-sm">
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Batal
                                        </span>
                                    </a>
                                    <button type="submit" 
                                            class="order-1 sm:order-2 w-full sm:w-auto px-4 sm:px-8 py-2.5 sm:py-3.5 bg-gradient-to-r from-[#2563EB] to-[#93C5FD] text-white rounded-lg hover:from-[#1D4ED8] hover:to-[#2563EB] 
                                                   shadow-sm hover:shadow-md transition-all duration-200 font-medium flex items-center justify-center gap-3 group">
                                        <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                        </svg>
                                        Simpan Jadwal
                                        <svg class="w-4 h-4 text-sm opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            
                        </div>
                    </form>
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

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const nama = this.querySelector('[name="nama"]').value.trim();
            const tanggal = this.querySelector('[name="tanggal"]').value;
            const jamMulai = this.querySelector('[name="jam_mulai"]').value;
            const jamSelesai = this.querySelector('[name="jam_selesai"]').value;
            
            // Validasi input
            if (!nama) {
                e.preventDefault();
                alert('Nama kegiatan harus diisi!');
                return false;
            }
            
            if (!tanggal) {
                e.preventDefault();
                alert('Tanggal harus diisi!');
                return false;
            }
            
            if (!jamMulai) {
                e.preventDefault();
                alert('Jam mulai harus diisi!');
                return false;
            }
            
            if (!jamSelesai) {
                e.preventDefault();
                alert('Jam selesai harus diisi!');
                return false;
            }
            
            if (jamMulai >= jamSelesai) {
                e.preventDefault();
                alert('Jam selesai harus setelah jam mulai!');
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Menyimpan...';
            submitBtn.disabled = true;
            
            // Re-enable after 3 seconds if still processing (safety)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });

        // Date validation - set min date to today
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.querySelector('input[type="date"]');
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;
        });
    </script>

    <!-- Custom Styles untuk responsivitas -->
    <style>
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

        /* Optimasi untuk tablet landscape */
        @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
            .lg\:grid-cols-2 {
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem;
            }
            
            .space-y-6 > * + * {
                margin-top: 1.25rem;
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
    </style>
</body>
</html>