<?php
session_start();

$email = $_SESSION['reset_email'] ?? null;
$otpVerified = $_SESSION['otp_verified'] ?? false;

if (!$email || !$otpVerified) {
header("Location:forgot_pass.php?err=Silakan verifikasi OTP dulu");
exit;
}

$err = $_GET['err'] ?? '';
$msg = $_GET['msg'] ?? '';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Reset Password</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Inter (optional) -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    
    /* Custom styles untuk pesan */
    .fade-in {
      animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    /* Transisi smooth untuk elemen */
    .transition-all {
      transition-property: all;
      transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
      transition-duration: 150ms;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
  <div class="max-w-md w-full mx-auto">
    <!-- Card Container -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
      <!-- Header dengan gradien -->
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Reset Password</h1>
        <p class="text-blue-100 text-sm md:text-base">Buat password baru untuk akun Anda</p>
      </div>
      
      <!-- Card Content -->
      <div class="px-6 py-8 md:px-8 md:py-10">
        <!-- Email Info -->
        <div class="mb-6 p-3 bg-blue-50 rounded-lg border border-blue-100">
          <p class="text-xs text-gray-500 mb-1">Email terverifikasi</p>
          <p class="font-medium text-blue-700 truncate">
            <span class="inline-flex items-center">
              <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
              </svg>
              <?= htmlspecialchars($email) ?>
            </span>
          </p>
        </div>

        <!-- Messages -->
        <?php if ($msg): ?>
          <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg fade-in">
            <div class="flex items-start">
              <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
              </svg>
              <div class="text-green-800 text-sm md:text-base"><?= htmlspecialchars($msg) ?></div>
            </div>
          </div>
        <?php endif; ?>
        
        <?php if ($err): ?>
          <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg fade-in">
            <div class="flex items-start">
              <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
              </svg>
              <div class="text-red-800 text-sm md:text-base"><?= htmlspecialchars($err) ?></div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Form -->
        <form id="resetForm" method="POST">
          <!-- Password Baru -->
          <div class="mb-6">
            <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
              Password Baru
              <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input 
                type="password" 
                name="password" 
                id="password" 
                required 
                minlength="6" 
                placeholder="Masukkan minimal 6 karakter"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400"
              />
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="button" id="togglePassword1" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                  <svg id="eyeIcon1" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                </button>
              </div>
            </div>
            <p class="mt-2 text-xs text-gray-500">Password harus terdiri dari minimal 6 karakter</p>
          </div>

          <!-- Ulangi Password -->
          <div class="mb-8">
            <label for="password_confirm" class="block text-gray-700 text-sm font-medium mb-2">
              Ulangi Password
              <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input 
                type="password" 
                name="password_confirm" 
                id="password_confirm" 
                required 
                minlength="6" 
                placeholder="Ketik ulang password Anda"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400"
              />
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="button" id="togglePassword2" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                  <svg id="eyeIcon2" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Hint Message -->
          <div id="hint" class="mb-6 min-h-[24px] text-sm font-medium"></div>

          <!-- Submit Button -->
          <button 
            type="submit" 
            id="btn" 
            class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed"
          >
            <span id="buttonText">Simpan Password Baru</span>
            <span id="buttonLoader" class="hidden ml-2">
              <svg class="animate-spin h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
          </button>

          <!-- Back to Login Link -->
          <div class="mt-6 text-center">
            <a href="login.php" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
              <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
              </svg>
              Kembali ke halaman login
            </a>
          </div>
        </form>
      </div>      
    </div>
  </div>

  <!-- JavaScript (termasuk fungsi yang sudah ada) -->
  <script>
    (function(){
      const form = document.getElementById('resetForm');
      const btn  = document.getElementById('btn');
      const buttonText = document.getElementById('buttonText');
      const buttonLoader = document.getElementById('buttonLoader');
      const hint = document.getElementById('hint');
      
      // Toggle password visibility
      function setupPasswordToggle() {
        const togglePassword1 = document.getElementById('togglePassword1');
        const togglePassword2 = document.getElementById('togglePassword2');
        const password1 = document.getElementById('password');
        const password2 = document.getElementById('password_confirm');
        const eyeIcon1 = document.getElementById('eyeIcon1');
        const eyeIcon2 = document.getElementById('eyeIcon2');
        
        togglePassword1.addEventListener('click', function() {
          const type = password1.getAttribute('type') === 'password' ? 'text' : 'password';
          password1.setAttribute('type', type);
          
          // Toggle icon
          if (type === 'text') {
            eyeIcon1.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
          } else {
            eyeIcon1.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
          }
        });
        
        togglePassword2.addEventListener('click', function() {
          const type = password2.getAttribute('type') === 'password' ? 'text' : 'password';
          password2.setAttribute('type', type);
          
          // Toggle icon
          if (type === 'text') {
            eyeIcon2.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
          } else {
            eyeIcon2.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
          }
        });
      }
      
      // Panggil fungsi toggle password
      setupPasswordToggle();

      function setHint(text, isError=false){
        hint.textContent = text;
        hint.className = `min-h-[24px] text-sm font-medium fade-in ${isError ? 'text-red-600' : 'text-green-600'}`;
      }

      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const p1 = document.getElementById('password').value;
        const p2 = document.getElementById('password_confirm').value;

        // Validasi
        if (p1.length < 6) {
          setHint('Password minimal 6 karakter.', true);
          document.getElementById('password').focus();
          return;
        }
        
        if (p1 !== p2) {
          setHint('Password tidak sama. Silakan periksa kembali.', true);
          document.getElementById('password_confirm').focus();
          return;
        }

        // Tampilkan loading state
        btn.disabled = true;
        buttonText.textContent = 'Menyimpan...';
        buttonLoader.classList.remove('hidden');
        setHint('Menyimpan password baru...');

        const fd = new FormData();
        fd.append('password', p1);
        fd.append('password_confirm', p2);

        try{
          const res = await fetch('/finalProject/public/api/auth/reset_password.php', {
            method: 'POST',
            body: fd
          });

          const text = await res.text();
          let json;
          try { 
            json = JSON.parse(text); 
          } catch { 
            throw new Error('Non-JSON: ' + text.slice(0,200)); 
          }

          if (json.success) {
            setHint('Password berhasil direset! Mengarahkan ke halaman login...');
            
            // Update button state
            buttonText.textContent = 'Berhasil!';
            buttonLoader.classList.add('hidden');
            btn.classList.remove('from-blue-600', 'to-blue-700', 'hover:from-blue-700', 'hover:to-blue-800');
            btn.classList.add('from-green-600', 'to-green-700');
            
            // Redirect setelah delay
            setTimeout(() => {
              window.location.href = '/finalProject/public/frontend/login.php';
            }, 1500);
          } else {
            setHint(json.message || 'Gagal reset password. Silakan coba lagi.', true);
            
            // Reset button state
            btn.disabled = false;
            buttonText.textContent = 'Simpan Password Baru';
            buttonLoader.classList.add('hidden');
          }
        } catch(err){
          console.error(err);
          setHint('Terjadi kesalahan pada server. Silakan coba lagi nanti.', true);
          
          // Reset button state
          btn.disabled = false;
          buttonText.textContent = 'Simpan Password Baru';
          buttonLoader.classList.add('hidden');
        }
      });
      
      // Real-time validation
      const passwordInputs = document.querySelectorAll('input[type="password"]');
      passwordInputs.forEach(input => {
        input.addEventListener('input', function() {
          const p1 = document.getElementById('password').value;
          const p2 = document.getElementById('password_confirm').value;
          
          if (p1 && p2 && p1 === p2 && p1.length >= 6) {
            setHint('Password cocok dan memenuhi syarat.', false);
          } else if (p1 && p2 && p1 !== p2) {
            setHint('Password tidak cocok.', true);
          } else if (p1 && p1.length < 6) {
            setHint('Password minimal 6 karakter.', true);
          } else {
            hint.textContent = '';
          }
        });
      });
    })();
  </script>
</body>
</html>