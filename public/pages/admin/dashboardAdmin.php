<?php
Session_start();
require_once __DIR__ . '/../../config.php';


require_once ROOT_PATH . '/backend/service/dashboardService.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

require_once PUBLIC_PATH. '/partials/header.php';
require_once PUBLIC_PATH . '/partials/sbAdmin.php';

// frontend/admin/dashboardAdmin.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Gunakan AuthMiddleware

use App\AuthMiddleware;
AuthMiddleware::authAdmin();

require_once PUBLIC_PATH . '/pages/admin/cardAdmin.php';
?>
<!-- Main Content Area -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-slate-100 lg:ml-64 transition-all duration-300">

    <!-- Header Section -->
    <div class="sticky top-0 z-20 bg-white/80 backdrop-blur-sm border-b border-gray-200/60 px-4 py-6 lg:px-8">
        <div class="max-w-full mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-cyan-100 to-blue-100 border border-cyan-200 shadow-sm">
                            <span class="material-icons text-lg lg:text-xl text-cyan-600">
                                dashboard
                            </span>
                        </div>
                        <div>
                            <h1 class="text-xl lg:text-3xl font-bold bg-gradient-to-r from-cyan-700 to-blue-700 bg-clip-text text-transparent">
                                Admin Dashboard
                            </h1>
                            <p class="text-xs lg:text-sm text-gray-600 mt-1 font-light">
                                Welcome back, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>!
                            </p>
                        </div>
                    </div>
                </div>
                <div class="inline-flex items-center px-3 py-2 lg:px-4 lg:py-2 bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-xl shadow-sm">
                    <span class="material-icons text-gray-500 text-xs lg:text-sm mr-2">
                        calendar_today
                    </span>
                    <span class="text-xs lg:text-sm font-medium text-gray-700">
                        <?= date('F j, Y') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-3 py-4 lg:px-8 lg:py-6">
        <div class="max-w-full mx-auto">
            
            <!-- Stats Grid - 4 Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Total Materi -->
                <div class="group bg-gradient-to-br from-white to-gray-50/50 border border-gray-200/60 rounded-2xl p-4 lg:p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="p-2 lg:p-3 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-xl border border-cyan-200">
                            <span class="material-icons text-lg lg:text-xl text-cyan-600">
                                school
                            </span>
                        </div>
                    </div>
                    <h3 class="text-xs lg:text-sm font-medium text-gray-500 mb-1">
                    <?= htmlspecialchars($data['materi']['title']) ?>
                    </h3>

                    <div class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">
                    <?= number_format((int)$data['materi']['value']) ?>
                    </div>

                    <div class="flex items-center text-xs lg:text-sm text-gray-500">
                    <span class="material-icons text-sm mr-1">trending_up</span>
                    <span><?= htmlspecialchars($data['materi']['meta']) ?></span>
                    </div>
                </div>

                <!-- Total Users Card -->
                <div class="group bg-gradient-to-br from-white to-gray-50/50 border border-gray-200/60 rounded-2xl p-4 lg:p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="p-2 lg:p-3 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl border border-green-200">
                            <span class="material-icons text-lg lg:text-xl text-green-600">
                                people
                            </span>
                        </div>
                    </div>
                    <h3 class="text-xs lg:text-sm font-medium text-gray-500 mb-1">
                    <?= htmlspecialchars($data['users']['title']) ?>
                    </h3>

                    <div class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">
                    <?= number_format((int)$data['users']['value']) ?>
                    </div>

                    <?php if (!empty($data['users']['meta'])): ?>
                    <div class="flex items-center text-xs lg:text-sm text-gray-500">
                        <span class="material-icons text-sm mr-1">person_add</span>
                        <span><?= htmlspecialchars($data['users']['meta']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Active Sessions Card -->
                <div class="group bg-gradient-to-br from-white to-gray-50/50 border border-gray-200/60 rounded-2xl p-4 lg:p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="p-2 lg:p-3 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl border border-purple-200">
                            <span class="material-icons text-lg lg:text-xl text-purple-600">
                                play_circle
                            </span>
                        </div>
                    </div>
                    <h3 class="text-xs lg:text-sm font-medium text-gray-500 mb-1">
                    <?= htmlspecialchars($data['sessions']['title']) ?>
                    </h3>

                    <div class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">
                    <?= number_format((int)$data['sessions']['value']) ?>
                    </div>

                    <div class="flex items-center text-xs lg:text-sm text-gray-500">
                    <span class="material-icons text-sm mr-1">schedule</span>
                    <span><?= htmlspecialchars($data['sessions']['meta']) ?></span>
                    </div>
                </div>

                <!-- Completion Rate Card -->
                <div class="group bg-gradient-to-br from-white to-gray-50/50 border border-gray-200/60 rounded-2xl p-4 lg:p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="p-2 lg:p-3 bg-gradient-to-br from-orange-100 to-amber-100 rounded-xl border border-orange-200">
                            <span class="material-icons text-lg lg:text-xl text-orange-600">
                                check_circle
                            </span>
                        </div>
                    </div>
                    <h3 class="text-xs lg:text-sm font-medium text-gray-500 mb-1">
                    <?= htmlspecialchars($data['completion']['title']) ?>
                    </h3>

                    <div class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">
                    <?= htmlspecialchars($data['completion']['value']) ?>
                    </div>

                    <?php if (!empty($data['completion']['meta'])): ?>
                    <div class="flex items-center text-xs lg:text-sm text-gray-500">
                        <span class="material-icons text-sm mr-1">show_chart</span>
                        <span><?= htmlspecialchars($data['completion']['meta']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Charts Section - 2 Cards Vertically Stacked -->
            <div class="grid grid-cols-1 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Aktivitas Chart Card -->
                <div id="cardAktivitas" 
                     class="bg-white border border-slate-200 rounded-2xl shadow-lg p-4 lg:p-6
                            transition-all duration-500 ease-out" style="transition-delay: 100ms;">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6 gap-3">
                        <div>
                            <h3 class="text-sm lg:text-base font-semibold text-slate-700">
                                Aktivitas Sistem (14 hari)
                            </h3>
                            <p class="text-xs lg:text-sm text-gray-500 mt-1">Daily user interactions and system activities</p>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg">
                            <span class="material-icons text-sm mr-1">update</span>
                            <span>Updated today</span>
                        </div>
                    </div>
                    <div class="h-56 sm:h-64 lg:h-72 w-full">
                        <canvas id="chartAktivitas"></canvas>
                    </div>
                </div>

                <!-- Top Materi Chart Card -->
                <div id="cardSesiStatus"
                     class="bg-white border border-slate-200 rounded-2xl shadow-lg p-4 lg:p-6
                            transition-all duration-500 ease-out" style="transition-delay: 200ms;">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6 gap-3">
                        <div>
                            <h3 class="text-sm lg:text-base font-semibold text-slate-700">
                                Top Materi (Total Durasi)
                            </h3>
                            <p class="text-xs lg:text-sm text-gray-500 mt-1">Most engaging learning materials by duration</p>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg">
                            <span class="material-icons text-sm mr-1">trending_up</span>
                            <span>This month</span>
                        </div>
                    </div>
                    <div class="h-56 sm:h-64 lg:h-72 w-full">
                        <canvas id="chartsesiStatus"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Courses & Quick Actions Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8">
                <!-- Recent Courses -->
                <div>
                    <div class="bg-gradient-to-br from-white to-gray-50/50 rounded-2xl shadow-lg border border-gray-200/60 p-4 lg:p-6">
                        <div class="flex items-center justify-between mb-4 lg:mb-6">
                            <div>
                                <h2 class="text-lg lg:text-xl font-semibold text-gray-800">Recent Courses</h2>
                                <p class="text-xs lg:text-sm text-gray-500 mt-1">Latest learning materials</p>
                            </div>
                            <a href="lihatMateri.php" class="text-xs lg:text-sm font-medium text-cyan-600 hover:text-cyan-700 flex items-center">
                                View All <span class="material-icons text-sm ml-1">arrow_forward</span>
                            </a>
                        </div>
                        
                        <div class="space-y-3 lg:space-y-4">
                            <?php if (!empty($data['recent_courses'])): ?>
                                <?php foreach ($data['recent_courses'] as $course): ?>
                                    <div class="group flex items-center gap-3 lg:gap-4 p-3 lg:p-4 bg-gradient-to-r from-white to-gray-50/80 border border-gray-200/60 rounded-xl hover:border-cyan-200 hover:shadow-md transition-all duration-300">
                                        <div class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-cyan-100 to-blue-100">
                                            <span class="material-icons text-cyan-600">book</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm lg:text-base font-medium text-gray-800 truncate"><?= htmlspecialchars($course['nama_materi']) ?></h4>
                                            <p class="text-xs lg:text-sm text-gray-500 mt-1 truncate">
                                                <?= htmlspecialchars(substr($course['deskripsi_materi'] ?? 'No description', 0, 50)) ?>...
                                            </p>
                                        </div>
                                        <a href="editMateri.php?id=<?= $course['id_materi'] ?>" 
                                           class="p-1 lg:p-2 text-gray-400 hover:text-cyan-600 rounded-lg hover:bg-cyan-50 transition-colors">
                                            <span class="material-icons text-sm">edit</span>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-6 lg:py-8">
                                    <div class="w-12 h-12 lg:w-16 lg:h-16 mx-auto mb-3 lg:mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                        <span class="material-icons text-xl lg:text-2xl text-gray-400">add_circle</span>
                                    </div>
                                    <p class="text-sm lg:text-base text-gray-500">No courses added yet</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div>
                    <div class="bg-gradient-to-br from-white to-gray-50/50 rounded-2xl shadow-lg border border-gray-200/60 p-4 lg:p-6">
                        <div class="flex items-center justify-between mb-4 lg:mb-6">
                            <div>
                                <h2 class="text-lg lg:text-xl font-semibold text-gray-800">Aksi Cepat</h2>
                                <p class="text-xs lg:text-sm text-gray-500 mt-1">Manage your content</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 lg:gap-4 mb-6 lg:mb-8">
                            <a href="tambahMateri.php" 
                               class="group flex flex-col items-center justify-center p-4 lg:p-6 bg-gradient-to-br from-cyan-50 to-blue-50 hover:from-cyan-100 hover:to-blue-100 border border-cyan-200 hover:border-cyan-300 rounded-xl shadow-xs hover:shadow-sm transition-all duration-200">
                                <span class="material-icons text-cyan-600 text-xl lg:text-2xl mb-2 lg:mb-3">add_circle</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700 text-center">Tambah Materi</span>
                            </a>
                            
                            <a href="lihatMateri.php" 
                               class="group flex flex-col items-center justify-center p-4 lg:p-6 bg-gradient-to-br from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 border border-purple-200 hover:border-purple-300 rounded-xl shadow-xs hover:shadow-sm transition-all duration-200">
                                <span class="material-icons text-purple-600 text-xl lg:text-2xl mb-2 lg:mb-3">manage_search</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700 text-center">Kelola Materi</span>
                            </a>
                            
                            <a href="materiArsip.php" 
                               class="group flex flex-col items-center justify-center p-4 lg:p-6 bg-gradient-to-br from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 border border-green-200 hover:border-green-300 rounded-xl shadow-xs hover:shadow-sm transition-all duration-200">
                                <span class="material-icons text-green-600 text-xl lg:text-2xl mb-2 lg:mb-3">archive</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700 text-center">Arsip Materi</span>
                            </a>
                            
                            <a href="#" 
                               class="group flex flex-col items-center justify-center p-4 lg:p-6 bg-gradient-to-br from-orange-50 to-amber-50 hover:from-orange-100 hover:to-amber-100 border border-orange-200 hover:border-orange-300 rounded-xl shadow-xs hover:shadow-sm transition-all duration-200">
                                <span class="material-icons text-orange-600 text-xl lg:text-2xl mb-2 lg:mb-3">analytics</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700 text-center">Analytics</span>
                            </a>
                        </div>
                        
                        <?php if (!empty($topCourses)): ?>
                        <div class="mt-4 lg:mt-6 pt-4 lg:pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3 lg:mb-4">Top Courses by Modules</h3>
                            <div class="space-y-2 lg:space-y-3">
                                <?php foreach (array_slice($topCourses, 0, 3) as $index => $course): ?>
                                    <div class="flex items-center gap-2 lg:gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                                        <div class="w-7 h-7 lg:w-8 lg:h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-gray-100 to-gray-200">
                                            <span class="text-xs font-bold text-gray-700">#<?= $index + 1 ?></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs lg:text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($course['course_name']) ?></div>
                                        </div>
                                        <span class="text-xs px-2 py-1 bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 rounded-full whitespace-nowrap">
                                            <?= $course['module_count'] ?> mod
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/chart/chartAdmin.js"></script>
<script>
// Simple animations for dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Animate stat cards on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, { threshold: 0.1 });
    
    // Observe all stat cards and chart cards
    document.querySelectorAll('.group.bg-gradient-to-br, #cardAktivitas, #cardSesiStatus').forEach(card => {
        observer.observe(card);
    });
});
</script>

<style>
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

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .reveral-on-scroll {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
}
</style>
</body>
</html>