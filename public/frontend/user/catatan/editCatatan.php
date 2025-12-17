<?php
session_start();
require_once __DIR__ . '/../../../../backend/AuthMiddleware.php';
use App\AuthMiddleware;
AuthMiddleware::authUser();

require_once __DIR__ . '/../../../../backend/catatanUser.php';
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

require_once __DIR__ . '/../../../assets/layout/header.php';
require_once __DIR__ . '/../../../assets/layout/sidebar.php';
?>

<!-- WRAPPER UTAMA -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50/30 md:ml-64 md:w-[calc(100%-16rem)]">
    <!-- Responsive margin untuk sidebar -->

    <!-- HEADER SECTION -->
    <!-- HEADER SECTION -->
<div class="sticky top-0 z-30 bg-white/80 backdrop-blur-sm border-b border-gray-200/60 shadow-sm">
    <div class="px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <!-- Kiri: Judul dan Info -->
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Edit Catatan
                </h1>
                <p class="mt-1 text-sm sm:text-base text-gray-600">
                    Perbarui dan tingkatkan catatan belajar Anda
                </p>
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <div class="inline-flex items-center px-3 py-1.5 bg-blue-50/80 border border-blue-100 rounded-lg">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-xs sm:text-sm font-medium text-blue-700">
                            ID: <?= $id ?>
                        </span>
                    </div>
                    <div class="inline-flex items-center px-3 py-1.5 bg-purple-50/80 border border-purple-100 rounded-lg">
                        <svg class="w-3.5 h-3.5 text-purple-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-medium text-purple-700">
                            Dibuat: <?= date('d M Y', strtotime($note['created_at'] ?? 'now')) ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Kanan: Tombol Kembali - Alignment khusus -->
            <div class="flex items-center h-full">
                <a href="lihatCatatan.php"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-all duration-200 hover:-translate-y-0.5 self-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>
</div>

    <!-- MAIN CONTENT -->
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="max-w-4xl mx-auto">
            
            <?php if ($error): ?>
            <!-- Error Alert -->
            <div class="mb-6 animate-fade-in">
                <div class="p-4 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200/60 rounded-xl shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-rose-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-red-800">Gagal menyimpan</h3>
                            <p class="mt-1 text-sm text-red-700"><?= htmlspecialchars($error) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- FORM SECTION -->
            <div class="animate-slide-up">
                <div class="bg-white/80 backdrop-blur-sm border border-gray-200/60 rounded-2xl shadow-xl overflow-hidden">
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-blue-50/50 to-purple-50/50 border-b border-gray-200/60 p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-center sm:text-left">
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                                    Edit Catatan
                                </h2>
                                <p class="mt-1 text-gray-600">
                                    Update isi catatan Anda. Perubahan akan disimpan secara otomatis.
                                </p>
                                <div class="mt-3 flex items-center justify-center sm:justify-start gap-2">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Terakhir edit: <?= date('H:i') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Content -->
                    <div class="p-6 sm:p-8">
                        <form method="POST" class="space-y-8">
                            <!-- Judul Input -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Judul Catatan
                                    </label>
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="judul" 
                                           value="<?= htmlspecialchars($note['judul'] ?? '') ?>"
                                           class="w-full pl-10 pr-4 py-3.5 bg-white border border-gray-300/60 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:outline-none transition-all duration-200 shadow-sm hover:shadow"
                                           placeholder="Berikan judul yang menarik...">
                                </div>
                            
                            </div>

                            <!-- Isi Catatan Input -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Isi Catatan
                                    </label>
                                    <span class="text-xs text-gray-500">*Wajib diisi</span>
                                </div>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                        </svg>
                                    </div>
                                    <textarea name="catatan"
                                              rows="15"
                                              class="w-full pl-10 pr-4 py-3.5 bg-white border border-gray-300/60 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 focus:outline-none transition-all duration-200 shadow-sm hover:shadow resize-none text-gray-700 leading-relaxed"
                                              placeholder="Tuliskan isi catatan Anda di sini..."
                                              required><?= htmlspecialchars($note['catatan'] ?? '') ?></textarea>
                                </div>
                                
                            </div>

                            <!-- Form Actions -->
                            <div class="pt-8 border-t border-gray-200/60">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    
                                    
                                    <!-- Buttons -->
                                    <div class="flex items-center gap-3">
                                        <a href="lihatCatatan.php"
                                           class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow">
                                            Batal
                                        </a>
                                        
                                        <button type="submit"
                                                class="group px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
                                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                            </svg>
                                            <span>Update Catatan</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.4s ease-out;
}

/* Textarea styling */
textarea {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    line-height: 1.6;
}

textarea::-webkit-scrollbar {
    width: 8px;
}

textarea::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

textarea::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

textarea::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .md\:ml-64 {
        margin-left: 0 !important;
    }
    .md\:w-\[calc\(100\%-16rem\)\] {
        width: 100% !important;
    }
}

@media (max-width: 640px) {
    .p-8 {
        padding: 1.5rem !important;
    }
    .space-y-8 > * + * {
        margin-top: 1.5rem;
    }
    textarea {
        rows: 12;
    }
}

/* Mobile optimizations */
@media (max-width: 480px) {
    .flex-col {
        flex-direction: column;
    }
    .gap-3 {
        gap: 0.75rem;
    }
    .text-sm {
        font-size: 0.875rem;
    }
    .text-xs {
        font-size: 0.75rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .bg-gradient-to-br {
        background-image: linear-gradient(to bottom right, #1f2937, #111827);
    }
    .bg-white\/80 {
        background-color: rgba(31, 41, 55, 0.8);
    }
    .text-gray-800 {
        color: #e5e7eb;
    }
    .text-gray-600 {
        color: #9ca3af;
    }
}
</style>

<!-- JavaScript for character count -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('textarea[name="catatan"]');
    const charCount = document.querySelector('.text-xs.text-gray-500:last-child');
    
    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length + ' karakter';
        });
    }
    
    // Auto-expand textarea
    function autoExpand(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    }
    
    textarea.addEventListener('input', function() {
        autoExpand(this);
    });
    
    // Initial adjustment
    autoExpand(textarea);
});
</script>