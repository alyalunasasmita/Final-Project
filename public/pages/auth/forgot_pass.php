<?php
// Cek session status sebelum start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sanitize GET parameters
$err = isset($_GET['err']) ? htmlspecialchars($_GET['err'], ENT_QUOTES, 'UTF-8') : '';
$msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8') : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563EB',
                        secondary: '#93C5FD',
                        accent: '#10B981',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Lupa Password - StudyYou</title>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white shadow-sm border-b border-gray-200 p-4">
        <div class="flex items-center justify-center">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-accent flex items-center justify-center mr-3">
                <i class="fas fa-key text-white"></i>
            </div>
            <h1 class="text-xl font-bold text-gray-900">StudyYou</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Header Desktop -->
            <div class="hidden lg:block text-center mb-8">
                <div class="mb-4">
                    <div class="w-20 h-20 mx-auto rounded-xl bg-gradient-to-br from-primary to-accent flex items-center justify-center">
                        <i class="fas fa-key text-white text-3xl"></i>
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-gray-900">Lupa Password?</h1>
                <p class="text-gray-600 mt-2 text-lg">Kami akan mengirim kode OTP ke email Anda</p>
            </div>

            <!-- Forgot Password Card -->
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-200">
                <!-- Messages -->
                <?php if ($msg): ?>
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <div>
                            <p class="text-sm text-green-800 font-medium">
                                <?= $msg ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($err): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <div>
                            <p class="text-sm text-red-800 font-medium">
                                <?= $err ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Dynamic Message -->
                <div id="dynamicMessage" class="mb-6 hidden p-4 rounded-lg"></div>

                <!-- Forgot Password Form -->
                <form id="forgotForm" method="POST" class="space-y-6">
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                required 
                                pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
                                placeholder="nama@email.com"
                                class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition text-base"
                            />
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Masukkan email yang terdaftar di akun StudyYou Anda
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                id="submitBtn"
                                class="w-full py-3 px-4 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition text-base">
                            <span id="submitText">Kirim Kode OTP</span>
                        </button>
                    </div>
                </form>

                <!-- Info & Back to Login -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="text-center space-y-4">
                        <div class="text-sm text-gray-600 flex items-center justify-center">
                            <i class="fas fa-info-circle text-primary mr-2"></i>
                            <span>Kode OTP akan berlaku selama 10 menit</span>
                        </div>
                        
                        <div>
                            <a href="login.php" class="text-primary font-medium hover:text-primary/80 transition inline-flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-sm text-gray-500">
                <p>Tidak menerima email? Periksa folder spam atau coba beberapa saat lagi</p>
            </div>
        </div>
    </div>

    <!-- Bottom spacing untuk mobile -->
    <div class="h-4 lg:hidden"></div>

    <script>
        // Function to show message
        function showMessage(text, type = 'info') {
            const messageEl = document.getElementById('dynamicMessage');
            messageEl.textContent = text;
            messageEl.classList.remove('hidden');
            
            // Set color based on type
            messageEl.classList.remove('bg-blue-50', 'border-blue-200', 'text-blue-800');
            messageEl.classList.remove('bg-green-50', 'border-green-200', 'text-green-800');
            messageEl.classList.remove('bg-red-50', 'border-red-200', 'text-red-800');
            
            switch(type) {
                case 'success':
                    messageEl.classList.add('bg-green-50', 'border-green-200', 'text-green-800');
                    break;
                case 'error':
                    messageEl.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
                    break;
                default:
                    messageEl.classList.add('bg-blue-50', 'border-blue-200', 'text-blue-800');
            }
        }
        
        // Form submission handler
        document.getElementById('forgotForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            
            // Basic email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                showMessage('Format email tidak valid. Contoh: nama@email.com', 'error');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Mengirim...';
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            
            showMessage('Mengirim kode OTP ke email Anda...', 'info');
            
            try {
                // Prepare form data
                const formData = new FormData();
                formData.append('email', email);
                
                // Send request to API
                const response = await fetch('/api/auth/forgot_password.php', {
                    method: 'POST',
                    body: formData
                });
                
                const text = await response.text();
                let result;
                
                try {
                    result = JSON.parse(text);
                } catch {
                    throw new Error('Response tidak valid');
                }
                
                if (result.success) {
                    showMessage('Kode OTP berhasil dikirim! Silakan periksa email Anda.', 'success');
                    
                    // Update button state
                    submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>OTP Terkirim!';
                    submitBtn.classList.remove('bg-primary');
                    submitBtn.classList.add('bg-green-600');
                    
                    // Store email in sessionStorage for OTP page
                    sessionStorage.setItem('reset_email', email);
                    
                    // Redirect to OTP verification page
                    setTimeout(() => {
                        window.location.href = 'verify.php';
                    }, 2000);
                } else {
                    showMessage(result.message || 'Gagal mengirim OTP. Silakan coba lagi.', 'error');
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    submitText.textContent = 'Kirim Kode OTP';
                    submitBtn.innerHTML = '<span id="submitText">Kirim Kode OTP</span>';
                    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('Terjadi kesalahan koneksi. Periksa koneksi internet Anda.', 'error');
                
                // Reset button state
                submitBtn.disabled = false;
                submitText.textContent = 'Kirim Kode OTP';
                submitBtn.innerHTML = '<span id="submitText">Kirim Kode OTP</span>';
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });
        
        // Clear dynamic message when user starts typing
        document.getElementById('email').addEventListener('input', function() {
            const messageEl = document.getElementById('dynamicMessage');
            if (messageEl.textContent) {
                messageEl.classList.add('hidden');
            }
        });
        
        // Pre-fill email from sessionStorage if available
        document.addEventListener('DOMContentLoaded', function() {
            const savedEmail = sessionStorage.getItem('reset_email');
            if (savedEmail) {
                document.getElementById('email').value = savedEmail;
            }
        });
        
        // Prevent zoom on input focus in mobile
        document.addEventListener('DOMContentLoaded', function() {
            if ('ontouchstart' in window) {
                document.querySelectorAll('input').forEach(input => {
                    input.addEventListener('focus', function() {
                        this.setAttribute('style', 'font-size: 16px !important');
                    });
                    
                    input.addEventListener('blur', function() {
                        this.removeAttribute('style');
                    });
                });
            }
        });
    </script>
</body>
</html>