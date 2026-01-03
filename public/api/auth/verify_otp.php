<?php
session_start();
require_once __DIR__ . '/../../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['reset_email'])) {
    $_SESSION['error'] = 'Sesi habis atau belum memulai reset password. Silakan ulangi proses lupa password.';
    header("Location: forgot_pass.php");
    exit;
}

$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token_otp'] = $csrf_token;

$err = isset($_GET['err']) ? htmlspecialchars($_GET['err'], ENT_QUOTES, 'UTF-8') : '';
$msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8') : '';

$otp_expired = false;
if (isset($_SESSION['otp_expiry']) && time() > $_SESSION['otp_expiry']) {
    $otp_expired = true;
    $err = 'Kode OTP sudah kadaluarsa. Silakan minta kode baru.';
}

$email = $_SESSION['reset_email'];

require_once PUBLIC_PATH . '/partials/header.php';
?>

  <title>Verifikasi OTP - Konfirmasi Kode Keamanan</title>
  <style>
    :root {
      --primary: #2563EB;
      --secondary: #93C5FD;
      --accent: #10B981;
      --bg: #F9FAFB;
      --text: #1F2937;
    }
    
    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg);
    }
    
    .fade-in {
      animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .card-shadow {
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .otp-input {
      text-align: center;
      font-weight: bold;
      letter-spacing: 0.5rem;
    }
    
    /* Custom primary color overrides */
    .btn-primary {
      background-color: var(--primary);
    }
    
    .btn-primary:hover {
      background-color: #1D4ED8;
    }
    
    .text-primary {
      color: var(--primary);
    }
    
    .border-primary {
      border-color: var(--primary);
    }
    
    .bg-primary {
      background-color: var(--primary);
    }
    
    .bg-accent {
      background-color: var(--accent);
    }
    
    .bg-secondary {
      background-color: var(--secondary);
    }
    
    /* Gradient with custom colors */
    .gradient-primary {
      background: linear-gradient(135deg, var(--primary) 0%, #1D4ED8 100%);
    }
    
    @media (max-width: 768px) {
      .otp-input {
        letter-spacing: 0.3rem;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
      }
    }
    
    @media (max-width: 480px) {
      .otp-input {
        letter-spacing: 0.2rem;
        font-size: 1.125rem !important;
      }
    }
  </style>
</head>
<body class="min-h-screen">
  
  <!-- Main Container - Responsive untuk semua ukuran -->
  <div class="flex min-h-screen">
    <!-- Sidebar untuk desktop (w-38 = 9.5rem = 152px) -->
    <div class="hidden lg:block w-38 bg-white border-r border-gray-200"></div>
    
    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col">
      <!-- Container utama yang menyesuaikan lebar -->
      <div class="flex-1 flex items-center justify-center p-4 md:p-6 lg:p-8 w-full">
        <div class="w-full max-w-lg mx-auto">
          
          <!-- Header -->
          <div class="text-center mb-6 sm:mb-8 md:mb-10 lg:mb-12 px-2">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-2">Verifikasi OTP</h1>
            <p class="text-gray-600 text-sm sm:text-base mx-auto max-w-sm sm:max-w-md">
              Masukkan kode keamanan 6-digit yang telah dikirim ke email Anda
            </p>
          </div>
          
          <!-- Card -->
          <div class="bg-white rounded-xl sm:rounded-2xl card-shadow border border-gray-200 overflow-hidden mx-2 sm:mx-0">
            <!-- Card Header -->
            <div class="px-4 py-6 sm:px-6 sm:py-8 md:px-8 md:py-10">
              
              <!-- Messages -->
              <?php if ($otp_expired): ?>
                <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-yellow-50 border border-yellow-200 rounded-lg fade-in">
                  <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-3 flex-shrink-0"></i>
                    <div class="flex-1">
                      <p class="text-yellow-800 font-medium text-sm sm:text-base">Kode OTP sudah kadaluarsa</p>
                      <p class="text-yellow-700 text-xs sm:text-sm mt-1">Silakan minta kode OTP baru untuk melanjutkan</p>
                      <a href="forgot_pass.php" class="inline-block mt-2 sm:mt-3 px-3 sm:px-4 py-1.5 sm:py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-redo mr-1.5"></i>Minta Kode Baru
                      </a>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
              
              <?php if ($msg): ?>
                <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border border-green-200 rounded-lg fade-in">
                  <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 mt-0.5 mr-3 flex-shrink-0"></i>
                    <div class="text-green-800 text-xs sm:text-sm md:text-base"><?= $msg ?></div>
                  </div>
                </div>
              <?php endif; ?>
              
              <?php if ($err): ?>
                <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-red-50 border border-red-200 rounded-lg fade-in">
                  <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5 mr-3 flex-shrink-0"></i>
                    <div class="text-red-800 text-xs sm:text-sm md:text-base"><?= $err ?></div>
                  </div>
                </div>
              <?php endif; ?>
              
              <!-- OTP Form -->
              <form method="POST" action="/api/auth/verify_otp.php" id="otpForm">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <!-- OTP Input -->
                <div class="mb-6 sm:mb-8">
                  <label for="otp" class="block text-gray-700 text-xs sm:text-sm font-medium mb-2 sm:mb-3">
                    Kode OTP (6 digit angka)
                    <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <i class="fas fa-key text-gray-400 text-sm sm:text-base"></i>
                    </div>
                    <input 
                      type="text" 
                      name="otp" 
                      id="otp" 
                      required 
                      pattern="[0-9]{6}" 
                      maxlength="6" 
                      minlength="6"
                      title="Harus 6 digit angka"
                      placeholder="123456"
                      class="w-full pl-10 pr-4 py-3 sm:py-3.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 otp-input text-lg sm:text-xl md:text-2xl"
                      <?= $otp_expired ? 'disabled' : '' ?>
                      autocomplete="one-time-code"
                      inputmode="numeric"
                    />
                  </div>
                  <p class="mt-1 sm:mt-2 text-xs text-gray-500">
                    Masukkan 6 digit angka yang Anda terima di email
                  </p>
                </div>
                
                <!-- Timer Countdown -->
                <?php if (!$otp_expired && isset($_SESSION['otp_expiry'])): 
                  $remaining_seconds = $_SESSION['otp_expiry'] - time();
                  if ($remaining_seconds > 0): ?>
                  <div class="mb-4 sm:mb-6 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                      <div class="flex items-center">
                        <i class="fas fa-clock text-blue-500 mr-2 text-sm"></i>
                        <span class="text-xs sm:text-sm text-gray-600">Kode OTP berlaku:</span>
                      </div>
                      <div id="countdown-timer" class="font-mono font-bold text-blue-700 text-sm sm:text-base">
                        <?= sprintf('%02d:%02d', floor($remaining_seconds / 60), $remaining_seconds % 60) ?>
                      </div>
                    </div>
                    <div class="h-1.5 sm:h-2 bg-gray-200 rounded-full overflow-hidden">
                      <div id="countdown-progress" class="h-full bg-blue-500 transition-all duration-1000" 
                           style="width: <?= min(100, ($remaining_seconds / 600) * 100) ?>%"></div>
                    </div>
                  </div>
                <?php endif; endif; ?>
                
                <!-- Submit Button -->
                <div class="mb-4 sm:mb-6">
                  <button 
                    type="submit" 
                    id="submitBtn"
                    class="w-full py-3 sm:py-3.5 px-4 bg-primary hover:bg-blue-700 text-white font-medium sm:font-semibold rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center disabled:opacity-70 disabled:cursor-not-allowed text-sm sm:text-base"
                    <?= $otp_expired ? 'disabled' : '' ?>
                  >
                    <span id="buttonText">Verifikasi Kode OTP</span>
                    <span id="buttonLoader" class="hidden ml-2">
                      <i class="fas fa-spinner fa-spin"></i>
                    </span>
                  </button>
                </div>
                
                <!-- Loading Indicator -->
                <div id="loading" class="hidden mb-4 sm:mb-6 p-3 sm:p-4 bg-blue-50 border border-blue-200 rounded-lg fade-in">
                  <div class="flex items-center">
                    <i class="fas fa-spinner fa-spin text-blue-600 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                    <div>
                      <p class="text-blue-800 font-medium text-xs sm:text-sm">Memverifikasi OTP...</p>
                      <p class="text-blue-700 text-xs mt-0.5 sm:mt-1">Mohon tunggu sebentar</p>
                    </div>
                  </div>
                </div>
              </form>
              
              <!-- Resend OTP Link -->
              <?php if (!$otp_expired): ?>
                <div class="text-center pt-4 sm:pt-6 border-t border-gray-100">
                  <p class="text-gray-600 text-xs sm:text-sm mb-2 sm:mb-3">Tidak menerima kode OTP?</p>
                  <a href="forgot_pass.php" class="inline-flex items-center text-primary hover:text-blue-800 font-medium transition-colors text-sm">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Kirim Ulang Kode OTP
                  </a>
                </div>
              <?php endif; ?>
            </div>
            
            <!-- Card Footer -->
            <div class="px-4 py-4 sm:px-6 sm:py-6 md:px-8 md:py-8 bg-gray-50 border-t border-gray-100">
              <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row justify-between items-center">
                <div class="text-center sm:text-left">
                  <p class="text-xs sm:text-sm text-gray-600">
                    <i class="fas fa-exclamation-circle text-blue-500 mr-1"></i>
                    Jangan berikan kode OTP kepada siapapun
                  </p>
                </div>
                <div>
                  <a href="login.php" class="text-gray-600 hover:text-gray-800 font-medium text-xs sm:text-sm transition-colors inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Login
                  </a>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Mobile Only Info -->
          <div class="lg:hidden mt-6 text-center px-4">
            <p class="text-xs text-gray-500">
              <i class="fas fa-mobile-alt mr-1"></i>
              Pastikan Anda mengakses dari perangkat yang aman
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('otpForm');
      const otpInput = document.getElementById('otp');
      const submitBtn = document.getElementById('submitBtn');
      const buttonText = document.getElementById('buttonText');
      const buttonLoader = document.getElementById('buttonLoader');
      const loadingEl = document.getElementById('loading');
      
      // Auto-focus OTP input
      if (otpInput && !otpInput.disabled) {
        setTimeout(() => otpInput.focus(), 100);
      }
      
      // Auto-tab OTP input (move to next after 1 digit)
      otpInput.addEventListener('input', function(e) {
        if (this.value.length === 6) {
          const isValid = /^\d{6}$/.test(this.value);
          if (!isValid) {
            this.setCustomValidity('Harus 6 digit angka');
            this.reportValidity();
          } else {
            this.setCustomValidity('');
          }
        }
      });
      
      // Countdown timer
      const countdownElement = document.getElementById('countdown-timer');
      const progressElement = document.getElementById('countdown-progress');
      
      if (countdownElement && progressElement) {
        let timeRemaining = <?= isset($remaining_seconds) ? $remaining_seconds : 0 ?>;
        
        if (timeRemaining > 0) {
          const countdownInterval = setInterval(function() {
            timeRemaining--;
            
            if (timeRemaining <= 0) {
              clearInterval(countdownInterval);
              countdownElement.textContent = '00:00';
              progressElement.style.width = '0%';
              
              // Disable form and show expired message
              if (otpInput) otpInput.disabled = true;
              if (submitBtn) submitBtn.disabled = true;
              
              // Redirect with expired message
              setTimeout(() => {
                window.location.href = '/frontend/verify.php?err=' + encodeURIComponent('Kode OTP sudah kadaluarsa. Silakan minta kode baru.');
              }, 1000);
            } else {
              const minutes = Math.floor(timeRemaining / 60);
              const seconds = timeRemaining % 60;
              countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
              
              // Update progress bar
              const progressPercent = (timeRemaining / 600) * 100;
              progressElement.style.width = Math.min(100, progressPercent) + '%';
            }
          }, 1000);
        }
      }
      
      // Form submission
      form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validasi client-side
        if (!otpInput.checkValidity()) {
          alert('Kode OTP harus 6 digit angka');
          otpInput.focus();
          return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        buttonText.textContent = 'Memverifikasi...';
        buttonLoader.classList.remove('hidden');
        loadingEl.classList.remove('hidden');
        
        // Prevent double submission
        let isSubmitting = false;
        
        if (isSubmitting) return;
        isSubmitting = true;
        
        try {
          const formData = new FormData(this);
          const res = await fetch(this.action, { 
            method: 'POST', 
            body: formData 
          });
          
          const text = await res.text();
          console.log('Response:', text);
          
          let jsonResponse = null;
          try { 
            jsonResponse = JSON.parse(text); 
          } catch(e) {
            console.error('Gagal parse JSON:', e, 'Response:', text);
            // Fallback handling
            alert('Terjadi kesalahan pada server. Silakan coba lagi.');
            this.submit();
            return;
          }
          
          if (jsonResponse && jsonResponse.success) {
            // Success
            buttonText.textContent = 'Berhasil!';
            buttonLoader.classList.add('hidden');
            loadingEl.innerHTML = `
              <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                <div>
                  <p class="text-green-800 font-medium text-xs sm:text-sm">OTP berhasil diverifikasi!</p>
                  <p class="text-green-700 text-xs mt-0.5 sm:mt-1">Mengalihkan ke halaman reset password...</p>
                </div>
              </div>
            `;
            
            setTimeout(() => {
              window.location.href = '/frontend/reset_password.php';
            }, 1500);
          } else {
            const errorMsg = (jsonResponse && jsonResponse.message) ? jsonResponse.message : 'Gagal verifikasi OTP';
            
            // Reset button state
            submitBtn.disabled = false;
            buttonText.textContent = 'Verifikasi Kode OTP';
            buttonLoader.classList.add('hidden');
            loadingEl.classList.add('hidden');
            
            // Redirect dengan error
            window.location.href = '/frontend/verify.php?err=' + encodeURIComponent(errorMsg);
          }
        } catch (error) {
          console.error('Fetch error:', error);
          
          // Reset button state
          submitBtn.disabled = false;
          buttonText.textContent = 'Verifikasi Kode OTP';
          buttonLoader.classList.add('hidden');
          
          // Show error message
          loadingEl.innerHTML = `
            <div class="flex items-center">
              <i class="fas fa-exclamation-triangle text-red-600 mr-2 sm:mr-3 text-sm sm:text-base"></i>
              <div>
                <p class="text-red-800 font-medium text-xs sm:text-sm">Gagal terhubung ke server</p>
                <p class="text-red-700 text-xs mt-0.5 sm:mt-1">Periksa koneksi internet Anda dan coba lagi</p>
              </div>
            </div>
          `;
          
          setTimeout(() => {
            loadingEl.classList.add('hidden');
          }, 5000);
        } finally {
          isSubmitting = false;
        }
      });
      
      if ('ontouchstart' in window) {
        otpInput.addEventListener('touchstart', function() {
          this.select();
        });
      }
      
      window.addEventListener('pageshow', function(event) {
        if (event.persisted) {

          submitBtn.disabled = false;
          buttonText.textContent = 'Verifikasi Kode OTP';
          buttonLoader.classList.add('hidden');
          loadingEl.classList.add('hidden');
        }
      });
    });
    
    window.addEventListener('resize', function() {
      const otpInput = document.getElementById('otp');
      if (otpInput && window.innerWidth < 480) {
        otpInput.style.fontSize = '1.125rem';
      }
    });
  </script>
</body>
</html>