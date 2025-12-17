<aside id="sidebar"
       class="fixed top-0 left-0 
              w-64 h-screen 
              bg-gradient-to-b from-gray-50 to-slate-100
              border-r border-gray-200
              flex flex-col justify-between
              z-50 shadow-xl
              hidden md:flex
              backdrop-blur-sm bg-white/80">
        
        <!-- TOP AREA -->
        <div class="overflow-y-auto custom-scrollbar">
            <div class="px-4 py-6 flex flex-col items-center">
                <!-- Admin Logo Box -->
                <div class="w-16 h-16 bg-gradient-to-br from-cyan-100 to-blue-100 border-2 border-blue-200 shadow-md mb-3 relative rounded-2xl
                           hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <!-- Modern corner accents -->
                    <div class="absolute -top-1 -left-1 w-3 h-3 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-full shadow-sm"></div>
                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-full shadow-sm"></div>
                    <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-full shadow-sm"></div>
                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-full shadow-sm"></div>
                    
                    <!-- Center content -->
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-icons text-2xl bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">
                            admin_panel_settings
                        </span>
                    </div>
                </div>
                
                <!-- Admin Text Title -->
                <h1 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent font-sans tracking-tight">
                    Admin<span class="font-light text-gray-600">Panel</span>
                </h1>
                
                <!-- Modern status dots -->
                <div class="mt-3 flex gap-1.5">
                    <div class="w-2 h-2 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-full animate-pulse"></div>
                    <div class="w-2 h-2 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full"></div>
                    <div class="w-2 h-2 bg-gradient-to-r from-indigo-400 to-purple-400 rounded-full"></div>
                </div>
                
                <p class="text-xs text-gray-500 mt-2 font-sans tracking-wide font-light">Dashboard Control</p>
            </div>

            <!-- Navigation Menu Admin -->
            <nav class="mt-4 space-y-1.5 px-3">
                <!-- Dashboard Admin -->
                <a href="/frontend/admin/dashboardAdmin.php" 
                   class="flex items-center gap-3 p-3 rounded-xl
                          bg-white hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-50
                          border border-gray-100 hover:border-blue-200
                          shadow-sm hover:shadow-md
                          transition-all duration-200
                          group hover:-translate-y-0.5">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-100 to-cyan-100 group-hover:from-blue-200 group-hover:to-cyan-200">
                        <span class="material-icons text-sm text-blue-600">
                            dashboard
                        </span>
                    </div>
                    <span class="font-medium text-gray-700 font-sans text-sm tracking-wide group-hover:text-blue-700">Dashboard</span>
                    <span class="ml-auto text-xs bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 px-2 py-1 rounded-full border border-blue-200">Admin</span>
                </a>
                
                <!-- Manage Course/Materi -->
                <a href="/frontend/admin/lihatMateri.php" 
                   class="flex items-center gap-3 p-3 rounded-xl
                          bg-white hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50
                          border border-gray-100 hover:border-purple-200
                          shadow-sm hover:shadow-md
                          transition-all duration-200
                          group hover:-translate-y-0.5">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-purple-100 to-pink-100 group-hover:from-purple-200 group-hover:to-pink-200">
                        <span class="material-icons text-sm text-purple-600">
                            menu_book
                        </span>
                    </div>
                    <span class="font-medium text-gray-700 font-sans text-sm tracking-wide group-hover:text-purple-700">Manage Courses</span>
                    <span class="ml-auto text-xs bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 px-2 py-1 rounded-full border border-purple-200 animate-pulse">New</span>
                </a>
            </nav>
        </div>

        <!-- ADMIN LOGOUT & PROFILE -->
        <div class="p-3 border-t border-gray-200 bg-white/50">
            <!-- Admin Profile -->
            <div class="flex items-center gap-3 p-3 mb-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="relative">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full flex items-center justify-center shadow-md">
                        <span class="material-icons text-white text-sm">
                            admin_panel_settings
                        </span>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 font-sans truncate"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></p>
                    <p class="text-xs text-gray-500 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                        Online
                    </p>
                </div>
            </div>
            
            <!-- Logout Button -->
            <a href="/frontend/logout.php"
               class="w-full flex items-center justify-center gap-2 
                      px-4 py-3 
                      bg-gradient-to-r from-white to-gray-50
                      hover:from-red-50 hover:to-pink-50
                      border-2 border-gray-200 hover:border-red-300
                      text-gray-700 hover:text-red-700
                      font-semibold text-sm
                      rounded-xl
                      transition-all duration-200
                      shadow-sm hover:shadow-md
                      transform hover:-translate-y-0.5
                      mb-4 group">
                <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gradient-to-br from-red-100 to-pink-100 group-hover:from-red-200 group-hover:to-pink-200">
                    <span class="material-icons text-sm text-red-500 group-hover:text-red-600">
                        logout
                    </span>
                </div>
                <span class="font-sans tracking-wide">Logout</span>
            </a>
            
            <!-- Footer -->
            <div class="text-center pt-3 border-t border-gray-200">
                <div class="text-xs text-gray-400 font-sans">
                    <div class="flex justify-center gap-1.5 mb-2">
                        <div class="w-1.5 h-1.5 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-full"></div>
                        <div class="w-1.5 h-1.5 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full"></div>
                        <div class="w-1.5 h-1.5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full"></div>
                    </div>
                    <span class="font-light">Admin Panel v2.1</span>
                </div>
            </div>
        </div>
    </aside>

<!-- Mobile Toggle Button -->
<button id="mobileSidebarToggle" 
        class="fixed top-4 left-4 z-40 
               w-10 h-10 
               bg-gradient-to-r from-white to-gray-50
               hover:from-blue-50 hover:to-cyan-50
               border-2 border-gray-200 hover:border-blue-300
               shadow-lg
               flex items-center justify-center
               rounded-xl
               transition-all duration-300
               md:hidden
               active:scale-95">
    <span class="material-icons text-gray-600 text-lg">
        menu
    </span>
</button>

<!-- Mobile Sidebar Overlay -->
<div id="mobileSidebarOverlay" 
     class="fixed inset-0 bg-black/30 backdrop-blur-sm z-30 
            opacity-0 invisible
            transition-all duration-300
            md:hidden"></div>

<!-- Mobile Sidebar -->
<aside id="mobileSidebar"
       class="fixed top-0 left-0 
              w-72 h-screen 
              bg-gradient-to-b from-white to-gray-50
              border-r border-gray-200
              flex flex-col justify-between
              z-40 shadow-2xl
              transform -translate-x-full
              transition-transform duration-300
              md:hidden
              backdrop-blur-lg bg-white/90">
    
    <!-- Mobile Content -->
    <div class="overflow-y-auto custom-scrollbar">
        <div class="px-4 py-6 flex flex-col items-center">
            <div class="w-14 h-14 bg-gradient-to-br from-cyan-100 to-blue-100 border-2 border-blue-200 shadow-md mb-3 relative rounded-2xl
                       hover:shadow-lg transition-all duration-300">
                <div class="absolute -top-1 -left-1 w-3 h-3 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-full"></div>
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-full"></div>
                <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-full"></div>
                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-full"></div>
                <div class="w-full h-full flex items-center justify-center">
                    <span class="material-icons text-xl bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">
                        admin_panel_settings
                    </span>
                </div>
            </div>
            <h1 class="text-lg font-bold bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent font-sans">
                Admin<span class="font-light text-gray-600">Panel</span>
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-sans font-light">Mobile Control</p>
        </div>

        <nav class="mt-4 space-y-1.5 px-3">
            <a href="/frontend/admin/dashboardAdmin.php" 
               class="flex items-center gap-3 p-3 rounded-xl bg-white border border-gray-100 shadow-sm group">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-100 to-cyan-100">
                    <span class="material-icons text-sm text-blue-600">dashboard</span>
                </div>
                <span class="font-medium text-gray-700 font-sans text-sm">Dashboard</span>
            </a>
            <a href="/frontend/admin/materi/listMateri.php" 
               class="flex items-center gap-3 p-3 rounded-xl bg-white border border-gray-100 shadow-sm group">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-purple-100 to-pink-100">
                    <span class="material-icons text-sm text-purple-600">menu_book</span>
                </div>
                <span class="font-medium text-gray-700 font-sans text-sm">Courses</span>
            </a>
        </nav>
    </div>

    <!-- Mobile Bottom Section -->
    <div class="p-3 border-t border-gray-200 bg-white/70">
        <!-- Mobile Profile -->
        <div class="flex items-center gap-3 p-3 mb-3 bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="relative">
                <div class="w-9 h-9 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full flex items-center justify-center">
                    <span class="material-icons text-white text-xs">person</span>
                </div>
                <div class="absolute -bottom-1 -right-1 w-2.5 h-2.5 bg-green-400 rounded-full border-2 border-white"></div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-800 font-sans truncate"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></p>
                <p class="text-xs text-gray-500">● Online</p>
            </div>
        </div>
        
        <!-- Mobile Logout Button -->
        <a href="/frontend/logout.php" 
           class="w-full flex items-center justify-center gap-2 
                  px-4 py-3 
                  bg-gradient-to-r from-white to-gray-50
                  border-2 border-gray-200
                  text-gray-700
                  font-semibold text-xs 
                  rounded-xl
                  shadow-sm
                  mb-3 group">
            <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gradient-to-br from-red-100 to-pink-100">
                <span class="material-icons text-sm text-red-500">logout</span>
            </div>
            <span class="font-sans">Logout</span>
        </a>
        
        <div class="text-center pt-3 border-t border-gray-200">
            <div class="text-xs text-gray-400 font-sans font-light">Admin v2.1</div>
        </div>
    </div>
</aside>

<script>
// Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobileSidebarToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileOverlay = document.getElementById('mobileSidebarOverlay');
    
    if (mobileToggle && mobileSidebar && mobileOverlay) {
        mobileToggle.addEventListener('click', function() {
            mobileSidebar.classList.toggle('-translate-x-full');
            mobileOverlay.classList.toggle('opacity-0');
            mobileOverlay.classList.toggle('invisible');
            mobileOverlay.classList.toggle('opacity-100');
            mobileOverlay.classList.toggle('visible');
        });
        
        mobileOverlay.addEventListener('click', function() {
            mobileSidebar.classList.add('-translate-x-full');
            mobileOverlay.classList.add('opacity-0');
            mobileOverlay.classList.add('invisible');
            mobileOverlay.classList.remove('opacity-100');
            mobileOverlay.classList.remove('visible');
        });
        
        // Close sidebar when clicking links on mobile
        const mobileLinks = mobileSidebar.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileSidebar.classList.add('-translate-x-full');
                mobileOverlay.classList.add('opacity-0');
                mobileOverlay.classList.add('invisible');
                mobileOverlay.classList.remove('opacity-100');
                mobileOverlay.classList.remove('visible');
            });
        });
        
        // Close sidebar on window resize (if resized to desktop)
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                mobileSidebar.classList.add('-translate-x-full');
                mobileOverlay.classList.add('opacity-0');
                mobileOverlay.classList.add('invisible');
                mobileOverlay.classList.remove('opacity-100');
                mobileOverlay.classList.remove('visible');
            }
        });
    }
});
</script>

<style>
    /* Custom scrollbar untuk sidebar */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #e5e7eb transparent;
    }
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #cbd5e1, #94a3b8);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #94a3b8, #64748b);
    }
    
    /* Modern smooth animations */
    * {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
    
    /* Soft glow effect for active menu */
    .active-modern-menu {
        position: relative;
        background: linear-gradient(to right, rgba(219, 234, 254, 0.3), rgba(207, 250, 254, 0.3));
        border-color: #93c5fd;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }
    
    /* Gradient text animation */
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .gradient-text-animate {
        background-size: 200% auto;
        animation: gradient-shift 3s ease-in-out infinite;
    }
    
    /* Floating animation for logo */
    @keyframes gentle-float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-3px); }
    }
    
    .float-animation {
        animation: gentle-float 6s ease-in-out infinite;
    }
    
    /* Ripple effect for buttons */
    .ripple {
        position: relative;
        overflow: hidden;
    }
    
    .ripple:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .ripple:active:after {
        width: 300px;
        height: 300px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 640px) {
        #mobileSidebar {
            width: 85vw;
            max-width: 280px;
        }
        
        #mobileSidebarToggle {
            width: 44px;
            height: 44px;
            top: 12px;
            left: 12px;
        }
    }
    
    @media (max-width: 480px) {
        #mobileSidebar {
            width: 90vw;
        }
        
        #mobileSidebarToggle {
            width: 40px;
            height: 40px;
        }
        
        #mobileSidebarToggle span {
            font-size: 16px;
        }
    }
    
    /* High contrast mode support */
    @media (prefers-contrast: high) {
        #sidebar,
        #mobileSidebar {
            border-width: 2px;
            border-color: #1e40af;
        }
        
        .bg-gradient-to-br {
            border: 1px solid currentColor;
        }
    }
    
    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
        * {
            transition-duration: 0.01ms !important;
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>