<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/Materi.php';
require_once ROOT_PATH . '/backend/progresMateri.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';


use App\AuthMiddleware;
$userData = AuthMiddleware::authUser();
$nama = htmlspecialchars($userData['nama']);


use App\Materi\Materi;
use App\progres\ProgressMateri;

// INISIALISASI OBJECT
$materiObj = new Materi();
$progressObj = new ProgressMateri();

// AMBIL SEMUA DATA MATERI
$result = $materiObj->lihatMateri();
$materi_list = $result['data'] ?? [];
$total_materi = $result['total'] ?? 0;

require_once PUBLIC_PATH . '/partials/header.php';
require_once PUBLIC_PATH . '/partials/sidebar.php';
?>

<!-- Main container dengan margin untuk sidebar w-38 -->
<div class="min-h-screen bg-gradient-to-br from-[#F9FAFB] to-gray-50">
    
    <!-- Desktop: setelah sidebar w-38 (152px) -->
    <div class="md:ml-[152px] lg:ml-[152px] xl:ml-[152px] transition-all duration-300">
        
        <!-- Full width container -->
        <div class="w-full">
            
            <!-- Header Section -->
           <div class="bg-gradient-to-br from-[#F9FAFB] to-gray-50 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <div class="max-w-full">
        <div class="text-center space-y-3">
            <h1 class="text-2xl sm:text-3xl font-bold text-[#1F2937]">
                Daftar Materi Pembelajaran
            </h1>
            <div class="text-gray-600 text-base sm:text-lg">
                Selamat belajar, 
                <span class="text-[#2563EB] font-semibold ml-1"><?= $nama ?></span>
            </div>
        </div>
    </div>
</div>

            <!-- Main Content Area -->
            <main class="px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6">
                <div class="max-w-full">
                    
                    <!-- Grid Materi -->
                    <?php if (empty($materi_list)): ?>
                        <!-- Empty State -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 text-center">
                            <div class="py-8 sm:py-12">
                                <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-6 bg-gradient-to-r from-[#93C5FD]/20 to-[#2563EB]/20 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl sm:text-2xl font-bold text-[#1F2937] mb-3 sm:mb-4">Belum Ada Materi</h3>
                                <p class="text-gray-500 mb-6 max-w-md mx-auto text-sm sm:text-base">
                                    Saat ini belum ada materi yang tersedia. Silakan hubungi administrator untuk informasi lebih lanjut.
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            <?php foreach ($materi_list as $index => $materi): 
                                $materi_nama = htmlspecialchars($materi['nama_materi'] ?? 'Untitled Course');
                                $materi_deskripsi = htmlspecialchars($materi['deskripsi_materi'] ?? 'No description available');
                                $materi_id = $materi['id_materi'] ?? 0;
                                $materi_kategori = htmlspecialchars($materi['kategori'] ?? 'General');
                                
                                // Generate warna berbeda untuk tiap card menggunakan warna custom
                                $colors = [
                                    'from-[#2563EB]/10 to-[#2563EB]/20',
                                    'from-[#10B981]/10 to-[#10B981]/20', 
                                    'from-[#93C5FD]/10 to-[#93C5FD]/20',
                                    'from-[#8B5CF6]/10 to-[#8B5CF6]/20',
                                    'from-[#F59E0B]/10 to-[#F59E0B]/20',
                                    'from-[#EF4444]/10 to-[#EF4444]/20'
                                ];
                                $color_class = $colors[$index % count($colors)];
                            ?>
                                <div class="animate-fade-in" style="animation-delay: <?= $index * 50 ?>ms">
                                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl border border-gray-100 transition-all duration-300 h-full flex flex-col overflow-hidden">
                                        <!-- Card Header dengan Gradien -->
                                        <div class="p-4 sm:p-5 bg-gradient-to-r <?= $color_class ?>">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <div class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm mr-2 sm:mr-3 shadow-sm">
                                                            <span class="text-base sm:text-lg font-bold text-[#1F2937]">
                                                                <?= strtoupper(substr($materi_nama, 0, 1)) ?>
                                                            </span>
                                                        </div>
                                                        <h3 class="text-base sm:text-lg font-semibold text-[#1F2937] line-clamp-2 flex-1">
                                                            <?= $materi_nama ?>
                                                        </h3>
                                                    </div>
                                                    <?php if ($materi_kategori && $materi_kategori !== 'General'): ?>
                                                        <div class="ml-10 sm:ml-13">
                                                            <span class="px-2 sm:px-3 py-1 bg-white/70 text-gray-700 text-xs rounded-full border border-white/50 backdrop-blur-sm">
                                                                <?= $materi_kategori ?>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Card Body -->
                                        <div class="p-4 sm:p-5 flex-grow">
                                            <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3">
                                                <?= $materi_deskripsi ?>
                                            </p>
                                            
                                            <div class="flex items-center justify-between mt-4 sm:mt-6">
                                                <div class="flex items-center text-xs sm:text-sm text-gray-500">
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Materi Pembelajaran
                                                </div>
                                                <div class="text-xs text-gray-400 hidden xs:block">
                                                    ID: <?= $materi_id ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Card Footer -->
                                        <div class="p-4 sm:p-5 pt-0">
                                            <a href="materi_detail.php?id=<?= $materi_id ?>" 
                                               class="block w-full px-3 sm:px-4 py-2 sm:py-2.5 bg-gradient-to-r from-[#2563EB] to-blue-600 text-white font-medium rounded-lg hover:shadow-md transition-all duration-200 text-center flex items-center justify-center group">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 transition-transform group-hover:translate-x-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-sm sm:text-base">Lihat Detail</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- Back to Top Button -->
<button id="backToTop" 
        class="fixed bottom-6 right-4 sm:bottom-8 sm:right-8 
               w-10 h-10 sm:w-12 sm:h-12 
               bg-gradient-to-r from-[#2563EB] to-blue-600 
               text-white rounded-full shadow-lg hover:shadow-xl 
               transition-all duration-300 
               hidden items-center justify-center z-50 group
               focus:outline-none focus:ring-2 focus:ring-[#2563EB] focus:ring-offset-2">
    <svg class="w-4 h-4 sm:w-5 sm:h-5 transform group-hover:-translate-y-0.5 transition-transform" 
         fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
    </svg>
</button>

<!-- Custom Styles -->
<style>
    * {
        font-family: 'Inter', sans-serif;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(-10px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Responsive breakpoints */
    @media (max-width: 639px) {
        .xs\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    
    @media (max-width: 480px) {
        .xs\:grid-cols-2 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Back to Top Button
        const backToTop = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTop.classList.remove('hidden');
                backToTop.classList.add('flex');
            } else {
                backToTop.classList.remove('flex');
                backToTop.classList.add('hidden');
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Animate cards on scroll
        const fadeCards = document.querySelectorAll('.animate-fade-in');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    entry.target.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                }
            });
        }, {
            threshold: 0.05,
            rootMargin: '0px 0px -50px 0px'
        });
        
        fadeCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            observer.observe(card);
        });
        
        // Responsive card layout adjustment
        function adjustCardLayout() {
            const cards = document.querySelectorAll('.animate-fade-in > div');
            const screenWidth = window.innerWidth;
            
            cards.forEach(card => {
                if (screenWidth < 640) {
                    // Mobile: lebih padat
                    card.classList.add('min-h-[280px]');
                    card.classList.remove('min-h-[320px]');
                } else {
                    // Desktop: lebih tinggi
                    card.classList.remove('min-h-[280px]');
                    card.classList.add('min-h-[320px]');
                }
            });
        }
        
        // Initial adjustment
        adjustCardLayout();
        
        // Adjust on resize
        window.addEventListener('resize', adjustCardLayout);
    });
</script>
</body>
</html>