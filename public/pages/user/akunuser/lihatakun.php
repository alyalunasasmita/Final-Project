<?php
session_start();
require_once __DIR__ . "/../../../config.php";
require_once ROOT_PATH . '/backend/userAcc.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';
use App\AuthMiddleware;
use App\User;

$userAuth = AuthMiddleware::authUser();
$userModel = new User();
$userData = $userModel->getById($userAuth['id']);

require_once PUBLIC_PATH . '/partials/header.php';
require_once PUBLIC_PATH . '/partials/sidebar.php';
?>

<!-- WRAPPER UTAMA - Konsisten dengan lihatCatatan.php -->
<div class="min-h-screen bg-[#F9FAFB]">
    
    <!-- Desktop: setelah sidebar w-38 (152px) -->
    <div class="md:ml-[152px] lg:ml-[152px] xl:ml-[152px] transition-all duration-300">
        
        <!-- Full width container -->
        <div class="w-full">
            
            <!-- HEADER SECTION - Konsisten tapi dengan styling pixel -->
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
                                        Profil Saya
                                    </h1>
                                    <p class="text-gray-500 text-xs sm:text-sm mt-0.5">
                                        Kelola informasi akun dan pengaturan
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:mt-3 flex flex-wrap items-center gap-2">
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-[#2563EB]/10 border border-[#2563EB]/20 rounded-lg">
                                    <div class="w-1.5 h-1.5 bg-[#2563EB] rounded-full mr-1.5"></div>
                                    <span class="text-xs font-medium text-[#2563EB]">
                                        ID: <?= $userAuth['id'] ?>
                                    </span>
                                </div>
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-[#10B981]/10 border border-[#10B981]/20 rounded-lg">
                                    <svg class="w-3 h-3 text-[#10B981] mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs font-medium text-[#10B981]">
                                        Member sejak: <?= date('d M Y', strtotime($userData['create_time'] ?? 'now')) ?>
                                    </span>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="w-full p-3 sm:p-4 md:p-6">
                <div class="w-full max-w-full mx-auto">
                    
                    <!-- Profile Container -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                        
                        <!-- Left Column - Profile Card -->
                        <div class="lg:col-span-1">
                            <!-- Profile Card -->
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                                
                                <!-- Profile Header -->
                                <div class="p-4 sm:p-6 text-center border-b border-gray-200">
                                    <!-- Avatar Container -->
                                    <div class="relative inline-block mb-4">
                                        <!-- Pixel Avatar Frame -->
                                        <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-r from-[#93C5FD] to-[#2563EB] rounded-full flex items-center justify-center mx-auto">
                                            <div class="w-20 h-20 sm:w-28 sm:h-28 bg-white rounded-full flex items-center justify-center">
                                                <svg class="w-10 h-10 sm:w-16 sm:h-16 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- User Name -->
                                    <h2 class="text-lg sm:text-xl font-bold text-[#1F2937] mb-1"><?= htmlspecialchars($userData['nama'] ?? 'User Name') ?></h2>
                                    
                                    <!-- Username -->
                                    <p class="text-gray-600 mb-4 text-sm sm:text-base">@<?= htmlspecialchars($userData['username'] ?? 'username') ?></p>
                                    
                                    <!-- Role Badge -->
                                    <div class="inline-block px-3 sm:px-4 py-1 bg-[#2563EB]/10 border border-[#2563EB]/20 rounded-lg">
                                        <span class="text-xs sm:text-sm font-medium text-[#2563EB]">STUDENT</span>
                                    </div>
                                </div>
                                
                                <!-- Profile Stats -->
                                <div class="p-4 sm:p-6">
                                    <h3 class="text-base sm:text-lg font-semibold text-[#1F2937] mb-3 sm:mb-4">Statistik Profil</h3>
                                    
                                    <div class="space-y-3">
                                        <!-- Joined Date -->
                                        <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-[#93C5FD]/20 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#2563EB]" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Bergabung</p>
                                                    <p class="font-medium text-gray-800 text-sm sm:text-base">
                                                        <?= date('d M Y', strtotime($userData['create_time'] ?? 'now')) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="mt-4 sm:mt-6 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                                <div class="p-4 sm:p-6">
                                    <h3 class="text-base sm:text-lg font-semibold text-[#1F2937] mb-3 sm:mb-4">Aksi Cepat</h3>
                                    
                                    <div class="space-y-3">
                                        <a href="editakun.php" 
                                           class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 group">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-[#2563EB]/10 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </div>
                                                <span class="font-medium text-gray-700 text-sm sm:text-base">Edit Profil</span>
                                            </div>
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 group-hover:text-[#2563EB] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Profile Details -->
                        <div class="lg:col-span-2">
                            <!-- Personal Information Card -->
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-4 sm:mb-6">
                                <div class="p-4 sm:p-6 md:p-8">
                                    <h2 class="text-lg sm:text-xl font-bold text-[#1F2937] mb-4 sm:mb-6">Informasi Pribadi</h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                                        <!-- Full Name -->
                                        <div class="p-3 sm:p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <label class="text-xs sm:text-sm text-gray-500">Nama Lengkap</label>
                                            </div>
                                            <p class="font-medium text-gray-800 text-sm sm:text-base pl-6 break-words"><?= htmlspecialchars($userData['nama'] ?? 'N/A') ?></p>
                                        </div>
                                        
                                        <!-- Username -->
                                        <div class="p-3 sm:p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                                </svg>
                                                <label class="text-xs sm:text-sm text-gray-500">Username</label>
                                            </div>
                                            <p class="font-medium text-gray-800 text-sm sm:text-base pl-6 break-words">@<?= htmlspecialchars($userData['username'] ?? 'N/A') ?></p>
                                        </div>
                                        
                                        <!-- Email -->
                                        <div class="p-3 sm:p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                <label class="text-xs sm:text-sm text-gray-500">Email Address</label>
                                            </div>
                                            <p class="font-medium text-gray-800 text-sm sm:text-base pl-6 break-words">
                                                <?= htmlspecialchars($userData['email'] ?? 'N/A') ?>
                                            </p>
                                        </div>
                                        
                                        <!-- Account Type -->
                                        <div class="p-3 sm:p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                                </svg>
                                                <label class="text-xs sm:text-sm text-gray-500">Tipe Akun</label>
                                            </div>
                                            <div class="flex items-center gap-2 pl-6">
                                                <p class="font-medium text-gray-800 text-sm sm:text-base">Student</p>
                                                <div class="w-2 h-2 bg-[#10B981] rounded-full"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Member Since -->
                                        <div class="md:col-span-2 p-3 sm:p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <label class="text-xs sm:text-sm text-gray-500">Member Sejak</label>
                                            </div>
                                            <div class="pl-6">
                                                <p class="font-medium text-gray-800 text-sm sm:text-base">
                                                    <?= date('d F Y', strtotime($userData['create_time'] ?? 'now')) ?>
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <?= date('H:i', strtotime($userData['create_time'] ?? 'now')) ?> WIB
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Account Security Card -->
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-4 sm:mb-6">
                                <div class="p-4 sm:p-6 md:p-8">
                                    <h2 class="text-lg sm:text-xl font-bold text-[#1F2937] mb-4 sm:mb-6">Keamanan Akun</h2>
                                    
                                    <div class="space-y-4">
                                        <!-- Password Security -->
                                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 hover:border-gray-300 transition-all duration-200">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#2563EB]/10 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-semibold text-gray-800 text-sm sm:text-base">Password</h3>
                                                        <p class="text-xs sm:text-sm text-gray-500">Terakhir diubah: Belum pernah</p>
                                                    </div>
                                                </div>
                                                <a href="gantipass.php" 
                                                   class="inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 bg-[#2563EB] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                                    Ganti Password
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Account Actions Card -->
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                                <div class="p-4 sm:p-6 md:p-8">
                                    <h2 class="text-lg sm:text-xl font-bold text-[#1F2937] mb-4 sm:mb-6">Aksi Akun</h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Delete Account -->
                                        <button onclick="confirmDelete()" 
                                                class="p-4 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all duration-200 text-left">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="font-semibold text-gray-800 text-sm sm:text-base">Hapus Akun</h3>
                                                    <p class="text-xs text-gray-500">Hapus akun secara permanen</p>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 sm:p-8 border border-gray-200 rounded-xl shadow-lg max-w-md mx-4">
        <div class="flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-[#10B981]/10 rounded-full mx-auto mb-4">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-[#10B981]" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <h3 class="text-lg sm:text-xl font-bold text-[#1F2937] text-center mb-2">SUKSES!</h3>
        <p class="text-gray-600 text-center mb-6 text-sm sm:text-base">Profil berhasil diperbarui</p>
        <button onclick="closeModal()" 
                class="w-full px-4 py-2.5 sm:py-3 bg-[#2563EB] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base">
            OKE
        </button>
    </div>
</div>

<!-- Custom Styles - Konsisten -->
<style>
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
    .text-sm {
        font-size: 0.8125rem;
    }
    
    .text-xs {
        font-size: 0.75rem;
    }
    
    .p-4 {
        padding: 1rem;
    }
}

/* Untuk layar sangat kecil */
@media (max-width: 359px) {
    .text-xs {
        font-size: 0.7rem;
    }
    
    .p-3 {
        padding: 0.75rem;
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

// Delete confirmation
function confirmDelete() {
    if(confirm("Yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan!")) {
        window.location.href = 'hapusakun.php';
    }
}

function closeModal() {
    const modal = document.getElementById('successModal');
    modal.classList.add('hidden');
}

// Show success message if URL has success parameter
if(window.location.search.includes('success=true')) {
    setTimeout(() => {
        const modal = document.getElementById('successModal');
        modal.classList.remove('hidden');
    }, 500);
}
</script>