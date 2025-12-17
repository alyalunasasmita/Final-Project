<?php
// listMateri.php

require_once __DIR__ . '/../../../../backend/AuthMiddleware.php';
use App\AuthMiddleware;
$userData = AuthMiddleware::authUser();
$nama = htmlspecialchars($userData['nama']);

// INCLUDE BACKEND CLASSES
require_once __DIR__ . '/../../../../backend/Materi.php';
require_once __DIR__ . '/../../../../backend/progresMateri.php';

use App\Materi\Materi;
use App\progres\ProgressMateri;

// INISIALISASI OBJECT
$materiObj = new Materi();
$progressObj = new ProgressMateri();

// AMBIL SEMUA DATA MATERI
$result = $materiObj->lihatMateri();
$materi_list = $result['data'] ?? [];
$total_materi = $result['total'] ?? 0;
?>

<?php
require_once __DIR__ . '/../../../assets/layout/header.php';
require_once __DIR__ . '/../../../assets/layout/sidebar.php';
?>
<div class="min-h-screen md:pl-64">

<style>
    * {
        font-family: 'Inter', sans-serif;
    }
    .gradient-bg {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
</head>
<body class="gradient-bg min-h-screen">
<div class="container mx-auto px-4 py-8 max-w-7xl">
    
    <!-- Header -->
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                    Daftar Materi Pembelajaran
                </h1>
                <p class="text-gray-600">
                    Pilih materi yang ingin Anda pelajari, <span class="font-semibold text-blue-600"><?= $nama ?></span>
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center space-x-4">
                    <div class="px-4 py-2 bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></div>
                            <span class="text-sm text-gray-700">
                                <span class="font-bold text-emerald-600"><?= $total_materi ?></span> Materi Tersedia
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Materi -->
    <?php if (empty($materi_list)): ?>
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
            <div class="py-12">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 mb-4">Belum Ada Materi</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                    Saat ini belum ada materi yang tersedia. Silakan hubungi administrator untuk informasi lebih lanjut.
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($materi_list as $index => $materi): 
                $materi_nama = htmlspecialchars($materi['nama_materi'] ?? 'Untitled Course');
                $materi_deskripsi = htmlspecialchars($materi['deskripsi_materi'] ?? 'No description available');
                $materi_id = $materi['id_materi'] ?? 0;
                $materi_kategori = htmlspecialchars($materi['kategori'] ?? 'General');
                
                // Generate warna berbeda untuk tiap card
                $colors = [
                    'from-blue-50 to-blue-100',
                    'from-emerald-50 to-emerald-100', 
                    'from-violet-50 to-violet-100',
                    'from-amber-50 to-amber-100',
                    'from-rose-50 to-rose-100',
                    'from-cyan-50 to-cyan-100'
                ];
                $color_class = $colors[$index % count($colors)];
            ?>
                <div class="animate-fade-in" style="animation-delay: <?= $index * 50 ?>ms">
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl overflow-hidden border border-gray-200 card-hover transition-all duration-300 h-full flex flex-col">
                        <!-- Card Header dengan Gradien -->
                        <div class="p-5 bg-gradient-to-r <?= $color_class ?>">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-sm mr-3 shadow-sm">
                                            <span class="text-lg font-bold text-gray-700">
                                                <?= strtoupper(substr($materi_nama, 0, 1)) ?>
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800 line-clamp-2 flex-1">
                                            <?= $materi_nama ?>
                                        </h3>
                                    </div>
                                    <?php if ($materi_kategori && $materi_kategori !== 'General'): ?>
                                        <div class="ml-13">
                                            <span class="px-3 py-1 bg-white/70 text-gray-700 text-xs rounded-full border border-white/50 backdrop-blur-sm">
                                                <?= $materi_kategori ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="p-5 flex-grow">
                            <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3">
                                <?= $materi_deskripsi ?>
                            </p>
                            
                            <div class="flex items-center justify-between mt-6">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Materi Pembelajaran
                                </div>
                                <div class="text-xs text-gray-400">
                                    ID: <?= $materi_id ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="p-5 pt-0">
                            <a href="materi_detail.php?id=<?= $materi_id ?>" 
                               class="block w-full px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:shadow-md transition-all duration-200 text-center flex items-center justify-center group">
                                <svg class="w-4 h-4 mr-2 transition-transform group-hover:translate-x-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                Lihat Detail Materi
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="mt-12 pt-8 border-t border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <p class="text-gray-600 text-sm">
                    Total <?= $total_materi ?> materi pembelajaran tersedia
                </p>
            </div>
            <div class="text-sm text-gray-500">
                Sistem Pembelajaran • © <?= date('Y') ?>
            </div>
        </div>
    </div>

</div>

<!-- Back to Top Button -->
<button id="backToTop" class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hidden items-center justify-center z-50 group">
    <svg class="w-5 h-5 transform group-hover:-translate-y-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
    </svg>
</button>

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
                }
            });
        }, {
            threshold: 0.05,
            rootMargin: '0px 0px -50px 0px'
        });
        
        fadeCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(card);
        });
    });
</script>
</body>
</html>