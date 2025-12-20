<?php
session_start();
require_once __DIR__ . '/../../config.php';

require_once ROOT_PATH . '/backend/auth.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';
use App\auth\Autentikasi;

$auth = new Autentikasi();
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $result = $auth->register(
            $_POST['nama'], 
            $_POST['username'], 
            $_POST['email'], 
            $_POST['password']
        );
        
        if ($result === true) {
            $success = true;
        } else {
            $error = $result;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
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
    <title>Register - StudyYou</title>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Main Container -->
    <div class="min-h-screen flex flex-col">
        <!-- Header Mobile -->
        <div class="lg:hidden bg-white shadow-sm border-b border-gray-200 p-4">
            <div class="flex items-center justify-center">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-accent flex items-center justify-center mr-3">
                    <i class="fas fa-graduation-cap text-white"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-900">StudyYou</h1>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <!-- Header Desktop -->
                <div class="hidden lg:block text-center mb-8">
                    <div class="mb-4">
                        <div class="w-20 h-20 mx-auto rounded-xl bg-gradient-to-br from-primary to-accent flex items-center justify-center">
                            <i class="fas fa-user-plus text-white text-3xl"></i>
                        </div>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900">Daftar Akun Baru</h1>
                    <p class="text-gray-600 mt-2 text-lg">Bergabung dengan StudyYou</p>
                </div>

                <!-- Register Card -->
                <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-200">
                    <?php if ($success): ?>
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <div>
                                <p class="text-sm text-green-800 font-medium">
                                    Registrasi berhasil! Silakan login.
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

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

                    <!-- Register Form -->
                    <form method="POST" action="" id="registerForm" class="space-y-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="nama" 
                                    id="nama"
                                    placeholder="Masukkan nama lengkap"
                                    value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>"
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition text-base"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email"
                                    placeholder="contoh@email.com"
                                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition text-base"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-at text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="username" 
                                    id="username"
                                    placeholder="Pilih username"
                                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition text-base"
                                    required
                                >
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Minimal 4 karakter, tanpa spasi
                            </p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password"
                                    placeholder="Minimal 6 karakter"
                                    class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition text-base"
                                    required
                                >
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-2">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs text-gray-500">Kekuatan password:</span>
                                    <span id="strengthValue" class="text-xs font-medium">Lemah</span>
                                </div>
                                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div id="strengthFill" class="h-full w-0 bg-red-500 transition-all duration-300"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="text-sm text-gray-600">
                            <p>
                                Dengan mendaftar, Anda menyetujui 
                                <a href="#" class="text-primary hover:text-primary/80 transition">Ketentuan Layanan</a> 
                                dan 
                                <a href="#" class="text-primary hover:text-primary/80 transition">Kebijakan Privasi</a>
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                    class="w-full py-3 px-4 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition text-base">
                                <span id="submitText">Daftar Sekarang</span>
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="my-6 relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Sudah punya akun?</span>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <a href="login.php" class="text-primary font-medium hover:text-primary/80 transition inline-flex items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk ke Akun Anda
                        </a>
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
        
        // Check Password Strength
        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Length
            if (password.length >= 6) strength += 25;
            if (password.length >= 8) strength += 25;
            
            // Complexity
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;
            
            return Math.min(strength, 100);
        }
        
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            
            let text = 'Lemah';
            let color = '#ef4444'; // red
            
            if (strength >= 75) {
                text = 'Kuat';
                color = '#10b981'; // green
            } else if (strength >= 50) {
                text = 'Cukup';
                color = '#f59e0b'; // yellow
            } else if (strength >= 25) {
                text = 'Lemah';
                color = '#f97316'; // orange
            }
            
            document.getElementById('strengthValue').textContent = text;
            document.getElementById('strengthValue').style.color = color;
            
            const fill = document.getElementById('strengthFill');
            fill.style.width = `${strength}%`;
            fill.style.backgroundColor = color;
        });
        
        // Form submission handler
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const submitText = document.getElementById('submitText');
            
            // Validation
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const email = document.getElementById('email').value;
            const nama = document.getElementById('nama').value;
            
            let isValid = true;
            
            // Clear previous error borders
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
                el.classList.add('border-gray-300');
            });
            
            // Validate nama
            if (nama.length < 3) {
                document.getElementById('nama').classList.add('border-red-500');
                document.getElementById('nama').classList.remove('border-gray-300');
                isValid = false;
            }
            
            // Validate username
            if (username.length < 4 || /\s/.test(username)) {
                document.getElementById('username').classList.add('border-red-500');
                document.getElementById('username').classList.remove('border-gray-300');
                isValid = false;
            }
            
            // Validate email
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById('email').classList.add('border-red-500');
                document.getElementById('email').classList.remove('border-gray-300');
                isValid = false;
            }
            
            // Validate password
            if (password.length < 6) {
                document.getElementById('password').classList.add('border-red-500');
                document.getElementById('password').classList.remove('border-gray-300');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Memproses...';
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            
            return true;
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
        
        // Auto redirect on success
        <?php if ($success): ?>
        setTimeout(() => {
            window.location.href = 'login.php?success=1';
        }, 2000);
        <?php endif; ?>
    </script>
</body>
</html>