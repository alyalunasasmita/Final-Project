<?php
// lihat_catatan.php
require_once __DIR__ . '/../../../../backend/AuthMiddleware.php';
use App\AuthMiddleware;
$userData = AuthMiddleware::authUser();
$nama = htmlspecialchars($userData['nama']);

// INCLUDE BACKEND CLASSES
require_once __DIR__ . '/../../../../backend/catatanUser.php';
use App\catat\Catatan;

$catat = new Catatan($_SESSION['user_id']);
$data = $catat->lihatCatatan();

require_once __DIR__ . '/../../../assets/layout/header.php';
require_once __DIR__ . '/../../../assets/layout/sidebar.php';
?>

<!-- WRAPPER UTAMA -->
<main class="w-full md:ml-64 min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 transition-all duration-300">

    <!-- HEADER SECTION - Versi fixed -->
    <div class="w-full flex top-0 z-40 bg-white/80 backdrop-blur-sm border-b border-gray-200/60 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <!-- Gunakan grid untuk control yang lebih baik -->
            <div class="w-full grid grid-cols-1 md:grid-cols-2 items-start md:items-center gap-4">
                
                <!-- Left Column -->
                <div class="w-full md:col-span-1">
                    <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                        Catatan Belajar
                    </h1>
                    <p class="mt-1 text-sm sm:text-base text-gray-600">
                        Halo, <span class="font-semibold text-blue-600"><?= $nama; ?></span> ✨
                    </p>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <div class="inline-flex items-center px-3 py-1.5 bg-blue-50/80 border border-blue-100 rounded-lg">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                            <span class="text-xs sm:text-sm font-medium text-blue-700">
                                <?= count($data) ?> Catatan
                            </span>
                        </div>
                        <div class="inline-flex items-center px-3 py-1.5 bg-purple-50/80 border border-purple-100 rounded-lg">
                            <svg class="w-3.5 h-3.5 text-purple-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-xs sm:text-sm font-medium text-purple-700">
                                Terakhir update: <?= date('d M') ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Button di kanan -->
                <div class="w-full md:col-span-1 md:flex md:justify-end">
                    <a href="tambahCatatan.php"
                       class="group w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 whitespace-nowrap mt-4 md:mt-0">
                        <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Catatan Baru</span>
                    </a>
                </div>
                
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="w-full p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-full mx-auto">
            
            <!-- Floating button for mobile -->
            <div class="md:hidden fixed bottom-6 right-6 z-50">
                <a href="tambahCatatan.php"
                   class="flex items-center justify-center w-14 h-14 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-110 active:scale-95">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </a>
            </div>
            
            <!-- Empty state -->
            <?php if (empty($data)): ?>
            <div class="w-full min-h-[70vh] flex flex-col items-center justify-center text-center py-12 px-4">
                <div class="relative mb-8">
                    <div class="w-40 h-40 bg-gradient-to-br from-blue-100/50 to-purple-100/50 rounded-full flex items-center justify-center shadow-lg">
                        <div class="w-32 h-32 bg-gradient-to-br from-blue-200/60 to-purple-200/60 rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute -top-2 -right-2 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-3">Mulai Dokumentasi Belajarmu</h3>
                <p class="text-gray-600 mb-8 max-w-md text-base sm:text-lg leading-relaxed">
                    Catat setiap momen belajar untuk melihat progres dan mengingat materi penting
                </p>
                
                <a href="tambahCatatan.php"
                   class="group w-full md:w-auto inline-flex items-center justify-center gap-3 px-8 py-3.5 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Buat Catatan Pertama</span>
                </a>
                
                <div class="w-full mt-8 grid grid-cols-2 sm:grid-cols-3 gap-3 max-w-lg">
                    <div class="w-full px-4 py-2 bg-blue-50/50 border border-blue-100 rounded-lg text-center">
                        <div class="text-sm font-medium text-blue-700">Tips</div>
                        <div class="text-xs text-blue-600 mt-1">Rutin update catatan</div>
                    </div>
                    <div class="w-full px-4 py-2 bg-purple-50/50 border border-purple-100 rounded-lg text-center">
                        <div class="text-sm font-medium text-purple-700">Idea</div>
                        <div class="text-xs text-purple-600 mt-1">Gunakan tagging</div>
                    </div>
                    <div class="w-full px-4 py-2 bg-pink-50/50 border border-pink-100 rounded-lg text-center">
                        <div class="text-sm font-medium text-pink-700">Note</div>
                        <div class="text-xs text-pink-600 mt-1">Tambahkan gambar</div>
                    </div>
                </div>
            </div>
            <?php else: ?>

            <!-- Grid Container -->
            <div class="w-full mb-8">
                <!-- Grid Header -->
                <div class="w-full flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                    <div class="w-full sm:w-auto">
                        <h2 class="text-lg font-semibold text-gray-800">Semua Catatan</h2>
                        <p class="text-sm text-gray-600 mt-1">Klik untuk melihat, edit, atau hapus catatan</p>
                    </div>
                    <div class="w-full sm:w-auto text-sm text-gray-500">
                        Urutkan: <span class="font-medium text-blue-600">Terbaru</span>
                    </div>
                </div>
                
                <!-- NOTES GRID -->
                <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4 md:gap-5 lg:gap-6">
                    <?php foreach ($data as $c): 
                        $id_cat = $c['id_catatan'];
                        $judul  = $c['judul'] ?? 'Tanpa Judul';
                        $body   = $c['catatan'] ?? '';
                        $excerpt = mb_substr(strip_tags($body), 0, 120);
                        $created = $c['created_at'] ?? '';
                        
                        // Soft color palette untuk kartu
                        $colorThemes = [
                            'bg-gradient-to-br from-blue-50/80 to-blue-100/30 border-blue-200/60',
                            'bg-gradient-to-br from-purple-50/80 to-purple-100/30 border-purple-200/60',
                            'bg-gradient-to-br from-pink-50/80 to-pink-100/30 border-pink-200/60',
                            'bg-gradient-to-br from-cyan-50/80 to-cyan-100/30 border-cyan-200/60',
                            'bg-gradient-to-br from-indigo-50/80 to-indigo-100/30 border-indigo-200/60',
                            'bg-gradient-to-br from-emerald-50/80 to-emerald-100/30 border-emerald-200/60'
                        ];
                        $colorIndex = $id_cat % count($colorThemes);
                        $themeClass = $colorThemes[$colorIndex];
                        
                        // Warna aksi berdasarkan tema
                        $actionColors = [
                            'from-blue-500 to-blue-600',
                            'from-purple-500 to-purple-600',
                            'from-pink-500 to-pink-600',
                            'from-cyan-500 to-cyan-600',
                            'from-indigo-500 to-indigo-600',
                            'from-emerald-500 to-emerald-600'
                        ];
                        $actionColor = $actionColors[$colorIndex];
                    ?>
                    
                    <!-- CARD -->
                    <div class="w-full group <?= $themeClass ?> border rounded-2xl p-5 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col h-full backdrop-blur-sm">
                        
                        <!-- Card Header -->
                        <div class="w-full flex items-start justify-between mb-4">
                            <div class="flex-1 min-w-0">
                                <!-- Judul dengan gradient -->
                                <h3 class="w-full text-lg font-bold text-gray-800 line-clamp-1 group-hover:text-gray-900 mb-2">
                                    <?= htmlspecialchars($judul) ?>
                                </h3>
                                
                                <!-- Date badge -->
                                <?php if ($created): ?>
                                <div class="inline-flex items-center px-2.5 py-1 bg-white/60 border border-gray-200/60 rounded-lg">
                                    <svg class="w-3.5 h-3.5 text-gray-400 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-600">
                                        <?= date('d M Y', strtotime($created)) ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Icon Note -->
                            <div class="ml-2 flex-shrink-0">
                                <div class="p-2 bg-white/80 rounded-xl shadow-sm">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Content Preview -->
                        <div class="w-full flex-1 mb-5">
                            <div class="w-full bg-white/60 border border-gray-200/60 p-4 rounded-xl h-40 overflow-hidden shadow-inner">
                                <div class="w-full h-full overflow-y-auto pr-2 custom-scrollbar">
                                    <p class="w-full text-gray-700 text-sm leading-relaxed line-clamp-5">
                                        <?= htmlspecialchars($excerpt) ?><?= (mb_strlen(strip_tags($body)) > 120 ? '...' : '') ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <!-- Action Buttons -->
<div class="w-full mt-auto pt-4 border-t border-gray-300/30">
    <div class="w-full flex flex-row items-center gap-2">
        <!-- View Button -->
        <a href="detailcatatan.php?id=<?= $id_cat ?>"
           class="flex-1 group/view flex items-center justify-center gap-2 px-3 py-2.5 bg-gradient-to-r <?= $actionColor ?> text-white font-medium rounded-lg shadow hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5 text-center whitespace-nowrap min-h-[44px]">
            <svg class="w-4 h-4 transition-transform group-hover/view:scale-110 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            <span class="text-sm hidden xs:inline">Baca</span>
            <span class="text-xs xs:hidden">Lihat</span>
        </a>
        
        <!-- Edit Button -->
        <a href="editCatatan.php?id=<?= $id_cat ?>"
           class="flex-1 group/edit flex items-center justify-center gap-2 px-3 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium rounded-lg shadow hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5 text-center whitespace-nowrap min-h-[44px]">
            <svg class="w-4 h-4 transition-transform group-hover/edit:rotate-12 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <span class="text-sm hidden xs:inline">Edit</span>
            <span class="text-xs xs:hidden">Ubah</span>
        </a>
        
        <!-- Delete Button -->
        <a href="hapuscatatan.php?id=<?= $id_cat ?>"
           onclick="return confirm('Yakin ingin menghapus catatan ini?')"
           class="flex-1 group/delete flex items-center justify-center gap-2 px-3 py-2.5 bg-gradient-to-r from-rose-500 to-rose-600 text-white font-medium rounded-lg shadow hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5 text-center whitespace-nowrap min-h-[44px]">
            <svg class="w-4 h-4 transition-transform group-hover/delete:scale-110 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <span class="text-sm hidden xs:inline">Hapus</span>
            <span class="text-xs xs:hidden">Hapus</span>
        </a>
    </div>
</div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination/Stats Footer -->
                <div class="w-full mt-8 pt-6 border-t border-gray-200/60">
                    <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="w-full sm:w-auto text-sm text-gray-600">
                            Menampilkan <span class="font-semibold text-blue-600"><?= count($data) ?></span> catatan
                        </div>
                        <div class="w-full sm:w-auto flex items-center justify-center sm:justify-end gap-2">
                            <button class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100/80 hover:bg-gray-200/80 rounded-lg transition-colors">
                                ← Sebelumnya
                            </button>
                            <button class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100/80 hover:bg-gray-200/80 rounded-lg transition-colors">
                                Selanjutnya →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>

<!-- Custom Styles -->
<style>
/* Custom scrollbar untuk preview konten */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

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

.line-clamp-3 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
}

.line-clamp-4 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 4;
}

.line-clamp-5 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 5;
}

/* Animasi halus */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.grid > div {
    animation: fadeIn 0.3s ease-out forwards;
}

/* Responsive grid adjustments */
@media (max-width: 640px) {
    .grid > div:nth-child(n) {
        animation-delay: calc(var(--item-index) * 0.05s);
    }
    
    /* Mobile-specific full width */
    main {
        width: 100vw !important;
        max-width: 100vw !important;
        margin-left: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    /* Ensure content fits mobile screen */
    .w-full {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* Padding adjustments for mobile */
    .p-4, .p-6, .p-8 {
        padding: 1rem !important;
    }
    
    /* Stack buttons on mobile */
    .xs\\:flex-row {
        flex-direction: column !important;
    }
}

/* Small devices (phones, less than 480px) */
@media (max-width: 479px) {
    .xs\\:grid-cols-2 {
        grid-template-columns: 1fr !important;
    }
}

/* Tablet devices (640px to 768px) */
@media (min-width: 640px) and (max-width: 768px) {
    .sm\\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}

/* Medium devices (768px to 1024px) */
@media (min-width: 768px) and (max-width: 1024px) {
    .md\\:grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    }
    
    /* Adjust main content margin for sidebar */
    main {
        margin-left: 16rem !important;
        width: calc(100% - 16rem) !important;
    }
}

/* Large devices (1024px to 1280px) */
@media (min-width: 1024px) and (max-width: 1280px) {
    .lg\\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    }
    
    main {
        margin-left: 16rem !important;
        width: calc(100% - 16rem) !important;
    }
}

/* Extra large devices (1280px and up) */
@media (min-width: 1280px) {
    .xl\\:grid-cols-5 {
        grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
    }
    
    main {
        margin-left: 16rem !important;
        width: calc(100% - 16rem) !important;
    }
}

/* Print styles */
@media print {
    .md\\:hidden,
    .fixed {
        display: none !important;
    }
}

/* Custom breakpoint for extra small screens (custom xs) */
@media (min-width: 480px) and (max-width: 639px) {
    .xs\\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}

/* Responsive button text */
@media (max-width: 479px) {
    .text-xs {
        font-size: 0.7rem !important;
    }
    
    .min-h-\[44px\] {
        min-height: 40px !important;
    }
    
    .gap-2 {
        gap: 0.25rem !important;
    }
    
    .px-3 {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
}

/* Untuk layar sangat kecil (iPhone SE dll) */
@media (max-width: 374px) {
    .text-xs {
        display: none !important;
    }
    
    .gap-2 {
        gap: 0.125rem !important;
    }
    
    .px-3 {
        padding-left: 0.375rem !important;
        padding-right: 0.375rem !important;
    }
    
    /* Buat icon lebih kecil di layar sangat kecil */
    .w-4 {
        width: 0.875rem !important;
    }
    
    .h-4 {
        height: 0.875rem !important;
    }
}
</style>

<!-- Tambahkan script untuk animasi item grid -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridItems = document.querySelectorAll('.grid > div');
    gridItems.forEach((item, index) => {
        item.style.setProperty('--item-index', index);
        item.style.animationDelay = `${index * 0.05}s`;
    });
    
    // Handle responsive behavior
    function handleResponsive() {
        const isMobile = window.innerWidth < 768;
        const mainElement = document.querySelector('main');
        
        if (isMobile) {
            // Remove sidebar margin on mobile
            mainElement.style.marginLeft = '0';
            mainElement.style.width = '100vw';
        } else {
            // Add sidebar margin on desktop
            mainElement.style.marginLeft = '16rem';
            mainElement.style.width = 'calc(100% - 16rem)';
        }
    }
    
    // Initial call
    handleResponsive();
    
    // Listen for window resize
    window.addEventListener('resize', handleResponsive);
});
</script>