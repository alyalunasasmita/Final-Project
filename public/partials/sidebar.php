<aside id="sidebar"
       class="fixed top-0 left-0 
              w-38 h-screen 
              bg-gradient-to-b from-[#F9FAFB] to-gray-50
              border-r border-gray-200
              flex flex-col justify-between
              z-50 transition-all duration-300 ease-in-out
              overflow-y-auto
              hidden md:flex">
    <!-- TOP AREA -->
    <div class="flex-1">
        <!-- Logo Area -->
        <div class="px-3 py-5 flex flex-col items-center">
            <!-- Logo -->
            <div class="w-12 h-12 
                        bg-gradient-to-br from-[#2563EB] to-[#10B981] 
                        rounded-lg 
                        flex items-center justify-center
                        transition-all duration-300
                        hover:scale-105">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
            </div>
            
            <!-- Text Title -->
            <h1 class="mt-2 text-lg font-bold text-[#1F2937]">
                Study<span class="text-[#2563EB]">You</span>
            </h1>
            
            <!-- Status dots -->
            <div class="mt-1 flex gap-1">
                <div class="w-1.5 h-1.5 bg-[#10B981] rounded-full"></div>
                <div class="w-1.5 h-1.5 bg-[#2563EB] rounded-full"></div>
                <div class="w-1.5 h-1.5 bg-[#93C5FD] rounded-full"></div>
            </div>
        </div>

        <!-- Navigation Menu - Always visible on desktop -->
        <nav class="mt-2 space-y-0.5 px-2">
            <!-- Dashboard -->
            <a href="<?php BASE_URL?>/pages/user/dashboardUser.php" 
               class="flex items-center gap-2 
                      p-2.5 rounded-md
                      text-[#1F2937] hover:text-[#2563EB]
                      hover:bg-gradient-to-r hover:from-gray-50 hover:to-white
                      hover:shadow-xs
                      transition-all duration-200
                      group/nav relative">
                <span class="flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-500 group-hover/nav:text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </span>
                <span class="text-sm font-medium">
                    Dashboard
                </span>
            </a>
            
            <!-- Schedule (Active) -->
            <a href="<?php BASE_URL?> /pages/user/schedule/listJadwal.php" 
               class="flex items-center gap-2 
                      p-2.5 rounded-md
                      text-[#1F2937] hover:text-[#2563EB]
                      hover:bg-gradient-to-r hover:from-gray-50 hover:to-white
                      hover:shadow-xs
                      transition-all duration-200
                      group/nav relative">
                <span class="flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-500 group-hover/nav:text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </span>
                <span class="text-sm font-semibold">
                    Jadwal
                </span>
            </a>
            
            <!-- Course -->
            <a href="<?php BASE_URL ?> /pages/user/materi/listMateri.php" 
               class="flex items-center gap-2 
                      p-2.5 rounded-md
                      text-[#1F2937] hover:text-[#2563EB]
                      hover:bg-gradient-to-r hover:from-gray-50 hover:to-white
                      hover:shadow-xs
                      transition-all duration-200
                      group/nav relative">
                <span class="flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-500 group-hover/nav:text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </span>
                <span class="text-sm font-medium">
                    Materi
                </span>
            </a>

            
            <!-- Notes -->
            <a href=" <?php BASE_URL ?>/pages/user/catatan/lihatCatatan.php" 
               class="flex items-center gap-2 
                      p-2.5 rounded-md
                      text-[#1F2937] hover:text-[#10B981]
                      hover:bg-gradient-to-r hover:from-green-50 hover:to-white
                      hover:shadow-xs
                      transition-all duration-200
                      group/nav relative">
                <span class="flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-500 group-hover/nav:text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </span>
                <span class="text-sm font-medium">
                    Catatan
                </span>
            </a>

            <!-- Tugas -->
            <a href=" <?php BASE_URL ?>/pages/user/daftarTugas/lihatTugas.php"
               class="flex items-center gap-2 
                      p-2.5 rounded-md
                      text-[#1F2937] hover:text-[#10B981]
                      hover:bg-gradient-to-r hover:from-green-50 hover:to-white
                      hover:shadow-xs
                      transition-all duration-200
                      group/nav relative">
                <span class="flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-500 group-hover/nav:text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </span>
                <span class="text-sm font-medium">
                    Tugas
                </span>
            </a>
            
            <!-- Account -->
            <a href="<?php BASE_URL ?>/pages/user/akunuser/lihatakun.php" 
               class="flex items-center gap-2 
                      p-2.5 rounded-md
                      text-[#1F2937] hover:text-[#2563EB]
                      hover:bg-gradient-to-r hover:from-blue-50 hover:to-white
                      hover:shadow-xs
                      transition-all duration-200
                      group/nav relative">
                <span class="flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-500 group-hover/nav:text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                <span class="text-sm font-medium">
                    Akun
                </span>
            </a>
        </nav>
    </div>

    <!-- BOTTOM AREA -->
    <div class="p-3 border-t border-gray-200">            
        <!-- Logout Button -->
        <a href="<?php BASE_URL?> /pages/auth/logout.php"
           class="flex items-center justify-center md:justify-start gap-2 
                  px-3 py-2 
                  rounded-md
                  bg-gradient-to-r from-red-50 to-pink-50
                  border border-red-200
                  hover:from-red-100 hover:to-pink-100
                  hover:border-red-300
                  text-red-600 hover:text-red-700
                  font-medium text-xs
                  transition-all duration-200
                  group/logout">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <span class="text-xs">
                Logout
            </span>
        </a>
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
    <svg class="w-4 h-4 text-[#1F2937]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>

<!-- Mobile Overlay -->
<div id="mobileSidebarOverlay" 
     class="fixed inset-0 bg-black/20 z-30 
            opacity-0 invisible
            transition-all duration-300 ease-in-out
            md:hidden"></div>

<!-- Mobile Sidebar - Compact Version -->
<aside id="mobileSidebar"
       class="fixed top-0 left-0 
              w-56 h-screen 
              bg-white
              border-r border-gray-200
              flex flex-col justify-between
              z-40
              transform -translate-x-full
              transition-transform duration-300 ease-in-out
              md:hidden shadow-lg">
    
    <!-- Mobile Header -->
    <div>
        <div class="px-3 py-4 flex items-center gap-3 border-b border-gray-200">
            <div class="w-9 h-9 bg-gradient-to-br from-[#2563EB] to-[#10B981] rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-base font-bold text-[#1F2937]">Study<span class="text-[#2563EB]">You</span></h1>
            </div>
            <button id="closeMobileSidebar" class="ml-auto p-1.5 rounded-md hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <nav class="mt-2 space-y-0.5 px-2">
            <a href="<?php BASE_URL ?>/pages/user/dashboardUser.php" 
               class="flex items-center gap-2 p-2.5 rounded-md text-[#1F2937] hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            
            <a href="<?php BASE_URL ?>/pages/user/schedule/listJadwal.php" 
               class="flex items-center gap-2 p-2.5 rounded-md text-[#1F2937] hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-sm font-medium">Jadwal</span>
            </a>
            
            <a href="<?php BASE_URL ?>/pages/user/materi/listMateri.php" 
               class="flex items-center gap-2 p-2.5 rounded-md text-[#1F2937] hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span class="text-sm font-medium">Materi</span>
            </a>
            
            <a href="<?php BASE_URL?>/pages/user/catatan/lihatCatatan.php" 
               class="flex items-center gap-2 p-2.5 rounded-md text-[#1F2937] hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span class="text-sm font-medium">Catatan</span>
            </a>

            <a href="<?php BASE_URL?>/pages/user/daftarTugas/lihatTugas.php" 
               class="flex items-center gap-2 p-2.5 rounded-md text-[#1F2937] hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="text-sm font-medium">Tugas</span>
            </a>

            <a href="<?php BASE_URL ?>/pages/user/akunuser/lihatakun.php" 
               class="flex items-center gap-2 p-2.5 rounded-md text-[#1F2937] hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-sm font-medium">Akun</span>
            </a>
        </nav>
    </div>

    <!-- Mobile Bottom -->
    <div class="p-3 border-t border-gray-200">
        <a href="<?php BASE_URL?> /pages/auth/logout.php" 
           class="flex items-center justify-center gap-2 p-2.5 rounded-md bg-red-50 text-red-600 hover:bg-red-100 transition-colors font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Logout
        </a>
    </div>
</aside>

<!-- JavaScript -->
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
    @keyframes pulse {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
    }
    
    .animate-pulse {
        animation: pulse 1.5s ease-in-out infinite;
    }
    
    /* Scrollbar styling */
    #sidebar {
        scrollbar-width: thin;
        scrollbar-color: #CBD5E1 #F1F5F9;
    }
    
    #sidebar::-webkit-scrollbar {
        width: 4px;
    }
    
    #sidebar::-webkit-scrollbar-track {
        background: #F1F5F9;
    }
    
    #sidebar::-webkit-scrollbar-thumb {
        background-color: #CBD5E1;
        border-radius: 4px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 640px) {
        #mobileSidebar {
            width: 240px;
        }
    }
    
    @media (max-width: 480px) {
        #mobileSidebar {
            width: 440px;
        }
        
        #mobileSidebarToggle {
            top: 10px;
            left: 10px;
            width: 20px;
            height: 20px;
        }
    }
</style>