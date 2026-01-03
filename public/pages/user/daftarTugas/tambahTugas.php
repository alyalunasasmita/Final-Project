<?php
session_start();
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/tugas.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

use App\AuthMiddleware;
use App\Tugas;

// AUTH
$userAuth = AuthMiddleware::authUser();
$userId = $userAuth['id'] ?? 0;

$task = new Tugas((int)$userId);

// CONTROLLER (proses POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? null;
    $status      = $_POST['status'] ?? 'belum progres';
    $deadline    = $_POST['deadline'] ?? null;

    $res = $task->tambahTugas($title, $description, $status, $deadline);

    $_SESSION['flash'] = $res;

    header("Location: lihatTugas.php");
    exit;
}

require_once PUBLIC_PATH . '/partials/header.php';
require_once PUBLIC_PATH . '/partials/sidebar.php';

// ambil flash
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<!-- WRAPPER UTAMA - Konsisten dengan lihatCatatan.php -->
<div class="min-h-screen bg-[#F9FAFB]">
    
    <!-- Desktop: setelah sidebar w-38 (152px) -->
    <div class="md:ml-[152px] lg:ml-[152px] xl:ml-[152px] transition-all duration-300">
        
        <!-- Full width container -->
        <div class="w-full">
            
            <!-- HEADER SECTION - Konsisten -->
            <div class="w-full px-3 sm:px-4 md:px-6 py-3 sm:py-4 bg-white border-b border-gray-100">
                <div class="max-w-full">
                    <!-- Gunakan grid untuk control yang lebih baik -->
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 items-start md:items-center gap-3 sm:gap-4">
                        
                        <!-- Left Column -->
                        <div class="w-full md:col-span-1">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-1.5 h-10 bg-gradient-to-b from-[#2563EB] to-[#93C5FD] rounded-full"></div>
                                <div>
                                    <h1 class="text-xl sm:text-2xl font-semibold text-[#1F2937]">
                                        Tambah Tugas Baru
                                    </h1>
                                    <p class="text-gray-500 text-xs sm:text-sm mt-0.5">
                                        Buat tugas baru untuk manajemen pembelajaran
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:mt-3 flex flex-wrap items-center gap-2">
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-[#2563EB]/10 border border-[#2563EB]/20 rounded-lg">
                                    <div class="w-1.5 h-1.5 bg-[#2563EB] rounded-full mr-1.5"></div>
                                    <span class="text-xs font-medium text-[#2563EB]">
                                        Form Input
                                    </span>
                                </div>
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-[#10B981]/10 border border-[#10B981]/20 rounded-lg">
                                    <svg class="w-3 h-3 text-[#10B981] mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs font-medium text-[#10B981]">
                                        <?= date('d M Y') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Button di kanan -->
                        <div class="w-full md:col-span-1 md:flex md:justify-end">
                            <a href="lihatTugas.php"
                               class="group w-full md:w-auto inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Kembali ke Tugas</span>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="w-full p-3 sm:p-4 md:p-6">
                <div class="w-full max-w-full mx-auto">
                    
                    <?php if ($flash): ?>
                    <div class="w-full mb-4 sm:mb-6 p-3 sm:p-4 rounded-lg border <?= $flash['success'] ? 'border-[#10B981]/30 bg-[#10B981]/10' : 'border-red-200 bg-red-50' ?>">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <?php if ($flash['success']): ?>
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#10B981]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium <?= $flash['success'] ? 'text-[#10B981]' : 'text-red-700' ?>">
                                    <?= htmlspecialchars($flash['message']) ?>
                                </p>
                                <?php if (!empty($flash['errors'])): ?>
                                <ul class="mt-2 list-disc pl-5 text-sm <?= $flash['success'] ? 'text-[#10B981]' : 'text-red-700' ?> space-y-1">
                                    <?php foreach ($flash['errors'] as $e): ?>
                                    <li><?= htmlspecialchars($e) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- FORM SECTION - Konsisten styling -->
                    <div class="w-full bg-white border border-gray-200 rounded-xl overflow-hidden">
                        <!-- Form Content -->
                        <div class="w-full p-4 sm:p-6 md:p-8">
                            <form method="POST" class="space-y-4 sm:space-y-6">
                                
                                <!-- Judul Input -->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-[#1F2937]">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Judul Tugas <span class="text-red-500">*</span>
                                        </span>
                                    </label>
                                    <input type="text" 
                                           name="title" 
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors text-sm sm:text-base"
                                           placeholder="Masukkan judul tugas"
                                           required>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Beri judul yang jelas dan deskriptif
                                    </p>
                                </div>

                                <!-- Deskripsi Input -->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-[#1F2937]">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Deskripsi (Opsional)
                                        </span>
                                    </label>
                                    <textarea name="description" 
                                              rows="4"
                                              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors resize-none text-sm sm:text-base"
                                              placeholder="Tambahkan detail atau instruksi tugas"></textarea>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Detail tambahan tentang tugas ini
                                    </p>
                                </div>

                                <!-- Status dan Deadline Row -->
                                <div class="grid grid-cols-1 xs:grid-cols-2 gap-3 sm:gap-4">
                                    
                                    <!-- Status Field -->
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-[#1F2937]">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                Status
                                            </span>
                                        </label>
                                        <select name="status" 
                                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors text-sm sm:text-base">
                                            <option value="belum progres">Belum Progres</option>
                                            <option value="dalam progres">Dalam Progres</option>
                                            <option value="selesai">Selesai</option>
                                        </select>
                                        
                                        <!-- Status Preview -->
                                        <div class="mt-2">
                                            <div class="inline-flex items-center px-2.5 py-1.5 bg-red-50 text-red-700 border border-red-100 rounded-lg text-xs font-medium">
                                                <div class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></div>
                                                Belum Progres
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">Status default saat dibuat</p>
                                        </div>
                                    </div>

                                    <!-- Deadline Field -->
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-[#1F2937]">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                Deadline (Opsional)
                                            </span>
                                        </label>
                                        <div class="relative">
                                            <input type="date" 
                                                   name="deadline"
                                                   class="w-full pl-3 sm:pl-4 pr-10 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors text-sm sm:text-base"
                                                   placeholder="Format: 2025-12-25 23:59:00">
                                            <button type="button" 
                                                    onclick="document.querySelector('input[name=\"deadline\"]').value = ''"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-red-500 transition-colors"
                                                    title="Hapus deadline">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada deadline</p>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex items-center justify-between pt-4 sm:pt-6 border-t border-gray-200">
                                    <a href="lihatTugas.php"
                                       class="px-4 sm:px-6 py-2 sm:py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-sm sm:text-base">
                                        Batal
                                    </a>
                                    
                                    <button type="submit"
                                            class="px-4 sm:px-6 py-2 sm:py-2.5 bg-[#2563EB] hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm sm:text-base">
                                        Buat Tugas
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles - Konsisten -->
<style>
/* Custom scrollbar */
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

/* Responsive adjustments */
@media (max-width: 479px) {
    .xs\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .text-sm {
        font-size: 0.8125rem;
    }
    
    .text-xs {
        font-size: 0.75rem;
    }
    
    .px-3 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
}

/* Custom breakpoint untuk extra small */
@media (min-width: 480px) and (max-width: 639px) {
    .xs\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Focus styles untuk form */
input:focus, textarea:focus, select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Untuk layar sangat kecil */
@media (max-width: 359px) {
    .text-xs {
        font-size: 0.7rem;
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
</style>

<script>
// Responsive handling untuk sidebar
const checkSidebar = () => {
    const wrapper = document.querySelector('.min-h-screen > div');
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

// Update status preview saat dropdown berubah
document.querySelector('select[name="status"]').addEventListener('change', function() {
    const statusMap = {
        'belum progres': { label: 'Belum Progres', color: 'red', bgColor: 'bg-red-50', textColor: 'text-red-700', borderColor: 'border-red-100', dotColor: 'bg-red-500' },
        'dalam progres': { label: 'Dalam Progres', color: 'yellow', bgColor: 'bg-yellow-50', textColor: 'text-yellow-700', borderColor: 'border-yellow-100', dotColor: 'bg-yellow-500' },
        'selesai': { label: 'Selesai', color: 'green', bgColor: 'bg-green-50', textColor: 'text-green-700', borderColor: 'border-green-100', dotColor: 'bg-green-500' }
    };
    
    const selected = this.value;
    const statusInfo = statusMap[selected];
    
    const previewElement = document.querySelector('.inline-flex.items-center.px-2\\.5');
    previewElement.className = `inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium border ${statusInfo.bgColor} ${statusInfo.textColor} ${statusInfo.borderColor}`;
    
    const dotElement = previewElement.querySelector('.w-1\\.5');
    dotElement.className = `w-1.5 h-1.5 rounded-full mr-1.5 ${statusInfo.dotColor}`;
    
    previewElement.innerHTML = dotElement.outerHTML + statusInfo.label;
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const title = this.querySelector('input[name="title"]').value.trim();
    
    if (!title) {
        e.preventDefault();
        alert('Judul tugas harus diisi!');
        return false;
    }
    
    // Optional: Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Membuat...';
    submitBtn.disabled = true;
    
    // Re-enable after 3 seconds if still processing (safety)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 3000);
});
</script>