<?php
require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/backend/auth.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

use App\auth\Autentikasi;
use App\AuthMiddleware;

// Redirect jika sudah login
AuthMiddleware::requireNoAuth();

$auth = new Autentikasi();

// Cek jika ada error di URL (dari backend)
$error = '';
if (isset($_GET['error'])) {
    $error = urldecode($_GET['error']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    $auth->loginAndRedirect($username, $password);
    exit;
}

require_once PUBLIC_PATH . '/partials/header.php';
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
    <title>Login - StudyYou</title>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Main Container - Menggunakan flex-col untuk mobile -->
    <div class="min-h-screen flex flex-col">
        <!-- Header Mobile (hanya muncul di mobile) -->
        <div class="lg:hidden bg-white shadow-sm border-b border-gray-200 p-4">
            <div class="flex items-center justify-center">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-accent flex items-center justify-center mr-3">
                    <i class="fas fa-graduation-cap text-white"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-900">StudyYou</h1>
            </div>
        </div>

        <!-- Content Area - Grow untuk mengisi ruang yang tersedia -->
        <div class="flex-1 flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <!-- Header Desktop (hanya muncul di desktop) -->
                <div class="hidden lg:block text-center mb-8">
                    <div class="mb-4">
                        <div class="w-20 h-20 mx-auto rounded-xl bg-gradient-to-br from-primary to-accent flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-3xl"></i>
                        </div>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900">StudyYou</h1>
                    <p class="text-gray-600 mt-2 text-lg">Silakan masuk ke akun Anda</p>
                </div>

                <!-- Login Card -->
                <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-200">
                    <?php if (!empty($error)): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                            <div>
                                <p class="text-sm text-red-800 font-medium">
                                    <?php echo htmlspecialchars($error); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form method="POST" action="" id="loginForm" class="space-y-6">
                        <!-- Username Field -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="username" 
                                    id="username" 
                                    placeholder="Masukkan username"
                                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition text-base"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Password
                                </label>
                                <a href="forgot_pass.php" class="text-sm text-primary hover:text-primary/80 transition">
                                    Lupa password?
                                </a>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    placeholder="Masukkan password"
                                    class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition text-base"
                                    required
                                >
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                    class="w-full py-3 px-4 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition text-base">
                                <span id="submitText">Masuk</span>
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="my-6 relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Atau</span>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-gray-600">
                            Belum punya akun? 
                            <a href="register.php" class="text-primary font-medium hover:text-primary/80 transition">
                                Daftar di sini
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 text-center text-sm text-gray-500">
                    <p>© 2024 StudyYou. All rights reserved.</p>
                </div>
            </div>
        </div>

        <!-- Bottom spacing untuk mobile keyboard -->
        <div class="h-4 lg:hidden"></div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = passwordInput.nextElementSibling.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
        
        // Form submission handler
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const submitText = document.getElementById('submitText');
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Memproses...';
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            
            // Add loading class
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
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

        // Adjust height for mobile viewport
        function adjustViewportHeight() {
            let vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }

        // Initial adjustment
        adjustViewportHeight();

        // Adjust on resize and orientation change
        window.addEventListener('resize', adjustViewportHeight);
        window.addEventListener('orientationchange', adjustViewportHeight);
    </script>
</body>
</html>