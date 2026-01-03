<?php
require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/backend/auth.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

use App\auth\Autentikasi;
use App\AuthMiddleware;

AuthMiddleware::requireNoAuth();

$auth = new Autentikasi();

$error = '';
$success_message = '';
if (isset($_GET['error'])) {
    $error = urldecode($_GET['error']);
}
if (isset($_GET['success'])) {
    $success_message = 'Registrasi berhasil! Silakan login dengan akun Anda.';
}

$form_data = [
    'username' => $_POST['username'] ?? ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    $auth->loginAndRedirect($username, $password);
    exit;
}

require_once PUBLIC_PATH . '/partials/header.php';
?>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        :root {
            --primary: #2563EB;
            --secondary: #93C5FD;
            --accent: #10B981;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        }
    </style>
    <title>Login - StudyYou</title>
<body class="bg-gray-50 min-h-screen">

    <div class="min-h-screen flex flex-col">

        <div class="lg:hidden bg-white shadow-sm border-b border-gray-200 p-4">
            <div class="flex items-center justify-center">
                <div class="w-10 h-10 rounded-lg gradient-primary flex items-center justify-center mr-3">
                    <i class="material-icons text-white">school</i>
                </div>
                <h1 class="text-xl font-bold text-gray-900">StudyYou</h1>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <!-- Header Desktop -->
                <div class="hidden lg:block text-center mb-8">
                    <div class="mb-6">
                        <div class="w-24 h-24 mx-auto rounded-full gradient-primary flex items-center justify-center shadow-lg">
                            <i class="material-icons-sharp text-white text-4xl">school</i>
                        </div>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">StudyYou</h1>
                    <p class="text-gray-600 text-lg">Selamat datang, silahkan login</p>
                </div>

                <!-- Login Card -->
                <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 border border-gray-100">
                    <?php if ($success_message): ?>
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg animate-fade-in">
                        <div class="flex items-center">
                            <i class="material-icons text-green-500 mr-3">check_circle</i>
                            <div>
                                <p class="text-sm text-green-800 font-medium">
                                    <?php echo htmlspecialchars($success_message); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg animate-fade-in">
                        <div class="flex items-start">
                            <i class="material-icons text-red-500 mr-3 mt-0.5">error</i>
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
                        <!-- Username/Email Field -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <i class="material-icons text-sm mr-1 text-gray-500">person</i>
                                    Username
                                </span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="material-icons text-gray-400">person</i>
                                </div>
                                <input 
                                    type="text" 
                                    name="username" 
                                    id="username" 
                                    placeholder="Masukkan username anda"
                                    value="<?php echo htmlspecialchars($form_data['username']); ?>"
                                    class="w-full pl-10 pr-3 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all duration-200 text-base placeholder-gray-400"
                                    required
                                    autocomplete="username"
                                    autofocus
                                >
                            </div>
                            <p id="usernameError" class="mt-1 text-xs text-red-500 hidden"></p>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <i class="material-icons text-sm mr-1 text-gray-500">lock</i>
                                        Password
                                    </span>
                                </label>
                                <a href="forgot_pass.php" class="text-sm text-blue-600 hover:text-blue-800 transition font-medium inline-flex items-center">
                                    <i class="material-icons text-sm mr-1">lock_reset</i>
                                    Lupa password?
                                </a>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="material-icons text-gray-400">lock</i>
                                </div>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    placeholder="Masukkan password"
                                    class="w-full pl-10 pr-10 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all duration-200 text-base placeholder-gray-400"
                                    required
                                    autocomplete="current-password"
                                >
                                <button type="button" onclick="togglePassword()" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition"
                                        aria-label="Toggle password visibility">
                                    <i class="material-icons" id="passwordToggleIcon">visibility</i>
                                </button>
                            </div>
                            <p id="passwordError" class="mt-1 text-xs text-red-500 hidden"></p>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" 
                                    id="submitBtn"
                                    class="w-full py-3.5 px-4 gradient-primary text-white font-semibold rounded-xl hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 text-base shadow-lg hover:shadow-xl">
                                <span id="submitText" class="flex items-center justify-center">
                                    <i class="material-icons mr-2">login</i>
                                    Masuk ke Akun
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="my-8 relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-400">Belum punya akun?</span>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <a href="register.php" 
                           class="inline-flex items-center justify-center w-full py-3.5 px-4 border-2 border-blue-600 text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-all duration-200">
                            <i class="material-icons mr-2">person_add</i>
                            Buat Akun Baru
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        © <?php echo date('Y'); ?> StudyYou. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.textContent = 'visibility_off';
                passwordInput.focus();
            } else {
                passwordInput.type = 'password';
                icon.textContent = 'visibility';
            }
        }
        
        // Form validation
        function validateForm() {
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.text-red-500').forEach(el => {
                if (el.id.endsWith('Error')) el.classList.add('hidden');
            });
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
                el.classList.add('border-gray-300');
            });
            
            // Validate username/email
            const username = document.getElementById('username').value.trim();
            if (username.length === 0) {
                document.getElementById('usernameError').textContent = 'Username atau email harus diisi';
                document.getElementById('usernameError').classList.remove('hidden');
                document.getElementById('username').classList.add('border-red-500', 'animate-shake');
                isValid = false;
            }
            
            // Validate password
            const password = document.getElementById('password').value;
            if (password.length === 0) {
                document.getElementById('passwordError').textContent = 'Password harus diisi';
                document.getElementById('passwordError').classList.remove('hidden');
                document.getElementById('password').classList.add('border-red-500', 'animate-shake');
                isValid = false;
            }
            
            // Remove shake animation after delay
            setTimeout(() => {
                document.querySelectorAll('.animate-shake').forEach(el => {
                    el.classList.remove('animate-shake');
                });
            }, 500);
            
            return isValid;
        }
        
        // Form submission handler
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return false;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.innerHTML = '<i class="material-icons animate-spin mr-2">refresh</i>Memproses...';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            
            // Submit the form
            setTimeout(() => {
                this.submit();
            }, 300);
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + Enter to submit
            if (e.ctrlKey && e.key === 'Enter') {
                document.getElementById('loginForm').requestSubmit();
            }
            
            // Tab navigation with validation
            if (e.key === 'Tab') {
                const activeElement = document.activeElement;
                if (activeElement.id === 'username' && activeElement.value.trim().length === 0) {
                    e.preventDefault();
                    document.getElementById('usernameError').textContent = 'Username atau email harus diisi';
                    document.getElementById('usernameError').classList.remove('hidden');
                    activeElement.classList.add('border-red-500');
                }
            }
        });
        
        // Real-time validation
        document.getElementById('username').addEventListener('blur', function() {
            if (this.value.trim().length === 0) {
                document.getElementById('usernameError').textContent = 'Username atau email harus diisi';
                document.getElementById('usernameError').classList.remove('hidden');
                this.classList.add('border-red-500');
            } else {
                document.getElementById('usernameError').classList.add('hidden');
                this.classList.remove('border-red-500');
            }
        });
        
        document.getElementById('password').addEventListener('blur', function() {
            if (this.value.length === 0) {
                document.getElementById('passwordError').textContent = 'Password harus diisi';
                document.getElementById('passwordError').classList.remove('hidden');
                this.classList.add('border-red-500');
            } else {
                document.getElementById('passwordError').classList.add('hidden');
                this.classList.remove('border-red-500');
            }
        });
        
        // Auto-focus on username field if empty
        document.addEventListener('DOMContentLoaded', function() {
            const usernameField = document.getElementById('username');
            if (usernameField.value.trim() === '') {
                usernameField.focus();
            }
            
            // Adjust viewport for mobile
            if ('ontouchstart' in window) {
                document.querySelectorAll('input').forEach(input => {
                    input.addEventListener('focus', function() {
                        this.style.fontSize = '16px';
                    });
                    
                    input.addEventListener('blur', function() {
                        this.style.fontSize = '';
                    });
                });
            }
        });
    </script>
</body>
</html>