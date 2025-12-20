<?php
// detail_catatan.php
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/catatanUser.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

use App\AuthMiddleware;
AuthMiddleware::authUser();

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
require_once PUBLIC_PATH . '/partials/header.php';
require_once PUBLIC_PATH . '/partials/sidebar.php';
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
                                        Detail Catatan
                                    </h1>
                                </div>
                            </div>
                            <div class="mt-2 sm:mt-3 flex flex-wrap items-center gap-2">
                                <?php if ($data['created_at']): ?>
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-[#10B981]/10 border border-[#10B981]/20 rounded-lg">
                                    <svg class="w-3 h-3 text-[#10B981] mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs font-medium text-[#10B981]">
                                        Dibuat: <?= date('d M Y', strtotime($data['created_at'])) ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Right Column - Button di kanan -->
                        <div class="w-full md:col-span-1 md:flex md:justify-end">
                            <a href="lihatCatatan.php"
                               class="group w-full md:w-auto inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200 whitespace-nowrap">
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
                <div class="w-full max-w-full mx-auto">
                    
                    <!-- CARD DETAIL UTAMA -->
                    <div class="w-full bg-white border border-gray-200 rounded-xl overflow-hidden mb-4 sm:mb-6">
                        
                        <!-- Card Header dengan judul lengkap -->
                        <div class="w-full bg-gradient-to-r from-[#93C5FD]/10 to-[#2563EB]/5 border-b border-gray-200 p-3 sm:p-4 md:p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-[#1F2937] mb-1 break-words">
                                        <?= htmlspecialchars($data['judul'] ?? 'Tanpa Judul') ?>
                                    </h2>
                                    <p class="text-gray-500 text-xs sm:text-sm">
                                        Dibaca <?= date('H:i') ?> • <?= number_format(strlen($data['catatan']), 0, ',', '.') ?> karakter
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-[#93C5FD]/10 text-[#2563EB] text-xs font-medium rounded-full">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Tersimpan
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Isi Catatan -->
                        <div class="w-full p-3 sm:p-4 md:p-6">
                            <!-- Content Display Area -->
                            <div class="w-full bg-gray-50 border border-gray-200 rounded-lg overflow-hidden mb-3">
                                <div class="p-3 sm:p-4 md:p-5 max-h-[400px] overflow-y-auto">
                                    <pre class="whitespace-pre-wrap font-mono text-sm sm:text-base leading-relaxed text-gray-800">
<?= htmlspecialchars($data['catatan']) ?>
                                    </pre>
                                </div>
                            </div>
                            
                            
                        <!-- Card Footer - Action Buttons -->
                        <div class="w-full bg-gray-50 border-t border-gray-200 p-3 sm:p-4">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                <div class="flex items-center text-gray-600 text-xs sm:text-sm">
                                    <svg class="w-3.5 h-3.5 text-[#10B981] mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Catatan tersimpan dengan aman</span>
                                </div>
                                
                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <a href="hapuscatatan.php?id=<?= $id ?>" 
                                       onclick="return confirm('Yakin ingin menghapus catatan ini?')"
                                       class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-red-50 text-red-600 font-medium rounded-lg hover:bg-red-600 hover:text-white transition-colors duration-200 text-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>Hapus Catatan</span>
                                    </a>

                                    <a href="editCatatan.php?id=<?= $id ?>" 
                                       class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-yellow-50 text-yellow-600 font-medium rounded-lg hover:bg-yellow-600 hover:text-white transition-colors duration-200 text-sm">
                                        <span>Edit Catatan</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles - Konsisten dengan lihatCatatan.php -->
<style>
/* Line clamp utilities */
.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
}

.break-words {
    word-break: break-word;
    overflow-wrap: break-word;
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
    
    .max-h-\[400px\] {
        max-height: 300px;
    }
}

/* Custom breakpoint untuk extra small */
@media (min-width: 480px) and (max-width: 639px) {
    .xs\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .xs\:grid-cols-4 {
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

/* Font untuk pre tag */
pre {
    font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Fira Code', monospace;
    line-height: 1.5;
    tab-size: 4;
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
    
    .py-2 {
        padding-top: 0.375rem;
        padding-bottom: 0.375rem;
    }
    
    .w-3\.5 {
        width: 0.875rem;
    }
    
    .h-3\.5 {
        height: 0.875rem;
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
</script>