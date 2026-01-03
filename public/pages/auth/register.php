<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/backend/auth.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';
use App\auth\Autentikasi;

$auth = new Autentikasi();
$error = '';
$success = false;
$form_data = [
    'nama' => '',
    'username' => '',
    'email' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $form_data = [
            'nama' => $_POST['nama'] ?? '',
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];
        
        $result = $auth->register(
            $form_data['nama'], 
            $form_data['username'], 
            $form_data['email'], 
            $_POST['password'] ?? ''
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
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Custom Colors */
        :root {
            --primary: #2563EB;
            --secondary: #93C5FD;
            --accent: #10B981;
        }
    </style>
    <title>Register - StudyYou</title>
<body class="bg-gray-50 min-h-screen">
    <!-- Main Container -->
    <div class="min-h-screen flex flex-col">
        <!-- Header Mobile -->
        <div class="lg:hidden bg-white shadow-sm border-b border-gray-200 p-4">
            <div class="flex items-center justify-center">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-600 to-emerald-500 flex items-center justify-center mr-3">
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
                    <div class="mb-4">
                        <div class="w-20 h-20 mx-auto rounded-xl bg-gradient-to-br from-blue-600 to-emerald-500 flex items-center justify-center">
                            <i class="material-icons-sharp text-white text-3xl">person_add</i>
                        </div>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900">Daftar Akun Baru</h1>
                    <p class="text-gray-600 mt-2 text-lg">Bergabung dengan StudyYou</p>
                </div>

                <!-- Register Card -->
                <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-200">
                    <?php if ($success): ?>
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg animate-fade-in">
                        <div class="flex items-center">
                            <i class="material-icons text-green-500 mr-3">check_circle</i>
                            <div class="flex-1">
                                <p class="text-sm text-green-800 font-medium">
                                    Registrasi berhasil! Anda akan diarahkan ke halaman login.
                                </p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-green-200">
                            <div class="w-full h-1.5 bg-green-100 rounded-full overflow-hidden">
                                <div id="successProgress" class="h-full bg-green-500 w-0 transition-all duration-2000"></div>
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
                                <?php if (strpos($error, 'sudah terdaftar') !== false): ?>
                                <p class="text-red-600 text-xs mt-1">
                                    <a href="login.php" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
                                        <i class="material-icons text-sm mr-1">login</i> Login disini
                                    </a>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Register Form -->
                    <form method="POST" action="" id="registerForm" class="space-y-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <i class="material-icons text-sm mr-1 text-gray-500">person</i>
                                    Nama Lengkap
                                </span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="material-icons text-gray-400">person</i>
                                </div>
                                <input 
                                    type="text" 
                                    name="nama" 
                                    id="nama"
                                    placeholder="Masukkan nama lengkap"
                                    value="<?php echo htmlspecialchars($form_data['nama']); ?>"
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition text-base placeholder-gray-400"
                                    required
                                    minlength="3"
                                    maxlength="50"
                                >
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <span id="namaCounter" class="text-xs text-gray-400">0/50</span>
                                </div>
                            </div>
                            <p id="namaError" class="mt-1 text-xs text-red-500 hidden"></p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <i class="material-icons text-sm mr-1 text-gray-500">email</i>
                                    Alamat Email
                                </span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="material-icons text-gray-400">email</i>
                                </div>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email"
                                    placeholder="contoh@email.com"
                                    value="<?php echo htmlspecialchars($form_data['email']); ?>"
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition text-base placeholder-gray-400"
                                    required
                                >
                            </div>
                            <p id="emailError" class="mt-1 text-xs text-red-500 hidden"></p>
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <i class="material-icons text-sm mr-1 text-gray-500">alternate_email</i>
                                    Username
                                </span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="material-icons text-gray-400">alternate_email</i>
                                </div>
                                <input 
                                    type="text" 
                                    name="username" 
                                    id="username"
                                    placeholder="Pilih username"
                                    value="<?php echo htmlspecialchars($form_data['username']); ?>"
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition text-base placeholder-gray-400"
                                    required
                                    minlength="4"
                                    maxlength="20"
                                    pattern="[A-Za-z0-9_]+"
                                >
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <span id="usernameCounter" class="text-xs text-gray-400">0/20</span>
                                </div>
                            </div>
                            <p id="usernameError" class="mt-1 text-xs text-red-500 hidden"></p>
                            <p class="mt-1 text-xs text-gray-500">
                                Minimal 4 karakter, hanya huruf, angka, dan underscore (_)
                            </p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <i class="material-icons text-sm mr-1 text-gray-500">lock</i>
                                    Password
                                </span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="material-icons text-gray-400">lock</i>
                                </div>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password"
                                    placeholder="Minimal 8 karakter"
                                    class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition text-base placeholder-gray-400"
                                    required
                                    minlength="6"
                                >
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                    <i class="material-icons" id="passwordToggleIcon">visibility</i>
                                </button>
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-3">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs text-gray-500">Kekuatan password:</span>
                                    <span id="strengthValue" class="text-xs font-medium">Belum ada</span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div id="strengthFill" class="h-full w-0 transition-all duration-300"></div>
                                </div>
                                <div class="mt-2 grid grid-cols-2 gap-2">
                                    <div class="flex items-center">
                                        <i id="lengthCheck" class="material-icons text-xs mr-1 text-gray-400">radio_button_unchecked</i>
                                        <span class="text-xs text-gray-500">Min. 6 karakter</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i id="numberCheck" class="material-icons text-xs mr-1 text-gray-400">radio_button_unchecked</i>
                                        <span class="text-xs text-gray-500">Angka</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <i class="material-icons text-sm mr-1 text-gray-500">lock_reset</i>
                                    Konfirmasi Password
                                </span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="material-icons text-gray-400">lock_reset</i>
                                </div>
                                <input 
                                    type="password" 
                                    name="confirm_password" 
                                    id="confirmPassword"
                                    placeholder="Ketik ulang password"
                                    class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition text-base placeholder-gray-400"
                                    required
                                >
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i id="passwordMatchIcon" class="material-icons text-transparent">check_circle</i>
                                </div>
                            </div>
                            <p id="confirmPasswordError" class="mt-1 text-xs text-red-500 hidden"></p>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="terms" id="terms" 
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" required>
                            </div>
                            <div class="ml-3">
                                <label for="terms" class="text-sm text-gray-600">
                                    Saya setuju dengan 
                                    <a href="#" class="text-blue-600 hover:text-blue-800 transition font-medium">Ketentuan Layanan</a> 
                                    dan 
                                    <a href="#" class="text-blue-600 hover:text-blue-800 transition font-medium">Kebijakan Privasi</a>
                                </label>
                                <p id="termsError" class="text-xs text-red-500 hidden">Anda harus menyetujui ketentuan</p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" 
                                    id="submitBtn"
                                    class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition text-base shadow-sm">
                                <span id="submitText" class="flex items-center justify-center">
                                    <i class="material-icons mr-2">person_add</i>
                                    Daftar Sekarang
                                </span>
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
                        <a href="login.php" class="text-blue-600 font-medium hover:text-blue-800 transition inline-flex items-center">
                            <i class="material-icons mr-2">login</i>
                            Masuk ke Akun Anda
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 text-center text-sm text-gray-500">
                    <p>© 2025 StudyYou. All rights reserved.</p>
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
            const confirmInput = document.getElementById('confirmPassword');
            const icon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                confirmInput.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                confirmInput.type = 'password';
                icon.textContent = 'visibility';
            }
        }
        
        // Character counters
        document.getElementById('nama').addEventListener('input', function() {
            const counter = document.getElementById('namaCounter');
            const length = this.value.length;
            counter.textContent = `${length}/50`;
            counter.className = `text-xs ${length > 45 ? 'text-red-500' : length > 35 ? 'text-yellow-500' : 'text-gray-400'}`;
        });
        
        document.getElementById('username').addEventListener('input', function() {
            const counter = document.getElementById('usernameCounter');
            const length = this.value.length;
            counter.textContent = `${length}/20`;
            counter.className = `text-xs ${length > 18 ? 'text-red-500' : length > 15 ? 'text-yellow-500' : 'text-gray-400'}`;
        });
        
        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            let checks = {
                length: false,
                number: false,
                upper: false,
                lower: false,
                special: false
            };
            
            // Length check
            if (password.length >= 6) {
                strength += 20;
                checks.length = true;
                document.getElementById('lengthCheck').textContent = 'check_circle';
                document.getElementById('lengthCheck').classList.remove('text-gray-400');
                document.getElementById('lengthCheck').classList.add('text-emerald-500');
            } else {
                document.getElementById('lengthCheck').textContent = 'radio_button_unchecked';
                document.getElementById('lengthCheck').classList.remove('text-emerald-500');
                document.getElementById('lengthCheck').classList.add('text-gray-400');
            }
            
            // Number check
            if (/[0-9]/.test(password)) {
                strength += 20;
                checks.number = true;
                document.getElementById('numberCheck').textContent = 'check_circle';
                document.getElementById('numberCheck').classList.remove('text-gray-400');
                document.getElementById('numberCheck').classList.add('text-emerald-500');
            } else {
                document.getElementById('numberCheck').textContent = 'radio_button_unchecked';
                document.getElementById('numberCheck').classList.remove('text-emerald-500');
                document.getElementById('numberCheck').classList.add('text-gray-400');
            }
            
            // Additional checks
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[a-z]/.test(password)) strength += 20;
            if (/[^A-Za-z0-9]/.test(password)) strength += 20;
            
            // Update strength indicator
            const fill = document.getElementById('strengthFill');
            const text = document.getElementById('strengthValue');
            
            if (password.length === 0) {
                text.textContent = 'Belum ada';
                text.className = 'text-xs font-medium text-gray-500';
                fill.style.width = '0%';
                fill.style.backgroundColor = '';
                return;
            }
            
            let strengthText = 'Sangat Lemah';
            let color = '#ef4444'; // red
            
            if (strength >= 60) {
                strengthText = 'Kuat';
                color = '#10b981'; // green
            } else if (strength >= 40) {
                strengthText = 'Cukup';
                color = '#f59e0b'; // yellow
            } else if (strength >= 20) {
                strengthText = 'Lemah';
                color = '#f97316'; // orange
            } else {
                strengthText = 'Sangat Lemah';
                color = '#ef4444'; // red
            }
            
            text.textContent = strengthText;
            text.className = `text-xs font-medium text-[${color}]`;
            fill.style.width = `${strength}%`;
            fill.style.backgroundColor = color;
        }
        
        // Password match checker
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirmPassword').value;
            const icon = document.getElementById('passwordMatchIcon');
            
            if (confirm.length === 0) {
                icon.className = 'material-icons text-transparent';
                return true;
            }
            
            if (password === confirm && password.length >= 6) {
                icon.className = 'material-icons text-emerald-500';
                icon.textContent = 'check_circle';
                return true;
            } else {
                icon.className = 'material-icons text-red-500';
                icon.textContent = 'error';
                return false;
            }
        }
        
        // Event listeners for password fields
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });
        
        document.getElementById('confirmPassword').addEventListener('input', checkPasswordMatch);
        
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
            
            // Validate nama
            const nama = document.getElementById('nama').value;
            if (nama.length < 3) {
                document.getElementById('namaError').textContent = 'Nama minimal 3 karakter';
                document.getElementById('namaError').classList.remove('hidden');
                document.getElementById('nama').classList.add('border-red-500');
                isValid = false;
            }
            
            // Validate username
            const username = document.getElementById('username').value;
            const usernameRegex = /^[A-Za-z0-9_]+$/;
            if (username.length < 4 || !usernameRegex.test(username)) {
                document.getElementById('usernameError').textContent = 'Username minimal 4 karakter, hanya huruf, angka, dan underscore';
                document.getElementById('usernameError').classList.remove('hidden');
                document.getElementById('username').classList.add('border-red-500');
                isValid = false;
            }
            
            // Validate email
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById('emailError').textContent = 'Format email tidak valid';
                document.getElementById('emailError').classList.remove('hidden');
                document.getElementById('email').classList.add('border-red-500');
                isValid = false;
            }
            
            // Validate password
            const password = document.getElementById('password').value;
            if (password.length < 6) {
                document.getElementById('password').classList.add('border-red-500');
                isValid = false;
            }
            
            // Validate password match
            if (!checkPasswordMatch()) {
                document.getElementById('confirmPasswordError').textContent = 'Password tidak cocok';
                document.getElementById('confirmPasswordError').classList.remove('hidden');
                document.getElementById('confirmPassword').classList.add('border-red-500');
                isValid = false;
            }
            
            // Validate terms
            if (!document.getElementById('terms').checked) {
                document.getElementById('termsError').classList.remove('hidden');
                isValid = false;
            }
            
            return isValid;
        }
        
        // Form submission handler
        document.getElementById('registerForm').addEventListener('submit', function(e) {
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
            this.submit();
        });
        
        // Auto redirect on success
        <?php if ($success): ?>
        setTimeout(() => {
            const progress = document.getElementById('successProgress');
            progress.style.width = '100%';
        }, 10);
        
        setTimeout(() => {
            window.location.href = 'login.php?success=1';
        }, 2200);
        <?php endif; ?>
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial counters
            document.getElementById('nama').dispatchEvent(new Event('input'));
            document.getElementById('username').dispatchEvent(new Event('input'));
            
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