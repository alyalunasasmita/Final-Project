<?php
require_once __DIR__ . "/../../../config.php";
require_once ROOT_PATH . '/backend/userAcc.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

use App\AuthMiddleware;
use App\User;

$userAuth = AuthMiddleware::authUser();

$userModel = new User();
$message = null;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'id'       => $userAuth['id'],
        'nama'     => trim($_POST['nama']),
        'email'    => strtolower(trim($_POST['email'])),
        'username' => trim($_POST['username'])
    ];

    $result = $userModel->updateById($data);

    if ($result) {
        AuthMiddleware::logCRUD('update', 'akun', $userAuth['id']);
        header("Location: lihatakun.php");
        exit;
    } else {
        $message = "Email tidak valid atau sudah digunakan";
        $error = true;
    }

}
$userData = $userModel->getById($userAuth['id']);

require_once PUBLIC_PATH . '/partials/header.php';
//require_once PUBLIC_PATH . '/partials/sidebar.php';
?>

<!-- WRAPPER UTAMA - FULL WIDTH -->
<div class="min-h-screen bg-[#F9FAFB] w-full">
    
    <!-- FULL WIDTH CONTAINER -->
    <div class="w-full mx-auto">
        
        <!-- HEADER SECTION - Full Width -->
        <div class="w-full bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    
                    <!-- Back Button (Kiri) -->
                    <div class="w-full sm:w-auto">
                        <a href="lihatakun.php"
                           class="inline-flex items-center gap-2 px-4 py-2.5 sm:px-5 sm:py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200 w-full sm:w-auto justify-center sm:justify-start">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="text-sm sm:text-base">Kembali ke Profil</span>
                        </a>
                    </div>                    
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT - Full Width Responsive -->
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <div class="max-w-7xl mx-auto">
                
                <!-- Alert Message -->
                <?php if ($message): ?>
                <div class="w-full mb-6 sm:mb-8 p-4 sm:p-6 rounded-xl border <?= $error ? 'border-red-200 bg-red-50' : 'border-[#10B981]/30 bg-[#10B981]/10' ?>">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <?php if ($error): ?>
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#10B981]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="ml-3 sm:ml-4 flex-1">
                            <p class="text-sm sm:text-base font-medium <?= $error ? 'text-red-700' : 'text-[#10B981]' ?>">
                                <?= htmlspecialchars($message) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- FORM SECTION - Responsive Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                    
                    <!-- Left Column - Form Container -->
                    <div class="lg:col-span-2">
                        <div class="bg-white border border-gray-200 rounded-xl sm:rounded-2xl shadow-sm overflow-hidden">
                            
                            <!-- Form Header -->
                            <div class="bg-gradient-to-r from-[#93C5FD]/10 to-[#2563EB]/5 border-b border-gray-200 px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-[#1F2937]">Informasi Profil</h2>
                                        <p class="text-gray-600 text-sm sm:text-base mt-1">Lengkapi data Anda dengan benar</p>
                                    </div>
                                    <div class="hidden sm:block">
                                        <div class="w-12 h-12 bg-gradient-to-r from-[#2563EB] to-[#93C5FD] rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form Content -->
                            <div class="p-4 sm:p-6 lg:p-8">
                                <form method="POST" class="space-y-4 sm:space-y-6">
                                    
                                    <!-- Nama Field -->
                                    <div class="space-y-2">
                                        <label class="block text-sm sm:text-base font-medium text-[#1F2937]">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                Nama Lengkap <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <input type="text" 
                                               name="nama" 
                                               required
                                               value="<?= htmlspecialchars($userData['nama']) ?>"
                                               class="w-full px-4 py-3 sm:px-5 sm:py-3.5 bg-white border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors text-sm sm:text-base"
                                               placeholder="Masukkan nama lengkap Anda">
                                    </div>

                                    <!-- Email Field -->
                                    <div class="space-y-2">
                                        <label class="block text-sm sm:text-base font-medium text-[#1F2937]">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                Email Address <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <input type="email" 
                                               name="email" 
                                               required
                                               value="<?= htmlspecialchars($userData['email']) ?>"
                                               class="w-full px-4 py-3 sm:px-5 sm:py-3.5 bg-white border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors text-sm sm:text-base"
                                               placeholder="contoh@email.com">
                                    </div>

                                    <!-- Username Field -->
                                    <div class="space-y-2">
                                        <label class="block text-sm sm:text-base font-medium text-[#1F2937]">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                                </svg>
                                                Username <span class="text-red-500">*</span>
                                            </span>
                                        </label>
                                        <input type="text" 
                                               name="username" 
                                               required
                                               value="<?= htmlspecialchars($userData['username']) ?>"
                                               class="w-full px-4 py-3 sm:px-5 sm:py-3.5 bg-white border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors text-sm sm:text-base"
                                               placeholder="username_anda">
                                    </div>

                                    <!-- Form Actions -->
                                    <div class="pt-6 sm:pt-8 border-t border-gray-200">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <!-- Simpan Button -->
                                            <button type="submit"
                                                    class="w-full px-4 py-3 sm:px-6 sm:py-3.5 bg-[#2563EB] hover:bg-blue-700 text-white font-medium rounded-lg sm:rounded-xl transition-colors text-sm sm:text-base shadow-sm hover:shadow">
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>                    
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" action="/backend/user/delete.php" method="POST" class="hidden">
</form>

<!-- Custom Styles - Full Width Responsive -->
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

/* Full width adjustments */
.max-w-7xl {
    max-width: 80rem; /* 1280px */
}

/* Responsive typography */
@media (max-width: 640px) {
    .text-3xl {
        font-size: 1.5rem;
    }
    
    .text-2xl {
        font-size: 1.25rem;
    }
    
    .text-xl {
        font-size: 1.125rem;
    }
    
    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .py-3 {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
}

/* Tablet adjustments */
@media (min-width: 641px) and (max-width: 1024px) {
    .max-w-7xl {
        max-width: 100%;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
}

/* Large screen adjustments */
@media (min-width: 1536px) {
    .max-w-7xl {
        max-width: 90rem; /* 1440px */
    }
}

/* Untuk layar sangat kecil (mobile) */
@media (max-width: 359px) {
    .px-4 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .py-3 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    
    .gap-4 {
        gap: 0.75rem;
    }
}

/* Focus styles untuk form */
input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}
</style>

<script>
// Delete confirmation
function confirmDelete() {
    if (confirm('Yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan!')) {
        document.getElementById('deleteForm').submit();
    }
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const nama = this.querySelector('input[name="nama"]').value.trim();
    const email = this.querySelector('input[name="email"]').value.trim();
    const username = this.querySelector('input[name="username"]').value.trim();
    
    // Validasi dasar
    if (!nama || !email || !username) {
        e.preventDefault();
        showAlert('Semua field harus diisi!', 'error');
        return false;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        showAlert('Format email tidak valid!', 'error');
        return false;
    }
    
    // Username validation
    if (username.includes(' ')) {
        e.preventDefault();
        showAlert('Username tidak boleh mengandung spasi!', 'error');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...</span>';
    submitBtn.disabled = true;
    
    // Safety timeout
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 5000);
});

// Function untuk show alert
function showAlert(message, type = 'info') {
    // Buat elemen alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg animate-slide-in ${
        type === 'error' ? 'bg-red-50 border border-red-200 text-red-700' : 
        type === 'success' ? 'bg-green-50 border border-green-200 text-green-700' :
        'bg-blue-50 border border-blue-200 text-blue-700'
    }`;
    
    alertDiv.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                ${type === 'error' ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>' :
                type === 'success' ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>' :
                '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>'}
            </svg>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    // Tambahkan ke body
    document.body.appendChild(alertDiv);
    
    // Hapus setelah 5 detik
    setTimeout(() => {
        alertDiv.classList.add('animate-slide-out');
        setTimeout(() => {
            document.body.removeChild(alertDiv);
        }, 300);
    }, 5000);
}

// Animasi untuk alert
const style = document.createElement('style');
style.textContent = `
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
    
    .animate-slide-out {
        animation: slide-out 0.3s ease-in;
    }
`;
document.head.appendChild(style);
</script>