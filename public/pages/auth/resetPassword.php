<?php
session_start();

$email = $_SESSION['reset_email'] ?? null;
$otpVerified = $_SESSION['otp_verified'] ?? false;

if (!$email || !$otpVerified) {
    header("Location: forgot_pass.php?err=Silakan verifikasi OTP dulu");
    exit;
}

$err = $_GET['err'] ?? '';
$msg = $_GET['msg'] ?? '';
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
    <title>Reset Password - StudyYou</title>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white shadow-sm border-b border-gray-200 p-4">
        <div class="flex items-center justify-center">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-accent flex items-center justify-center mr-3">
                <i class="fas fa-lock text-white"></i>
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
                <h1 class="text-4xl font-bold text-gray-900">Reset Password</h1>
                <p class="text-gray-600 mt-2 text-lg">Buat password baru untuk akun Anda</p>
            </div>

            <!-- Email Info -->
            <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <p class="text-sm text-gray-500 mb-1">Email terverifikasi</p>
                <p class="font-medium text-blue-700 truncate flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    <?= htmlspecialchars($email) ?>
                </p>
            </div>

            <!-- Reset Password Card -->
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-200">
                <!-- Messages -->
                <?php if ($msg): ?>
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <div>
                            <p class="text-sm text-green-800 font-medium">
                                <?= htmlspecialchars($msg) ?>
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
                                <?= htmlspecialchars($err) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Reset Password Form -->
                <form id="resetForm" method="POST" class="space-y-6">
                    <!-- Password Baru -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                required 
                                minlength="6" 
                                placeholder="Masukkan minimal 6 karakter"
                                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition text-base"
                            />
                            <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Minimal 6 karakter</p>
                    </div>

                    <!-- Ulangi Password -->
                    <div>
                        <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">
                            Ulangi Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password_confirm" 
                                id="password_confirm" 
                                required 
                                minlength="6" 
                                placeholder="Ketik ulang password Anda"
                                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition text-base"
                            />
                            <button type="button" onclick="togglePassword('password_confirm')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div id="passwordStrength" class="hidden">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-500">Kekuatan password:</span>
                            <span id="strengthValue" class="text-xs font-medium">Lemah</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div id="strengthFill" class="h-full w-0 transition-all duration-300"></div>
                        </div>
                    </div>

                    <!-- Validation Message -->
                    <div id="hint" class="min-h-[24px] text-sm"></div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                id="submitBtn"
                                class="w-full py-3 px-4 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition text-base">
                            <span id="submitText">Simpan Password Baru</span>
                        </button>
                    </div>
                </form>

                <!-- Back to Login Link -->
                <div class="mt-6 text-center">
                    <a href="login.php" class="text-primary font-medium hover:text-primary/80 transition inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke halaman login
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-sm text-gray-500">
                <p>Pastikan password Anda kuat dan mudah diingat</p>
            </div>
        </div>
    </div>

    <!-- Bottom spacing untuk mobile -->
    <div class="h-4 lg:hidden"></div>

    <script>
        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
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
        
        // Update hint message
        function setHint(text, isError = false) {
            const hint = document.getElementById('hint');
            hint.textContent = text;
            hint.className = `min-h-[24px] text-sm ${isError ? 'text-red-600' : 'text-green-600'}`;
        }
        
        // Form validation and submission
        document.getElementById('resetForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            
            // Validation
            if (password.length < 6) {
                setHint('Password minimal 6 karakter', true);
                document.getElementById('password').focus();
                return;
            }
            
            if (password !== passwordConfirm) {
                setHint('Password tidak cocok', true);
                document.getElementById('password_confirm').focus();
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Memproses...';
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            
            // Prepare form data
            const formData = new FormData();
            formData.append('password', password);
            formData.append('password_confirm', passwordConfirm);
            
            try {
                const response = await fetch('/finalProject/public/api/auth/reset_password.php', {
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
                    setHint('Password berhasil direset! Mengarahkan ke login...', false);
                    
                    // Update button state
                    submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Berhasil!';
                    submitBtn.classList.remove('bg-primary');
                    submitBtn.classList.add('bg-green-600');
                    
                    // Redirect to login page
                    setTimeout(() => {
                        window.location.href = 'login.php?success=1';
                    }, 1500);
                } else {
                    setHint(result.message || 'Gagal reset password', true);
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    submitText.textContent = 'Simpan Password Baru';
                    submitBtn.innerHTML = '<span id="submitText">Simpan Password Baru</span>';
                    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                }
            } catch (error) {
                console.error('Error:', error);
                setHint('Terjadi kesalahan. Silakan coba lagi.', true);
                
                // Reset button state
                submitBtn.disabled = false;
                submitText.textContent = 'Simpan Password Baru';
                submitBtn.innerHTML = '<span id="submitText">Simpan Password Baru</span>';
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });
        
        // Real-time password validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const confirmPassword = document.getElementById('password_confirm').value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            // Show/hide strength indicator
            if (password.length > 0) {
                strengthDiv.classList.remove('hidden');
                
                // Calculate strength
                const strength = checkPasswordStrength(password);
                
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
                document.getElementById('strengthFill').style.width = `${strength}%`;
                document.getElementById('strengthFill').style.backgroundColor = color;
            } else {
                strengthDiv.classList.add('hidden');
            }
            
            // Check if passwords match
            if (confirmPassword && password !== confirmPassword) {
                setHint('Password tidak cocok', true);
            } else if (confirmPassword && password === confirmPassword && password.length >= 6) {
                setHint('Password cocok', false);
            } else {
                setHint('');
            }
        });
        
        document.getElementById('password_confirm').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                setHint('Password tidak cocok', true);
            } else if (confirmPassword && password === confirmPassword && password.length >= 6) {
                setHint('Password cocok', false);
            } else {
                setHint('');
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