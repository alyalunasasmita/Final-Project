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
  <title>Lupa Password - Reset Password Anda</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Font Awesome untuk icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
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
    
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 flex items-center justify-center p-4 md:p-8">
  
  <!-- Main Container -->
  <div class="max-w-lg w-full">
    
    <!-- Header -->
    <div class="text-center mb-8 md:mb-12">
      <a href="/" class="inline-block mb-4">
        <div class="flex items-center justify-center space-x-2">
          <div class="w-10 h-10 rounded-full gradient-bg flex items-center justify-center">
            <i class="fas fa-lock text-white text-lg"></i>
          </div>
        </div>
      </a>
      <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Lupa Password?</h1>
      <p class="text-gray-600 max-w-md mx-auto">
        Masukkan alamat email Anda dan kami akan mengirimkan kode OTP untuk mereset password Anda.
      </p>
    </div>
    
    <!-- Card -->
    <div class="bg-white rounded-2xl card-shadow border border-gray-100 overflow-hidden">
      <!-- Card Header -->
      <div class="px-6 py-8 md:px-8 md:py-10 border-b border-gray-100">
        <div class="flex items-center mb-6">
          <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center mr-4">
            <i class="fas fa-key text-blue-600 text-xl"></i>
          </div>
          <div>
            <h2 class="text-xl font-bold text-gray-800">Reset Password</h2>
          </div>
        </div>
        
        <!-- Messages -->
        <?php if ($msg): ?>
          <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg fade-in">
            <div class="flex items-start">
              <i class="fas fa-check-circle text-green-600 mt-0.5 mr-3 flex-shrink-0"></i>
              <div class="text-green-800 text-sm md:text-base"><?= $msg ?></div>
            </div>
          </div>
        <?php endif; ?>
        
        <?php if ($err): ?>
          <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg fade-in">
            <div class="flex items-start">
              <i class="fas fa-exclamation-circle text-red-600 mt-0.5 mr-3 flex-shrink-0"></i>
              <div class="text-red-800 text-sm md:text-base"><?= $err ?></div>
            </div>
          </div>
        <?php endif; ?>
        
        <!-- Dynamic Message (for JS) -->
        <div id="msg" class="mb-6"></div>
        
        <!-- Form -->
        <form id="forgotForm" method="POST" action="" class="space-y-6">
          <!-- Email Input -->
          <div>
            <label for="email" class="block text-gray-700 text-sm font-medium mb-2">
              Alamat Email
              <span class="text-red-500">*</span>
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
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400"
              />
            </div>
            <p class="mt-2 text-xs text-gray-500">
              Pastikan email yang Anda masukkan adalah email yang terdaftar di akun Anda.
            </p>
          </div>
          
          <!-- Submit Button -->
          <div>
            <button 
              type="submit" 
              id="submitBtn"
              class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center"
            >
              <span id="buttonText">Kirim Kode OTP</span>
              <span id="buttonLoader" class="hidden ml-2">
                <i class="fas fa-spinner fa-spin"></i>
              </span>
            </button>
          </div>
        </form>
      </div>
      
      <!-- Card Footer -->
      <div class="px-6 py-6 md:px-8 md:py-8 bg-gray-50">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
          <div class="text-center md:text-left">
            <p class="text-sm text-gray-600">
              <i class="fas fa-info-circle text-blue-500 mr-1"></i>
              Kode OTP akan dikirim ke email Anda dan berlaku selama 10 menit.
            </p>
          </div>
          <div>
            <a href="/frontend/login.php" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors inline-flex items-center">
              <i class="fas fa-arrow-left mr-2"></i>
              Kembali ke Login
            </a>
          </div>
        </div>
      </div>
    </div>
    

  <!-- JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('forgotForm');
      const msgEl = document.getElementById('msg');
      const emailInput = document.getElementById('email');
      const submitBtn = document.getElementById('submitBtn');
      const buttonText = document.getElementById('buttonText');
      const buttonLoader = document.getElementById('buttonLoader');
      
      // Clear dynamic message when user starts typing
      emailInput.addEventListener('input', function() {
        if (msgEl.textContent) {
          msgEl.textContent = '';
          msgEl.className = '';
        }
      });
      
      form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validasi email pattern
        if (!emailInput.checkValidity()) {
          showMessage('Format email tidak valid. Contoh: nama@email.com', 'error');
          emailInput.focus();
          return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        buttonText.textContent = 'Mengirim OTP...';
        buttonLoader.classList.remove('hidden');
        
        showMessage('Mengirim kode OTP ke email Anda...', 'info');
        
        try {
          const res = await fetch('/api/auth/forgot_password.php', {
            method: 'POST',
            body: new FormData(this)
          });
          
          if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
          }
          
          const json = await res.json();
          
          if (json.success) {
            showMessage('Kode OTP berhasil dikirim! Silakan periksa email Anda.', 'success');
            
            // Update button state
            buttonText.textContent = 'OTP Terkirim!';
            buttonLoader.classList.add('hidden');
            submitBtn.classList.remove('from-blue-600', 'to-indigo-600', 'hover:from-blue-700', 'hover:to-indigo-700');
            submitBtn.classList.add('from-green-600', 'to-green-700', 'hover:from-green-700', 'hover:to-green-800');
            
            // Simpan email untuk halaman verifikasi
            sessionStorage.setItem('reset_email', emailInput.value);
            
            // Redirect setelah 2 detik
            setTimeout(() => {
              window.location.href = '/frontend/verify.php';
            }, 2000);
          } else {
            showMessage(json.message || 'Gagal mengirim OTP. Silakan coba lagi.', 'error');
            
            // Reset button state
            submitBtn.disabled = false;
            buttonText.textContent = 'Kirim Kode OTP';
            buttonLoader.classList.add('hidden');
          }
        } catch (error) {
          console.error('Error:', error);
          showMessage('Terjadi kesalahan koneksi. Periksa koneksi internet Anda dan coba lagi.', 'error');
          
          // Reset button state
          submitBtn.disabled = false;
          buttonText.textContent = 'Kirim Kode OTP';
          buttonLoader.classList.add('hidden');
        }
      });
      
      // Function to show messages
      function showMessage(text, type) {
        msgEl.textContent = text;
        msgEl.className = 'fade-in p-4 rounded-lg';
        
        switch(type) {
          case 'success':
            msgEl.classList.add('bg-green-50', 'border', 'border-green-200', 'text-green-800');
            break;
          case 'error':
            msgEl.classList.add('bg-red-50', 'border', 'border-red-200', 'text-red-800');
            break;
          case 'info':
            msgEl.classList.add('bg-blue-50', 'border', 'border-blue-200', 'text-blue-800');
            break;
          default:
            msgEl.classList.add('bg-gray-50', 'border', 'border-gray-200', 'text-gray-800');
        }
      }
      
      // Pre-fill email if available in sessionStorage
      const savedEmail = sessionStorage.getItem('reset_email');
      if (savedEmail && !emailInput.value) {
        emailInput.value = savedEmail;
      }
    });
  </script>
</body>
</html>