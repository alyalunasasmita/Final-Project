<?php
session_start();
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/catatanUser.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

use App\AuthMiddleware;
AuthMiddleware::authUser();
use App\catat\Catatan;

$id = $_GET['id'] ?? 0;
$error = '';
$success = '';

$catatanObj = new Catatan();
$note = $catatanObj->getCatatanById($id);

if (!$note) {
    header('Location: lihatCatatan.php?error=notfound');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';
    $catatan = $_POST['catatan'] ?? '';
    
    if (empty($catatan)) {
        $error = 'Catatan tidak boleh kosong';
    } else {
        if ($catatanObj->updateCatatan($id, $catatan, $judul)) {
            header('Location: lihatCatatan.php?success=1');
            exit;
        } else {
            $error = 'Gagal memperbarui catatan';
        }
    }
}

require_once PUBLIC_PATH . '/partials/header.php';
require_once PUBLIC_PATH . '/partials/sidebar.php';

?>

<!-- WRAPPER UTAMA -->
<div class="min-h-screen bg-[#F9FAFB]">
    
    <!-- Desktop: setelah sidebar w-38 (152px) -->
    <div class="md:ml-[152px] lg:ml-[152px] xl:ml-[152px] transition-all duration-300">
        
        <!-- Full width container -->
        <div class="w-full">
            
            <!-- HEADER SECTION -->
            <div class="w-full bg-white border-b border-gray-100 px-3 sm:px-4 md:px-6 py-3 sm:py-4">
                <div class="max-w-full">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                        <!-- Kiri: Judul dan Info -->
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-1.5 h-10 bg-gradient-to-b from-[#2563EB] to-[#93C5FD] rounded-full"></div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-semibold text-[#1F2937]">
                                    Edit Catatan
                                </h1>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-[#2563EB] bg-[#2563EB]/10 px-2 py-0.5 rounded">
                                        ID: <?= $id ?>
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        Dibuat: <?= date('d M Y', strtotime($note['created_at'] ?? 'now')) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kanan: Tombol Kembali -->
                        <div>
                            <a href="lihatCatatan.php"
                               class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200 text-xs sm:text-sm">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Kembali</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="w-full p-3 sm:p-4 md:p-6">
                <div class="max-w-2xl lg:max-w-4xl mx-auto">
                    
                    <?php if ($error): ?>
                    <!-- Error Alert -->
                    <div class="mb-4 sm:mb-6">
                        <div class="p-3 sm:p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700"><?= htmlspecialchars($error) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- FORM SECTION -->
                    <div class="bg-white border border-gray-200 rounded-lg sm:rounded-xl shadow-sm">
                        <!-- Form Header -->
                        <div class="p-4 sm:p-6 border-b border-gray-100">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#2563EB] to-[#93C5FD] rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl font-semibold text-[#1F2937]">
                                        Edit Catatan
                                    </h2>
                                    <p class="text-gray-500 text-xs sm:text-sm mt-0.5">
                                        Update isi catatan Anda
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Content -->
                        <div class="p-4 sm:p-6">
                            <form method="POST" class="space-y-4 sm:space-y-6">
                                <!-- Judul Input -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-[#1F2937]">
                                        Judul Catatan
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" 
                                               name="judul" 
                                               value="<?= htmlspecialchars($note['judul'] ?? '') ?>"
                                               class="w-full pl-9 sm:pl-10 pr-4 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors duration-200"
                                               placeholder="Berikan judul yang menarik...">
                                    </div>
                                </div>

                                <!-- Isi Catatan Input -->
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-sm font-medium text-[#1F2937]">
                                            Isi Catatan
                                        </label>
                                        <span class="text-xs text-gray-500">*Wajib diisi</span>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                        </div>
                                        <textarea name="catatan"
                                                  rows="12"
                                                  class="w-full pl-9 sm:pl-10 pr-4 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors duration-200 resize-none text-gray-700 leading-relaxed"
                                                  placeholder="Tuliskan isi catatan Anda di sini..."
                                                  required><?= htmlspecialchars($note['catatan'] ?? '') ?></textarea>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="pt-4 sm:pt-6 border-t border-gray-100">
                                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                                        <a href="lihatCatatan.php"
                                           class="w-full sm:w-auto px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200 text-center text-sm">
                                            Batal
                                        </a>
                                        
                                        <button type="submit"
                                                class="w-full sm:w-auto px-6 py-2.5 bg-[#2563EB] hover:bg-[#2563EB]/90 text-white font-medium rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                            </svg>
                                            <span>Update Catatan</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
/* Line clamp utilities */
.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
}

.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}

/* Textarea styling */
textarea {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    line-height: 1.6;
    min-height: 200px;
}

textarea::-webkit-scrollbar {
    width: 6px;
}

textarea::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

textarea::-webkit-scrollbar-thumb {
    background: #93C5FD;
    border-radius: 4px;
}

textarea::-webkit-scrollbar-thumb:hover {
    background: #2563EB;
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .text-sm {
        font-size: 0.8125rem;
    }
    
    .text-xs {
        font-size: 0.75rem;
    }
    
    textarea {
        rows: 10;
        min-height: 180px;
    }
    
    .py-2\.5 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
}

/* Tablet adjustments */
@media (min-width: 640px) and (max-width: 768px) {
    .max-w-2xl {
        max-width: 100%;
    }
    
    textarea {
        rows: 14;
        min-height: 250px;
    }
}

/* Desktop adjustments */
@media (min-width: 769px) {
    .lg\:max-w-4xl {
        max-width: 56rem;
    }
}

/* Untuk layar sangat kecil */
@media (max-width: 359px) {
    .text-sm {
        font-size: 0.75rem;
    }
    
    .text-xs {
        font-size: 0.6875rem;
    }
    
    .gap-3 {
        gap: 0.5rem;
    }
    
    .p-3 {
        padding: 0.5rem;
    }
    
    .pl-9 {
        padding-left: 2rem;
    }
    
    textarea {
        rows: 8;
        min-height: 160px;
    }
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
</style>

<!-- JavaScript for auto-expand textarea -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('textarea[name="catatan"]');
    
    // Auto-expand textarea
    function autoExpand(textarea) {
        textarea.style.height = 'auto';
        const newHeight = Math.min(textarea.scrollHeight, 500); // Max height 500px
        textarea.style.height = newHeight + 'px';
    }
    
    if (textarea) {
        textarea.addEventListener('input', function() {
            autoExpand(this);
        });
        
        // Initial adjustment
        autoExpand(textarea);
    }
    
    // Responsive adjustment
    function adjustForScreenSize() {
        const screenWidth = window.innerWidth;
        
        if (screenWidth < 480) {
            // Smaller font on mobile
            document.body.style.fontSize = '14px';
        } else {
            document.body.style.fontSize = '';
        }
    }
    
    // Initial adjustment
    adjustForScreenSize();
    
    // Adjust on resize
    window.addEventListener('resize', adjustForScreenSize);
});
</script>