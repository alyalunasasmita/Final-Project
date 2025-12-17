<?php
// detail_catatan.php
require_once __DIR__ . '/../../../../backend/AuthMiddleware.php';
use App\AuthMiddleware;
AuthMiddleware::authUser();

// INCLUDE BACKEND
require_once __DIR__ . '/../../../../backend/catatanUser.php';
use App\catat\Catatan;

$catat = new Catatan($_SESSION['user_id']);

if (!isset($_GET['id'])) {
    header('Location: lihatCatatan.php');
    exit();
}

$id = intval($_GET['id']);
$data = $catat->getCatatanById($id);

if (!$data) {
    echo "Catatan tidak ditemukan.";
    exit();
}

require_once __DIR__ . '/../../../assets/layout/header.php';
require_once __DIR__ . '/../../../assets/layout/sidebar.php';
?>

<!-- WRAPPER UTAMA -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50/30 md:ml-64 md:w-[calc(100%-16rem)]">

    <!-- HEADER SECTION -->
    <div class="sticky top-0 z-30 bg-white/90 backdrop-blur-sm border-b border-gray-200/60 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <!-- Kiri: Judul dan Info -->
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                        Detail Catatan
                    </h1>
                    <p class="mt-1 text-sm sm:text-base text-gray-600">
                        Tinjau dan kelola catatan belajar Anda
                    </p>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <div class="inline-flex items-center px-3 py-1.5 bg-blue-50/80 border border-blue-100 rounded-lg">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                            <span class="text-xs sm:text-sm font-medium text-blue-700">
                                ID: <?= $id ?>
                            </span>
                        </div>
                        <?php if ($data['created_at']): ?>
                        <div class="inline-flex items-center px-3 py-1.5 bg-purple-50/80 border border-purple-100 rounded-lg">
                            <svg class="w-3.5 h-3.5 text-purple-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-xs sm:text-sm font-medium text-purple-700">
                                Dibuat: <?= date('d M Y', strtotime($data['created_at'])) ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Kanan: Tombol Aksi -->
                <div class="flex items-center gap-3 self-end sm:self-center">
                    <a href="lihatCatatan.php"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-all duration-200 hover:-translate-y-0.5">
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
        <div class="w-full max-w-6xl mx-auto">
            
            <!-- CARD DETAIL UTAMA -->
            <div class="bg-white/90 backdrop-blur-sm border border-gray-200/60 rounded-2xl shadow-xl overflow-hidden mb-8">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-blue-50/50 to-purple-50/50 border-b border-gray-200/60 px-6 py-6 sm:px-8 sm:py-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                        <!-- Info Kiri -->
                        <div class="flex-1 min-w-0">
                            <div class="mb-4">
                                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-3">
                                    <?= htmlspecialchars($data['judul'] ?? 'Tanpa Judul') ?>
                                </h2>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm">Dibaca <?= date('H:i') ?></span>
                                </div>
                            </div>
                            
                            <!-- Stats -->
                            <div class="flex flex-wrap gap-3">
                                <div class="inline-flex items-center px-4 py-2 bg-white/80 border border-gray-200/60 rounded-xl">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">
                                        <?= strlen($data['catatan']) ?> karakter
                                    </span>
                                </div>
                                <div class="inline-flex items-center px-4 py-2 bg-white/80 border border-gray-200/60 rounded-xl">
                                    <svg class="w-4 h-4 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">
                                        <?= date('d F Y', strtotime($data['created_at'] ?? 'now')) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Icon Kanan -->
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center border-2 border-white">
                                    <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card Body - Isi Catatan -->
                <div class="p-6 sm:p-8">
                    <div class="mb-2">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Isi Catatan</h3>
                            <span class="text-xs px-3 py-1 bg-blue-100 text-blue-700 rounded-full">Preview</span>
                        </div>
                        
                        <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200/60 rounded-2xl shadow-inner overflow-hidden">
                            <div class="p-6 sm:p-8 max-h-[600px] overflow-y-auto custom-scrollbar">
                                <div class="prose prose-blue max-w-none">
                                    <pre class="text-gray-700 leading-relaxed whitespace-pre-wrap font-sans text-base sm:text-lg">
<?= htmlspecialchars($data['catatan']) ?>
                                    </pre>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Word Count -->
                        <div class="mt-4 flex items-center justify-end">
                            <div class="inline-flex items-center px-3 py-1.5 bg-gray-100/60 rounded-lg">
                                <svg class="w-4 h-4 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm text-gray-600">
                                    <?= str_word_count($data['catatan']) ?> kata
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card Footer -->
                <div class="bg-gray-50/60 border-t border-gray-200/60 px-6 py-5 sm:px-8 sm:py-6">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Info Status -->
                        <div class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm">Catatan tersimpan dengan aman</span>
                        </div>
                        
                        <!-- Tombol Aksi -->
                        <div class="flex items-center gap-3">
                            <a href="hapuscatatan.php?id=<?= $id ?>" 
                               onclick="return confirm('Yakin ingin menghapus catatan ini?')"
                               class="group inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Hapus Catatan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ACTION BUTTONS BESAR -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <!-- Tombol Lihat Semua -->
                <a href="lihatCatatan.php"
                   class="group bg-white/90 backdrop-blur-sm border border-gray-200/60 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center mb-2">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-100 to-purple-100 rounded-xl flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Lihat Semua Catatan</h3>
                            </div>
                            <p class="text-gray-600 text-sm ml-13">Kembali ke daftar semua catatan</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </a>
                
                <!-- Tombol Edit -->
                <a href="editCatatan.php?id=<?= $id ?>" 
                   class="group bg-white/90 backdrop-blur-sm border border-gray-200/60 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center mb-2">
                                <div class="w-10 h-10 bg-gradient-to-r from-amber-100 to-yellow-100 rounded-xl flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Edit Catatan Ini</h3>
                            </div>
                            <p class="text-gray-600 text-sm ml-13">Perbarui atau tambah informasi</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Quick Stats -->
            <div class="mt-8">
                <div class="bg-white/90 backdrop-blur-sm border border-gray-200/60 rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Catatan</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50/50 border border-blue-100 rounded-xl">
                            <div class="text-2xl font-bold text-blue-600"><?= strlen($data['catatan']) ?></div>
                            <div class="text-sm text-gray-600">Karakter</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50/50 border border-purple-100 rounded-xl">
                            <div class="text-2xl font-bold text-purple-600"><?= str_word_count($data['catatan']) ?></div>
                            <div class="text-sm text-gray-600">Kata</div>
                        </div>
                        <div class="text-center p-4 bg-green-50/50 border border-green-100 rounded-xl">
                            <div class="text-2xl font-bold text-green-600">
                                <?= ceil(str_word_count($data['catatan']) / 200) ?>
                            </div>
                            <div class="text-sm text-gray-600">Menit baca</div>
                        </div>
                        <div class="text-center p-4 bg-pink-50/50 border border-pink-100 rounded-xl">
                            <div class="text-2xl font-bold text-pink-600">
                                <?= substr_count($data['catatan'], "\n") + 1 ?>
                            </div>
                            <div class="text-sm text-gray-600">Baris</div>
                        </div>
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

/* Custom scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

/* Prose styling for better readability */
.prose {
    color: #374151;
}

.prose pre {
    font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    line-height: 1.7;
    white-space: pre-wrap;
    word-wrap: break-word;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .md\:ml-64 {
        margin-left: 0 !important;
    }
    .md\:w-\[calc\(100\%-16rem\)\] {
        width: 100% !important;
    }
    
    .max-h-\[600px\] {
        max-height: 400px;
    }
}

@media (max-width: 640px) {
    .p-8 {
        padding: 1.5rem !important;
    }
    
    .text-2xl {
        font-size: 1.5rem;
    }
    
    .text-lg {
        font-size: 1.125rem;
    }
    
    .grid-cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }
    
    pre {
        font-size: 0.875rem;
        line-height: 1.5;
    }
}

/* Mobile optimizations */
@media (max-width: 480px) {
    .grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .gap-4 {
        gap: 1rem;
    }
    
    .p-6 {
        padding: 1rem;
    }
    
    .ml-13 {
        margin-left: 3.25rem;
    }
}

/* Print styles */
@media print {
    .bg-gradient-to-br,
    .shadow-xl,
    .shadow-sm {
        background: white !important;
        box-shadow: none !important;
    }
    
    .border {
        border: 1px solid #e5e7eb !important;
    }
    
    .sticky {
        position: static !important;
    }
}
</style>

<!-- JavaScript for additional interactivity -->
