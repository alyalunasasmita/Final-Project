<?php
session_start();

require_once __DIR__ . '/../../backend/auth.php';
require_once __DIR__ . '/../../backend/AuthMiddleware.php';

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

require_once __DIR__ . '/../assets/layout/header.php';
?>


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
        
        .forgot-password-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 0.25rem;
        }
        
        .forgot-password {
            color: var(--pixel-primary);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .forgot-password:hover {
            color: var(--pixel-secondary);
            text-decoration: underline;
            transform: translateX(2px);
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
        
        .register-link {
            color: var(--pixel-primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .register-link:hover {
            color: var(--pixel-secondary);
            text-decoration: underline;
            transform: translateX(2px);
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <h1 class="pixel-title">Login</h1>
                <p class="pixel-subtitle">Selamat Datang Kembali di StudyYou</p>
            </div>
            
            <form method="POST" action="" id="loginForm" class="pixel-form">
                <div class="pixel-input-group">
                    <label class="pixel-label">Username</label>
                    <input type="text" 
                           name="username" 
                           id="username" 
                           placeholder="Enter your username"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                           class="pixel-input"
                           required>
                    <div class="pixel-input-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="pixel-input-group">
                    <div class="flex justify-between items-center">
                        <label class="pixel-label">Password</label>
                        <div class="forgot-password-container">
                            <a href="lupapass.php" class="forgot-password">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                Lupa Password?
                            </a>
                        </div>
                    </div>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           placeholder="Enter your password"
                           class="pixel-input"
                           required>
                    <div class="pixel-input-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>
                
                <button type="submit" class="pixel-button">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Masuk
                </button>
                
                
                <a href="register.php" class="register-link">
                    Belum Punya Akun? Daftar Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </form>
            
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
        
        // Error popup handler
        <?php if (!empty($error)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const error = '<?php echo addslashes($error); ?>'.toLowerCase();
            let title = 'Login Failed';
            let icon = 'error';
            let message = '';
            
            if (error.includes('username tidak ditemukan')) {
                title = 'Username tidak Ditemukan';
                message = 'username yang kamu masukan tidak tersedia dalam sistem.';
                document.getElementById('username').classList.add('error-border');
                icon = 'warning';
            } else if (error.includes('password salah')) {
                title = 'password Salah';
                message = 'password salah, harap coba lagi.';
                document.getElementById('password').classList.add('error-border');
                icon = 'error';
            } else {
                message = '<?php echo addslashes($error); ?>';
            }
            
            setTimeout(() => {
                Swal.fire({
                    title: title,
                    text: message,
                    icon: icon,
                    confirmButtonText: 'Okay',
                    customClass: {
                        popup: 'rounded-2xl border border-slate-200 dark:border-slate-700',
                        confirmButton: 'px-6 py-2.5 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-medium hover:from-purple-600 hover:to-indigo-700 transition-all',
                        title: 'text-slate-800 dark:text-slate-200 font-bold',
                        htmlContainer: 'text-slate-600 dark:text-slate-300'
                    },
                    buttonsStyling: false,
                    background: '#ffffff dark:bg-slate-800',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            }, 500);
        });
        <?php endif; ?>

        // Form submission handler
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = `
                <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            `;
            submitBtn.disabled = true;
            
            // Clear previous error borders
            document.querySelectorAll('.error-border').forEach(el => {
                el.classList.remove('error-border');
            });
        });

        // Input focus effects
        document.querySelectorAll('.pixel-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('input-focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('input-focused');
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