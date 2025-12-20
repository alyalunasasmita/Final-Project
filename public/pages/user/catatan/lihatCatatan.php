<?php
// lihat_catatan.php
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';
require_once ROOT_PATH . '/backend/catatanUser.php';

use App\AuthMiddleware;
$userData = AuthMiddleware::authUser();
$nama = htmlspecialchars($userData['nama']);


use App\catat\Catatan;

$catat = new Catatan($_SESSION['user_id']);
$data = $catat->lihatCatatan();

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
                                        Catatan Belajar
                                    </h1>
                                    <p class="text-gray-500 text-xs sm:text-sm mt-0.5">
                                        Halo, <span class="font-medium text-[#2563EB]"><?= $nama; ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:mt-3 flex flex-wrap items-center gap-2">
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-[#2563EB]/10 border border-[#2563EB]/20 rounded-lg">
                                    <div class="w-1.5 h-1.5 bg-[#2563EB] rounded-full mr-1.5"></div>
                                    <span class="text-xs font-medium text-[#2563EB]">
                                        <?= count($data) ?> Catatan
                                    </span>
                                </div>
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-[#10B981]/10 border border-[#10B981]/20 rounded-lg">
                                    <svg class="w-3 h-3 text-[#10B981] mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs font-medium text-[#10B981]">
                                        Terakhir update: <?= date('d M') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Button di kanan -->
                        <div class="w-full md:col-span-1 md:flex md:justify-end">
                            <a href="tambahCatatan.php"
                               class="group w-full md:w-auto inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-[#2563EB] text-white font-medium rounded-lg hover:bg-[#2563EB]/90 transition-all duration-200 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Catatan Baru</span>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="w-full p-3 sm:p-4 md:p-6">
                <div class="w-full max-w-full mx-auto">
                    
                    <!-- Floating button for mobile -->
                    <div class="md:hidden fixed bottom-4 sm:bottom-6 right-4 sm:right-6 z-30">
                        <a href="tambahCatatan.php"
                           class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-[#2563EB] text-white rounded-full shadow-lg hover:bg-[#2563EB]/90 transition-colors duration-200">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </a>
                    </div>
                    
                    <!-- Empty state -->
                    <?php if (empty($data)): ?>
                    <div class="w-full min-h-[60vh] flex flex-col items-center justify-center text-center py-8 sm:py-12 px-3 sm:px-4">
                        <div class="relative mb-6">
                            <div class="w-32 h-32 sm:w-40 sm:h-40 bg-gradient-to-br from-[#93C5FD]/20 to-[#2563EB]/20 rounded-full flex items-center justify-center">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-[#93C5FD]/30 to-[#2563EB]/30 rounded-full flex items-center justify-center">
                                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="text-lg sm:text-xl font-medium text-[#1F2937] mb-2">Mulai Dokumentasi Belajarmu</h3>
                        <p class="text-gray-500 mb-6 max-w-sm text-sm sm:text-base">
                            Catat setiap momen belajar untuk melihat progres dan mengingat materi penting
                        </p>
                        
                        <a href="tambahCatatan.php"
                           class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 bg-[#2563EB] text-white font-medium rounded-lg hover:bg-[#2563EB]/90 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Buat Catatan Pertama</span>
                        </a>
                    </div>
                    <?php else: ?>

                    <!-- Grid Container -->
                    <div class="w-full mb-6">
                        <!-- Grid Header -->
                        <div class="w-full flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
                            <div>
                                <h2 class="text-base sm:text-lg font-semibold text-[#1F2937]">Semua Catatan</h2>
                                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Klik untuk melihat, edit, atau hapus catatan</p>
                            </div>
                        </div>
                        
                        <!-- NOTES GRID -->
                        <div class="w-full grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                            <?php foreach ($data as $c): 
                                $id_cat = $c['id_catatan'];
                                $judul  = $c['judul'] ?? 'Tanpa Judul';
                                $body   = $c['catatan'] ?? '';
                                $excerpt = mb_substr(strip_tags($body), 0, 120);
                                $created = $c['created_at'] ?? '';
                                
                                // Soft color palette untuk kartu menggunakan warna custom
                                $colorThemes = [
                                    'bg-gradient-to-br from-[#93C5FD]/10 to-white border-[#93C5FD]/30',
                                    'bg-gradient-to-br from-[#2563EB]/10 to-white border-[#2563EB]/30',
                                    'bg-gradient-to-br from-[#10B981]/10 to-white border-[#10B981]/30',
                                    'bg-gradient-to-br from-[#F9FAFB] to-gray-50 border-gray-200'
                                ];
                                $colorIndex = $id_cat % count($colorThemes);
                                $themeClass = $colorThemes[$colorIndex];
                                
                                // Warna aksi berdasarkan tema
                                $actionColors = [
                                    'bg-[#2563EB] hover:bg-[#2563EB]/90',
                                    'bg-[#10B981] hover:bg-[#10B981]/90',
                                    'bg-[#93C5FD] hover:bg-[#93C5FD]/90',
                                    'bg-gray-700 hover:bg-gray-800'
                                ];
                                $actionColor = $actionColors[$colorIndex];
                            ?>
                            
                            <!-- CARD -->
                            <div class="w-full group bg-white border border-gray-100 rounded-lg hover:border-gray-200 transition-all duration-200 flex flex-col h-full">
                                
                                <!-- Card Header -->
                                <div class="w-full flex items-start justify-between p-3 sm:p-4">
                                    <div class="flex-1 min-w-0">
                                        <!-- Judul -->
                                        <h3 class="w-full text-sm sm:text-base font-medium text-[#1F2937] line-clamp-1 mb-1">
                                            <?= htmlspecialchars($judul) ?>
                                        </h3>
                                        
                                        <!-- Date badge -->
                                        <?php if ($created): ?>
                                        <div class="inline-flex items-center px-2 py-0.5 bg-gray-50 rounded">
                                            <svg class="w-3 h-3 text-gray-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-xs text-gray-600">
                                                <?= date('d M Y', strtotime($created)) ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Icon Note -->
                                    <div class="ml-2 flex-shrink-0">
                                        <div class="p-1.5 bg-gray-50 rounded-lg">
                                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content Preview -->
                                <div class="w-full flex-1 px-3 sm:px-4">
                                    <div class="w-full bg-gray-50 border border-gray-100 p-3 rounded-lg h-32 overflow-hidden">
                                        <div class="w-full h-full overflow-y-auto pr-2">
                                            <p class="w-full text-gray-600 text-xs sm:text-sm leading-relaxed line-clamp-5">
                                                <?= htmlspecialchars($excerpt) ?><?= (mb_strlen(strip_tags($body)) > 120 ? '...' : '') ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="w-full p-3 sm:p-4">
                                    <div class="w-full flex items-center gap-1.5">
                                        <!-- View Button -->
                                        <a href="detailcatatan.php?id=<?= $id_cat ?>"
                                           class="flex-1 flex items-center justify-center gap-1.5 px-2 py-1.5 bg-[#2563EB]/10 text-[#2563EB] font-medium rounded hover:bg-[#2563EB] hover:text-white transition-colors duration-200 text-xs sm:text-sm text-center whitespace-nowrap">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span class="hidden xs:inline">Baca</span>
                                            <span class="xs:hidden">Lihat</span>
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <a href="editCatatan.php?id=<?= $id_cat ?>"
                                           class="flex-1 flex items-center justify-center gap-1.5 px-2 py-1.5 bg-[#10B981]/10 text-[#10B981] font-medium rounded hover:bg-[#10B981] hover:text-white transition-colors duration-200 text-xs sm:text-sm text-center whitespace-nowrap">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            <span class="hidden xs:inline">Edit</span>
                                            <span class="xs:hidden">Ubah</span>
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <a href="hapuscatatan.php?id=<?= $id_cat ?>"
                                           onclick="return confirm('Yakin ingin menghapus catatan ini?')"
                                           class="flex-1 flex items-center justify-center gap-1.5 px-2 py-1.5 bg-red-50 text-red-600 font-medium rounded hover:bg-red-600 hover:text-white transition-colors duration-200 text-xs sm:text-sm text-center whitespace-nowrap">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span class="hidden xs:inline">Hapus</span>
                                            <span class="xs:hidden">Hapus</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Pagination/Stats Footer -->
                    </div>
                    <?php endif; ?>

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
    
    .gap-1\.5 {
        gap: 0.25rem;
    }
    
    .p-3 {
        padding: 0.5rem;
    }
}

/* Custom breakpoint untuk extra small */
@media (min-width: 480px) and (max-width: 639px) {
    .xs\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Tablet */
@media (min-width: 640px) and (max-width: 767px) {
    .sm\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Desktop kecil */
@media (min-width: 768px) and (max-width: 1023px) {
    .md\:grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

/* Desktop besar */
@media (min-width: 1024px) {
    .lg\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

/* Untuk layar sangat kecil */
@media (max-width: 359px) {
    .text-xs {
        font-size: 0.7rem;
    }
    
    .gap-1\.5 {
        gap: 0.125rem;
    }
    
    .px-2 {
        padding-left: 0.375rem;
        padding-right: 0.375rem;
    }
    
    .py-1\.5 {
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
    }
    
    .w-3 {
        width: 0.75rem;
    }
    
    .h-3 {
        height: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Responsive button text adjustment
    function adjustButtonText() {
        const screenWidth = window.innerWidth;
        const viewButtons = document.querySelectorAll('a[href*="detailcatatan"] span');
        const editButtons = document.querySelectorAll('a[href*="editCatatan"] span');
        const deleteButtons = document.querySelectorAll('a[href*="hapuscatatan"] span');
        
        if (screenWidth < 480) {
            // Show short text on mobile
            viewButtons.forEach(btn => btn.textContent = 'Lihat');
            editButtons.forEach(btn => btn.textContent = 'Ubah');
            deleteButtons.forEach(btn => btn.textContent = 'Hapus');
        } else {
            // Show full text on larger screens
            viewButtons.forEach(btn => btn.textContent = 'Baca');
            editButtons.forEach(btn => btn.textContent = 'Edit');
            deleteButtons.forEach(btn => btn.textContent = 'Hapus');
        }
    }
    
    // Initial adjustment
    adjustButtonText();
    
    // Adjust on resize
    window.addEventListener('resize', adjustButtonText);
});
</script>