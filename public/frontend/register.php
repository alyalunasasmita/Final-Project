<?php
session_start();
require_once "../../backend/auth.php";
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
require_once __DIR__ . '/../assets/layout/header.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - StudiYou</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --pixel-primary: #8b5cf6;
            --pixel-secondary: #6366f1;
            --pixel-accent: #f0abfc;
            --pixel-light: #f8fafc;
            --pixel-dark: #1e293b;
            --pixel-gray: #64748b;
            --pixel-success: #10b981;
            --pixel-error: #ef4444;
            --pixel-warning: #f59e0b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Pixel Art Background Pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(139, 92, 246, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 50% 50%, rgba(240, 171, 252, 0.03) 0%, transparent 50%);
            z-index: -1;
        }
        
        .pixel-container {
            width: 100%;
            max-width: 440px;
            position: relative;
        }
        
        .pixel-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.08),
                0 8px 16px rgba(139, 92, 246, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .pixel-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pixel-primary), var(--pixel-secondary), var(--pixel-accent));
            border-radius: 24px 24px 0 0;
        }
        
        .pixel-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .pixel-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--pixel-primary) 0%, var(--pixel-secondary) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 20px rgba(139, 92, 246, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .pixel-logo::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 30%,
                rgba(255, 255, 255, 0.1) 50%,
                transparent 70%
            );
            transform: rotate(45deg);
            animation: pixelShine 3s infinite linear;
        }
        
        @keyframes pixelShine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .pixel-logo svg {
            width: 36px;
            height: 36px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }
        
        .pixel-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--pixel-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }
        
        .pixel-subtitle {
            color: var(--pixel-gray);
            font-size: 1rem;
            font-weight: 500;
        }
        
        .pixel-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .pixel-input-group {
            position: relative;
        }
        
        .pixel-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--pixel-dark);
            margin-bottom: 0.5rem;
            padding-left: 0.25rem;
        }
        
        .pixel-input {
            width: 100%;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            background: rgba(248, 250, 252, 0.8);
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            color: var(--pixel-dark);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
            position: relative;
        }
        
        .pixel-input:focus {
            background: white;
            border-color: var(--pixel-primary);
            box-shadow: 
                0 0 0 4px rgba(139, 92, 246, 0.1),
                0 8px 16px rgba(139, 92, 246, 0.08);
            transform: translateY(-1px);
        }
        
        .pixel-input::placeholder {
            color: #94a3b8;
        }
        
        .pixel-input-icon {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--pixel-gray);
            pointer-events: none;
        }
        
        .pixel-button {
            width: 100%;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, var(--pixel-primary) 0%, var(--pixel-secondary) 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .pixel-button:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 12px 24px rgba(139, 92, 246, 0.2),
                0 6px 12px rgba(139, 92, 246, 0.1);
        }
        
        .pixel-button:active {
            transform: translateY(0);
        }
        
        .pixel-button::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 30%,
                rgba(255, 255, 255, 0.2) 50%,
                transparent 70%
            );
            transform: rotate(45deg);
            transition: transform 0.5s ease;
        }
        
        .pixel-button:hover::after {
            animation: pixelShine 1.5s infinite linear;
        }
        
        .pixel-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: var(--pixel-gray);
            font-size: 0.875rem;
        }
        
        .pixel-divider::before,
        .pixel-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .pixel-divider span {
            padding: 0 1rem;
        }
        
        .pixel-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            color: var(--pixel-gray);
            font-size: 0.9375rem;
        }
        
        .login-link {
            color: var(--pixel-primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .login-link:hover {
            color: var(--pixel-secondary);
            text-decoration: underline;
            transform: translateX(2px);
        }
        
        /* Password Strength Indicator */
        .password-strength {
            margin-top: 0.5rem;
        }
        
        .strength-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 0.25rem;
        }
        
        .strength-fill {
            height: 100%;
            width: 25%;
            background: var(--pixel-error);
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .strength-text {
            font-size: 0.75rem;
            color: var(--pixel-gray);
            display: flex;
            justify-content: space-between;
        }
        
        .strength-label {
            font-weight: 500;
        }
        
        .strength-value {
            font-weight: 600;
        }
        
        .error-border {
            border-color: var(--pixel-error) !important;
            animation: shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
            20%, 40%, 60%, 80% { transform: translateX(4px); }
        }
        
        .pixel-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .particle {
            position: absolute;
            background: linear-gradient(135deg, var(--pixel-primary), var(--pixel-accent));
            border-radius: 50%;
            opacity: 0.1;
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(-1000px) rotate(720deg); }
        }
        
        /* Success/Error Messages */
        .message-container {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .message-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .message-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .message-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .message-success .message-icon {
            background: rgba(16, 185, 129, 0.2);
        }
        
        .message-error .message-icon {
            background: rgba(239, 68, 68, 0.2);
        }
        
        .message-content h4 {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.125rem;
        }
        
        .message-success .message-content h4 {
            color: var(--pixel-success);
        }
        
        .message-error .message-content h4 {
            color: var(--pixel-error);
        }
        
        .message-content p {
            font-size: 0.75rem;
            color: var(--pixel-gray);
        }
        
        /* Responsive Design */
        @media (max-width: 640px) {
            .pixel-container {
                max-width: 100%;
                padding: 1rem;
            }
            
            .pixel-card {
                padding: 2rem 1.5rem;
                border-radius: 20px;
            }
            
            .pixel-logo {
                width: 70px;
                height: 70px;
                border-radius: 18px;
            }
            
            .pixel-title {
                font-size: 1.75rem;
            }
            
            .pixel-input {
                padding: 0.875rem 1rem;
            }
            
            .pixel-button {
                padding: 0.875rem 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .pixel-card {
                padding: 1.5rem 1.25rem;
            }
            
            .pixel-title {
                font-size: 1.5rem;
            }
            
            .pixel-logo {
                width: 60px;
                height: 60px;
                border-radius: 16px;
                margin-bottom: 1rem;
            }
            
            .pixel-logo svg {
                width: 28px;
                height: 28px;
            }
        }
        
        @media (max-width: 360px) {
            body {
                padding: 0.75rem;
            }
            
            .pixel-card {
                padding: 1.25rem 1rem;
                border-radius: 16px;
            }
            
            .pixel-form {
                gap: 1.25rem;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            }
            
            body::before {
                background-image: 
                    radial-gradient(circle at 10% 20%, rgba(139, 92, 246, 0.08) 0%, transparent 20%),
                    radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.08) 0%, transparent 20%);
            }
            
            .pixel-card {
                background: rgba(30, 41, 59, 0.9);
                border-color: rgba(255, 255, 255, 0.05);
                color: #f1f5f9;
            }
            
            .pixel-title {
                color: #f8fafc;
            }
            
            .pixel-subtitle {
                color: #cbd5e1;
            }
            
            .pixel-label {
                color: #e2e8f0;
            }
            
            .pixel-input {
                background: rgba(15, 23, 42, 0.8);
                border-color: #334155;
                color: #f1f5f9;
            }
            
            .pixel-input:focus {
                background: #1e293b;
            }
            
            .pixel-input::placeholder {
                color: #64748b;
            }
            
            .pixel-footer {
                border-top-color: #334155;
                color: #94a3b8;
            }
            
            .strength-bar {
                background: #334155;
            }
            
            .message-success {
                background: rgba(16, 185, 129, 0.15);
                border-color: rgba(16, 185, 129, 0.3);
            }
            
            .message-error {
                background: rgba(239, 68, 68, 0.15);
                border-color: rgba(239, 68, 68, 0.3);
            }
        }
        
        /* Terms link */
        .terms {
            text-align: center;
            font-size: 0.75rem;
            color: var(--pixel-gray);
            margin-top: 1rem;
        }
        
        .terms a {
            color: var(--pixel-primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .terms a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Animated Particles Background -->
    <div class="pixel-particles" id="particles"></div>
    
    <div class="pixel-container">
        <div class="pixel-card">
            <div class="pixel-header">
                <div class="pixel-logo">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h1 class="pixel-title">Buat Akun Baru</h1>
                <p class="pixel-subtitle">Bergabung dengan komunitas belajar StudiYou</p>
            </div>
            
            <!-- Success Message -->
            <?php if ($success): ?>
            <div class="message-container message-success">
                <div class="message-icon">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="message-content">
                    <h4>Registrasi Berhasil!</h4>
                    <p>Akun Anda telah dibuat. Silakan login untuk melanjutkan.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
            <div class="message-container message-error">
                <div class="message-icon">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="message-content">
                    <h4>Registrasi Gagal</h4>
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="registerForm" class="pixel-form">
                <!-- Nama Lengkap -->
                <div class="pixel-input-group">
                    <label class="pixel-label">Nama Lengkap</label>
                    <input type="text" 
                           name="nama" 
                           id="nama"
                           placeholder="Masukkan nama lengkap"
                           value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>"
                           class="pixel-input"
                           required>
                    <div class="pixel-input-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Email -->
                <div class="pixel-input-group">
                    <label class="pixel-label">Email</label>
                    <input type="email" 
                           name="email" 
                           id="email"
                           placeholder="contoh@email.com"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                           class="pixel-input"
                           required>
                    <div class="pixel-input-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Username -->
                <div class="pixel-input-group">
                    <label class="pixel-label">Username</label>
                    <input type="text" 
                           name="username" 
                           id="username"
                           placeholder="Pilih username"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                           class="pixel-input"
                           required>
                    <div class="pixel-input-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <p style="font-size: 0.75rem; color: var(--pixel-gray); margin-top: 0.25rem; padding-left: 0.25rem;">
                        Minimal 4 karakter, tanpa spasi
                    </p>
                </div>
                
                <!-- Password -->
                <div class="pixel-input-group">
                    <label class="pixel-label">Password</label>
                    <input type="password" 
                           name="password" 
                           id="password"
                           placeholder="Minimal 6 karakter"
                           class="pixel-input"
                           required>
                    <div class="pixel-input-icon" id="togglePassword">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="password-strength">
                        <div class="strength-text">
                            <span class="strength-label">Kekuatan password</span>
                            <span id="strengthValue" class="strength-value">Lemah</span>
                        </div>
                        <div class="strength-bar">
                            <div id="strengthFill" class="strength-fill"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="pixel-button">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Daftar Sekarang
                </button>
                
                <div class="pixel-divider">
                    <span>Sudah punya akun?</span>
                </div>
                
                <a href="login.php" class="login-link">
                    Masuk ke Akun Anda
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
                
                <!-- Terms and Conditions -->
                <div class="terms">
                    Dengan mendaftar, Anda menyetujui 
                    <a href="#">Ketentuan Layanan</a> 
                    dan 
                    <a href="#">Kebijakan Privasi</a>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="pixel-footer">
            © <?php echo date('Y'); ?> StudiYou. All rights reserved.
        </div>
    </div>

    <script>
        // Create animated particles
        function createParticles() {
            const container = document.getElementById('particles');
            const particleCount = window.innerWidth < 768 ? 15 : 25;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Random size
                const size = Math.random() * 20 + 10;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Random position
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                
                // Random animation delay and duration
                const delay = Math.random() * 20;
                const duration = Math.random() * 10 + 20;
                particle.style.animationDelay = `${delay}s`;
                particle.style.animationDuration = `${duration}s`;
                
                // Random opacity
                particle.style.opacity = Math.random() * 0.1 + 0.05;
                
                container.appendChild(particle);
            }
        }
        
        // Toggle Password Visibility
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Change icon
            this.innerHTML = type === 'password' 
                ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>'
                : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>';
        });
        
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
        
        passwordInput.addEventListener('input', function() {
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
            const fill = document.getElementById('strengthFill');
            fill.style.width = `${strength}%`;
            fill.style.background = color;
        });
        
        // Form Validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const nama = document.getElementById('nama').value;
            
            let isValid = true;
            
            // Clear previous error styles
            document.querySelectorAll('.error-border').forEach(el => {
                el.classList.remove('error-border');
            });
            
            // Validate nama
            if (nama.length < 3) {
                document.getElementById('nama').classList.add('error-border');
                isValid = false;
            }
            
            // Validate username
            if (username.length < 4 || /\s/.test(username)) {
                document.getElementById('username').classList.add('error-border');
                isValid = false;
            }
            
            // Validate email
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById('email').classList.add('error-border');
                isValid = false;
            }
            
            // Validate password
            if (password.length < 6) {
                document.getElementById('password').classList.add('error-border');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mendaftarkan...
            `;
            submitBtn.disabled = true;
            
            return true;
        });
        
        <?php if ($success): ?>
        // Show success message and redirect
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                Swal.fire({
                    title: '🎉 Registrasi Berhasil!',
                    text: 'Akun Anda telah dibuat. Silakan login untuk melanjutkan.',
                    icon: 'success',
                    confirmButtonText: 'Lanjut Login',
                    confirmButtonColor: '#8b5cf6',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-2xl border border-slate-200 dark:border-slate-700',
                        confirmButton: 'px-6 py-2.5 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-medium hover:from-purple-600 hover:to-indigo-700 transition-all',
                        title: 'text-slate-800 dark:text-slate-200 font-bold',
                        htmlContainer: 'text-slate-600 dark:text-slate-300'
                    },
                    buttonsStyling: false,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login.php?success=1';
                    }
                });
            }, 500);
        });
        <?php endif; ?>

        <?php if (!empty($error)): ?>
        // Show error message
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                Swal.fire({
                    title: '⚠️ Registrasi Gagal',
                    text: '<?php echo addslashes($error); ?>',
                    icon: 'error',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#8b5cf6',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-2xl border border-slate-200 dark:border-slate-700',
                        confirmButton: 'px-6 py-2.5 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-medium hover:from-purple-600 hover:to-indigo-700 transition-all',
                        title: 'text-slate-800 dark:text-slate-200 font-bold',
                        htmlContainer: 'text-slate-600 dark:text-slate-300'
                    },
                    buttonsStyling: false
                });
            }, 500);
        });
        <?php endif; ?>

        // Clear error on focus
        document.querySelectorAll('.pixel-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.classList.remove('error-border');
            });
        });

        // Initialize particles on load
        document.addEventListener('DOMContentLoaded', createParticles);
        
        // Recreate particles on resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                document.getElementById('particles').innerHTML = '';
                createParticles();
            }, 250);
        });
    </script>
</body>
</html>