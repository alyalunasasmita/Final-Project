<?php
// frontend/admin/lihatMateri.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Gunakan AuthMiddleware
require_once __DIR__ . "/../../../backend/AuthMiddleware.php";
use App\AuthMiddleware;

// Middleware akan redirect otomatis jika bukan admin
AuthMiddleware::authAdmin();

// Sekarang include file lainnya
require_once __DIR__ . "/../../../backend/materi.php";
use App\Materi\Materi;

$tambah_materi = new Materi(); 
$result = $tambah_materi->lihatMateri();

require_once __DIR__ . '/../../assets/layout/header.php';
require_once __DIR__ . '/../../assets/layout/sbAdmin.php';
?>

<!-- WRAPPER UTAMA -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-slate-100 lg:ml-64 transition-all duration-300">

    <!-- HEADER SECTION -->
    <div class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200/60 px-4 py-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex w-10 h-10 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-100 to-blue-100 border border-cyan-200 shadow-sm">
                            <span class="material-icons text-base text-cyan-600">
                                menu_book
                            </span>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-cyan-700 to-blue-700 bg-clip-text text-transparent">
                                Kelola Materi Pembelajaran
                            </h1>
                            <p class="text-sm text-gray-600 mt-1 font-light">
                                Buat dan kelola materi pembelajaran 
                            </p>
                        </div>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="flex flex-wrap gap-2">
                        <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-white to-gray-50 rounded-xl border border-gray-200 shadow-xs">
                            <div class="w-3 h-3 rounded-full bg-gradient-to-r from-cyan-400 to-blue-400 mr-2"></div>
                            <span class="text-sm font-medium text-gray-700">
                                <?php 
                                if (isset($result['success']) && $result['success'] === true) {
                                    echo $result['total'] ?? 0;
                                } else {
                                    echo 0;
                                }
                                ?> Materi
                            </span>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Add Course Button -->
                <div class="flex-shrink-0">
                    <a href="tambahMateri.php"
                       class="group inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0">
                        <span class="material-icons text-lg">
                            add
                        </span>
                        <span class="text-sm sm:text-base">Tambah Materi Baru</span>
                        <div class="ml-2 w-6 h-6 flex items-center justify-center bg-white/20 rounded-lg group-hover:bg-white/30 transition-colors">
                            <span class="material-icons text-sm">
                                north_east
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="px-4 py-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <?php if (!isset($result['success']) || $result['success'] !== true || empty($result['data'])): ?>
            <!-- Empty State -->
            <div class="min-h-[60vh] flex flex-col items-center justify-center text-center px-4 py-12">
                <div class="relative mb-8">
                    <div class="w-40 h-40 bg-gradient-to-br from-cyan-50 to-blue-50 rounded-full flex items-center justify-center shadow-lg">
                        <div class="w-32 h-32 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-6xl text-cyan-400/80">
                                auto_stories
                            </span>
                        </div>
                    </div>
                    <div class="absolute -top-2 -right-2 w-16 h-16 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center shadow-lg">
                        <span class="material-icons text-2xl text-purple-400">
                            add_circle
                        </span>
                    </div>
                </div>
                
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-3">
                    <?php 
                    if (!isset($result['success'])) {
                        echo "Unable to Load Data";
                    } elseif ($result['success'] === false) {
                        echo $result['message'] ?? "Failed to Load Courses";
                    } else {
                        echo "No Courses Yet";
                    }
                    ?>
                </h2>
                
                <p class="text-gray-600 max-w-md mx-auto mb-8 leading-relaxed">
                    Start building your educational content by adding the first course. 
                    Your students are waiting to learn!
                </p>
                
                <a href="tambahMateri.php"
                   class="group inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <span class="material-icons text-xl">
                        add_circle
                    </span>
                    <span class="text-base">Create First Course</span>
                    <span class="material-icons text-lg group-hover:translate-x-1 transition-transform">
                        arrow_forward
                    </span>
                </a>
                
                <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-3xl w-full">
                    <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
                        <div class="w-10 h-10 flex items-center justify-center bg-cyan-50 rounded-lg mb-3">
                            <span class="material-icons text-cyan-500">
                                create
                            </span>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-1">Create Content</h4>
                        <p class="text-sm text-gray-600">Start by adding your course materials</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
                        <div class="w-10 h-10 flex items-center justify-center bg-blue-50 rounded-lg mb-3">
                            <span class="material-icons text-blue-500">
                                category
                            </span>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-1">Organize</h4>
                        <p class="text-sm text-gray-600">Structure your learning modules</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
                        <div class="w-10 h-10 flex items-center justify-center bg-purple-50 rounded-lg mb-3">
                            <span class="material-icons text-purple-500">
                                share
                            </span>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-1">Share</h4>
                        <p class="text-sm text-gray-600">Publish for your students</p>
                    </div>
                </div>
            </div>
            <?php else: ?>
            
            <?php 
            $materi = $result['data'];
            ?>

            <!-- Courses Grid -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($materi as $m): 
                    if (!is_array($m)) {
                        continue;
                    }
                    
                    $id_materi = $m['id_materi'] ?? 0;
                    $nama_materi = $m['nama_materi'] ?? 'Untitled Course';
                    $deskripsi = $m['deskripsi_materi'] ?? '';
                    $excerpt = mb_strimwidth(strip_tags($deskripsi), 0, 120, "...");
                    
                    // Generate random gradient for card
                    $gradients = [
                        'from-cyan-50 to-blue-50 border-cyan-200',
                        'from-blue-50 to-indigo-50 border-blue-200',
                        'from-purple-50 to-pink-50 border-purple-200',
                        'from-emerald-50 to-teal-50 border-emerald-200',
                        'from-amber-50 to-orange-50 border-amber-200',
                        'from-violet-50 to-purple-50 border-violet-200'
                    ];
                    $randomGradient = $gradients[array_rand($gradients)];
                ?>
                
                <!-- Course Card -->
                <div class="group relative bg-gradient-to-br from-white to-gray-50/50 border border-gray-200/80 rounded-2xl p-5 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <!-- Decorative corner -->
                    <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-cyan-500/5 to-blue-500/5 rounded-bl-full"></div>
                    
                    <!-- Card Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1 pr-2">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br <?= explode(' ', $randomGradient)[0] ?> <?= explode(' ', $randomGradient)[1] ?> border <?= explode(' ', $randomGradient)[2] ?> shadow-xs">
                                    <span class="material-icons text-sm text-cyan-600">
                                        book
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 line-clamp-1 group-hover:text-cyan-700 transition-colors">
                                    <?= htmlspecialchars($nama_materi) ?>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- Course Description -->
                    <div class="mb-6">
                        <div class="bg-white/60 border border-gray-100 rounded-xl p-4 min-h-[100px]">
                            <p class="text-sm text-gray-700 leading-relaxed line-clamp-3">
                                <?= htmlspecialchars($excerpt) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 pt-5 border-t border-gray-100">
                        <div class="grid grid-cols-3 gap-2">
                            <!-- Edit Button -->
                            <a href="editMateri.php?id=<?= $id_materi ?>"
                               class="group/edit flex flex-col items-center justify-center p-3 bg-gradient-to-br from-cyan-50 to-white hover:from-cyan-100 hover:to-white border border-cyan-200 hover:border-cyan-300 rounded-xl shadow-xs hover:shadow-md transition-all duration-200">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-cyan-100 to-cyan-50 mb-1.5">
                                    <span class="material-icons text-sm text-cyan-600">
                                        edit
                                    </span>
                                </div>
                                <span class="text-xs font-medium text-gray-700 group-hover/edit:text-cyan-700">Edit</span>
                            </a>
                            
                            <!-- Delete Button -->
                            <a href="hapusMateri.php?id=<?= $id_materi ?>"
                               class="group/delete flex flex-col items-center justify-center p-3 bg-gradient-to-br from-red-50 to-white hover:from-red-100 hover:to-white border border-red-200 hover:border-red-300 rounded-xl shadow-xs hover:shadow-md transition-all duration-200"
                               onclick="return confirm('Are you sure you want to delete this course? This action cannot be undone.')">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-red-100 to-red-50 mb-1.5">
                                    <span class="material-icons text-sm text-red-600">
                                        delete
                                    </span>
                                </div>
                                <span class="text-xs font-medium text-gray-700 group-hover/delete:text-red-700">Delete</span>
                            </a>
                            
                            <!-- Submateri Button -->
                            <a href="lihatSubmateri.php?id=<?= $id_materi ?>"
                               class="group/sub flex flex-col items-center justify-center p-3 bg-gradient-to-br from-purple-50 to-white hover:from-purple-100 hover:to-white border border-purple-200 hover:border-purple-300 rounded-xl shadow-xs hover:shadow-md transition-all duration-200">
                                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-purple-100 to-purple-50 mb-1.5">
                                    <span class="material-icons text-sm text-purple-600">
                                        list
                                    </span>
                                </div>
                                <span class="text-xs font-medium text-gray-700 group-hover/sub:text-purple-700">Content</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Hover Effect Border -->
                    <div class="absolute inset-0 border-2 border-transparent group-hover:border-cyan-200/50 rounded-2xl pointer-events-none transition-all duration-300"></div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Stats Footer -->
        
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
}

.line-clamp-3 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
}

/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #cbd5e1, #94a3b8);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #94a3b8, #64748b);
}

/* Glass effect */
.backdrop-blur-md {
    backdrop-filter: blur(12px);
}

/* Smooth transitions */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Card hover effect enhancement */
.group:hover .group-hover\:border-cyan-200\/50 {
    border-color: rgba(165, 243, 252, 0.3);
}

/* Material icons sizing */
.material-icons {
    font-size: inherit;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .lg\:ml-64 {
        margin-left: 0;
    }
    
    .sticky {
        position: static;
    }
}

@media (max-width: 640px) {
    .text-2xl {
        font-size: 1.5rem;
    }
    
    .text-3xl {
        font-size: 1.75rem;
    }
    
    .grid-cols-4 {
        grid-template-columns: repeat(1, 1fr);
    }
}

/* Animation for cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.grid > div {
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
}

.grid > div:nth-child(1) { animation-delay: 0.1s; }
.grid > div:nth-child(2) { animation-delay: 0.2s; }
.grid > div:nth-child(3) { animation-delay: 0.3s; }
.grid > div:nth-child(4) { animation-delay: 0.4s; }
.grid > div:nth-child(5) { animation-delay: 0.5s; }
.grid > div:nth-child(6) { animation-delay: 0.6s; }
.grid > div:nth-child(7) { animation-delay: 0.7s; }
.grid > div:nth-child(8) { animation-delay: 0.8s; }
.grid > div:nth-child(9) { animation-delay: 0.9s; }
.grid > div:nth-child(10) { animation-delay: 1s; }

/* Loading state animation */
@keyframes shimmer {
    0% {
        background-position: -200px 0;
    }
    100% {
        background-position: calc(200px + 100%) 0;
    }
}

.loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200px 100%;
    animation: shimmer 1.5s infinite;
}

/* Focus styles for accessibility */
a:focus, button:focus {
    outline: 2px solid #06b6d4;
    outline-offset: 2px;
}

/* Print styles */
@media print {
    .sticky, 
    [href*="editMateri"], 
    [href*="hapusMateri"], 
    [href*="lihatSubmateri"] {
        display: none !important;
    }
}
</style>

<script>
// Add interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('a, button');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.7);
                transform: scale(0);
                animation: ripple 0.6s linear;
                width: ${size}px;
                height: ${size}px;
                top: ${y}px;
                left: ${x}px;
                pointer-events: none;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close any open modals if needed
        }
    });
    
    // Lazy load images if added in future
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                    }
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px',
            threshold: 0.1
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            observer.observe(img);
        });
    }
});

// Add ripple animation
const style = document.createElement('style');
style.textContent = `
@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}
`;
document.head.appendChild(style);
</script>