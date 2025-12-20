<?php
require_once __DIR__ . "/../../../config.php";
require_once ROOT_PATH . '/backend/userAcc.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

use App\AuthMiddleware;
use App\User;

$userAuth = AuthMiddleware::authUser();
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['new_password'] !== $_POST['confirm_password']) {
        header("Location: gantipass.php?error=confirm");
        exit;
    }

    if (strlen($_POST['new_password']) < 6) {
        header("Location: gantipass.php?error=length");
        exit;
    }

    if ($user->updatePassword(
        $userAuth['id'],
        $_POST['old_password'],
        $_POST['new_password']
    )) {

        AuthMiddleware::logCRUD('update', 'password', $userAuth['id']);
        AuthMiddleware::logout();

        header("Location: login.php?msg=password_changed");
        exit;
    } else {
        header("Location: gantipass.php?error=wrong_old");
        exit;
    }
}

require_once PUBLIC_PATH . '/partials/header.php';
// Hapus sidebar require
// require_once PUBLIC_PATH . '/partials/sidebar.php';
?>

<!-- WRAPPER UTAMA - Tanpa sidebar -->
<div class="min-h-screen bg-[#F9FAFB]">
    
    <!-- Container tanpa margin kiri -->
    <div class="w-full transition-all duration-300">
        
        <!-- Full width container -->
        <div class="w-full">
            
            <!-- HEADER SECTION - Di tengah -->
            <div class="w-full px-3 sm:px-4 md:px-6 py-3 sm:py-4 bg-white border-b border-gray-100">
                <div class="max-w-6xl mx-auto">
                    <!-- Gunakan grid untuk control yang lebih baik -->
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 items-start md:items-center gap-3 sm:gap-4">
                        
                        <!-- Left Column -->
                        <div class="w-full md:col-span-1">
                        </div>
                        
                        <!-- Right Column - Button di kanan -->
                        <div class="w-full md:col-span-1 md:flex md:justify-end">
                            <a href="lihatakun.php"
                               class="group w-full md:w-auto inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Kembali ke Profil</span>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT - Form di tengah -->
            <div class="w-full p-3 sm:p-4 md:p-6">
                <div class="w-full max-w-6xl mx-auto">
                    
                    <!-- FORM SECTION - Center aligned -->
                    <div class="flex justify-center items-center min-h-[calc(100vh-200px)]">
                        <div class="w-full max-w-md">
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">    
                                <!-- Form Content -->
                                <div class="w-full p-4 sm:p-6 md:p-8">
                                    <!-- Error Messages -->
                                    <?php if (isset($_GET['error'])): ?>
                                    <div class="w-full mb-4 sm:mb-6 p-3 sm:p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-sm text-red-700">
                                                <?php 
                                                    if ($_GET['error'] === 'confirm') {
                                                        echo "Konfirmasi password tidak sesuai.";
                                                    } elseif ($_GET['error'] === 'length') {
                                                        echo "Password baru harus terdiri dari minimal 6 karakter.";
                                                    } elseif ($_GET['error'] === 'wrong_old') {
                                                        echo "Password lama salah.";
                                                    }
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <form method="POST" action="" class="space-y-4 sm:space-y-6">
                                        
                                        <!-- Old Password -->
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-[#1F2937]">
                                                <span class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    Password Lama
                                                </span>
                                            </label>
                                            <input type="password" 
                                                   id="old_password" 
                                                   name="old_password" 
                                                   required
                                                   class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors text-sm sm:text-base"
                                                   placeholder="Masukkan password lama Anda">
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-[#1F2937]">
                                                <span class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    Password Baru
                                                </span>
                                            </label>
                                            <input type="password" 
                                                   id="new_password" 
                                                   name="new_password" 
                                                   required
                                                   class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors text-sm sm:text-base"
                                                   placeholder="Masukkan password baru (minimal 6 karakter)">
                                            <p class="mt-1 text-xs text-gray-500">Minimal 6 karakter</p>
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-[#1F2937]">
                                                <span class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Konfirmasi Password Baru
                                                </span>
                                            </label>
                                            <input type="password" 
                                                   id="confirm_password" 
                                                   name="confirm_password" 
                                                   required
                                                   class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors text-sm sm:text-base"
                                                   placeholder="Ulangi password baru Anda">
                                            <p class="mt-1 text-xs text-gray-500">Pastikan sama dengan password baru</p>
                                        </div>

                                        <!-- Form Actions -->
                                        <div class="pt-4 sm:pt-6">
                                            <button type="submit"
                                                    class="w-full px-4 sm:px-6 py-2.5 sm:py-3 bg-[#2563EB] hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm sm:text-base">
                                                Ganti Password
                                            </button>
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

<!-- Custom Styles -->
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
    
    .px-3 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
}

/* Focus styles untuk form */
input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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
    
    .py-2\.5 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
}

/* Center content vertically */
.min-h-screen {
    display: flex;
    flex-direction: column;
}

/* Header tetap di atas */
header {
    position: sticky;
    top: 0;
    z-index: 50;
}
</style>

<script>
// Form validation dengan real-time feedback
document.querySelector('form').addEventListener('submit', function(e) {
    const oldPassword = this.querySelector('#old_password').value.trim();
    const newPassword = this.querySelector('#new_password').value.trim();
    const confirmPassword = this.querySelector('#confirm_password').value.trim();
    
    // Validasi input
    if (!oldPassword) {
        e.preventDefault();
        alert('Password lama harus diisi!');
        return false;
    }
    
    if (!newPassword || newPassword.length < 6) {
        e.preventDefault();
        alert('Password baru minimal 6 karakter!');
        return false;
    }
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('Konfirmasi password tidak sesuai!');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Memproses...';
    submitBtn.disabled = true;
    
    // Re-enable after 3 seconds if still processing (safety)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 3000);
});

// Real-time password strength check
document.querySelector('#new_password').addEventListener('input', function() {
    const password = this.value;
    const strengthIndicator = document.createElement('div');
    
    // Remove existing indicator if any
    const existingIndicator = this.parentNode.querySelector('.password-strength');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    if (password.length > 0) {
        let strength = 'Lemah';
        let color = 'text-red-500';
        
        if (password.length >= 8 && /[A-Z]/.test(password) && /[0-9]/.test(password)) {
            strength = 'Sangat Kuat';
            color = 'text-green-500';
        } else if (password.length >= 8 && (/[A-Z]/.test(password) || /[0-9]/.test(password))) {
            strength = 'Kuat';
            color = 'text-green-400';
        } else if (password.length >= 6) {
            strength = 'Cukup';
            color = 'text-yellow-500';
        }
        
        strengthIndicator.className = `password-strength text-xs mt-1 ${color}`;
        strengthIndicator.innerHTML = `Kekuatan: ${strength}`;
        this.parentNode.appendChild(strengthIndicator);
    }
});
</script>