<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/config/nyambung.php';
require_once ROOT_PATH . "/backend/AuthMiddleware.php";
require_once ROOT_PATH . '/backend/service/quizAdminService.php';
require_once ROOT_PATH . "/backend/subMateri.php";

use App\submateri\Submateri;
use App\AuthMiddleware;
AuthMiddleware::authAdmin();
use App\Service\QuizAdminService;

$submateriId = (int)($_GET['submateri_id'] ?? 0);
$soalId = (int)($_GET['soal_id'] ?? 0);
if ($submateriId <= 0) { echo "submateri_id invalid"; exit; }

$subModel = new Submateri();
$svc = new QuizAdminService();

$sub = $subModel->lihatSubmateriById($submateriId);
$namaSubmateri = $sub['nama_subMateri'] ?? '-';

$edit = null;
$options = ["", "", "", ""];
$correctIndex = 1;

if ($soalId > 0) {
  $edit = $svc->getQuestionWithOptions($soalId);
  if ($edit) {
    // Map options to 4 slots
    for ($i=0; $i<4; $i++) {
      $options[$i] = $edit["options"][$i]["option_text"] ?? "";
      if (!empty($edit["options"][$i]["is_correct"])) $correctIndex = $i+1;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title><?= $soalId>0 ? 'Edit' : 'Tambah' ?> Soal</title>
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
    
    .textarea-content {
      min-height: 120px;
      padding: 12px;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      background: white;
      font-family: 'Inter', sans-serif;
      font-size: 14px;
      line-height: 1.6;
      resize: vertical;
      transition: all 0.2s;
    }
    
    .textarea-content:focus {
      outline: 2px solid var(--primary);
      outline-offset: -1px;
      border-color: var(--primary);
    }
    
    .input-field {
      padding: 10px 12px;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.2s;
    }
    
    .input-field:focus {
      outline: 2px solid var(--primary);
      outline-offset: -1px;
      border-color: var(--primary);
    }
    
    .option-radio:checked + .option-label {
      background-color: #EFF6FF;
      border-color: var(--primary);
    }
    
    .option-label {
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      padding: 10px;
      cursor: pointer;
      transition: all 0.2s;
    }
    
    .option-label:hover {
      border-color: var(--secondary);
      background-color: #F8FAFC;
    }
    
    @media (max-width: 768px) {
      .textarea-content {
        min-height: 100px;
        padding: 10px;
        font-size: 13px;
      }
      
      .input-field {
        padding: 8px 10px;
        font-size: 13px;
      }
    }
    
    @media (max-width: 480px) {
      .textarea-content {
        min-height: 80px;
        padding: 8px;
        font-size: 12px;
      }
      
      .input-field {
        padding: 6px 8px;
        font-size: 12px;
      }
    }
  </style>
</head>
<body class="min-h-screen">
  <!-- Main Container -->
  <div class="flex min-h-screen">
    
    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col">
      <!-- Header -->
      <div class="bg-white border-b border-gray-200 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between">
          <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
              <i class="fas <?= $soalId>0 ? 'fa-edit' : 'fa-plus-circle' ?> text-primary mr-2"></i>
              <?= $soalId>0 ? 'Edit' : 'Tambah' ?> Soal Quiz
            </h1>
            <p class="text-gray-600 text-sm sm:text-base mt-1">
              Submateri: <span class="font-mono bg-gray-100 px-2 py-1 rounded"><?= htmlspecialchars($namaSubmateri) ?></span>
            </p>
          </div>
          <div>
            <a href="quizList.php?submateri_id=<?= $submateriId ?>" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors text-sm sm:text-base">
              <i class="fas fa-arrow-left mr-2"></i>
              Kembali ke Daftar
            </a>
          </div>
        </div>
      </div>
      
      <!-- Main Content -->
      <div class="flex-1 p-4 sm:p-6 lg:p-8">
        <div class="max-w-4xl mx-auto">
          <div class="bg-white rounded-xl card-shadow border border-gray-200 overflow-hidden">
            <div class="px-4 py-6 sm:px-6 sm:py-8 md:px-8 md:py-10">
              <form method="POST" action="quizService.php" id="quizForm" class="space-y-6">
                <input type="hidden" name="submateri_id" value="<?= $submateriId ?>">
                <input type="hidden" name="soal_id" value="<?= $soalId ?>">
                
                <!-- Question -->
                <div>
                  <label class="block text-gray-700 text-sm sm:text-base font-medium mb-2 sm:mb-3">
                    <i class="fas fa-question-circle text-gray-500 mr-1"></i>
                    Pertanyaan
                    <span class="text-red-500">*</span>
                  </label>
                  <textarea 
                    name="question" 
                    required 
                    class="w-full textarea-content"
                    placeholder="Tulis pertanyaan di sini..."
                    oninput="autoResizeTextarea(this)"
                  ><?= htmlspecialchars($edit["question"] ?? "") ?></textarea>
                  <p class="mt-1 sm:mt-2 text-xs text-gray-500">
                    Buat pertanyaan yang jelas dan mudah dipahami
                  </p>
                </div>
                
                <!-- Options -->
                <div>
                  <label class="block text-gray-700 text-sm sm:text-base font-medium mb-2 sm:mb-3">
                    <i class="fas fa-list-ol text-gray-500 mr-1"></i>
                    Pilihan Jawaban
                    <span class="text-red-500">*</span>
                    <span class="text-sm font-normal text-gray-500 ml-2">(pilih satu jawaban yang benar)</span>
                  </label>
                  
                  <div class="space-y-3 sm:space-y-4">
                    <?php for ($i=1; $i<=4; $i++): ?>
                      <div class="flex items-start gap-3">
                        <div class="flex items-center h-12">
                          <input 
                            type="radio" 
                            name="correct_index" 
                            value="<?= $i ?>" 
                            required 
                            id="option_<?= $i ?>"
                            class="hidden option-radio"
                            <?= ($correctIndex===$i?'checked':'') ?>
                          >
                          <label 
                            for="option_<?= $i ?>" 
                            class="option-label flex items-center justify-center w-8 h-8 rounded-full border-2 border-gray-300 cursor-pointer transition-all hover:border-primary"
                          >
                            <span class="text-sm font-medium text-gray-700"><?= chr(64 + $i) ?></span>
                          </label>
                        </div>
                        <div class="flex-1">
                          <input 
                            type="text" 
                            name="options[]" 
                            required
                            class="w-full input-field"
                            placeholder="Teks pilihan <?= $i ?>"
                            value="<?= htmlspecialchars($options[$i-1]) ?>"
                            maxlength="500"
                          >
                        </div>
                      </div>
                    <?php endfor; ?>
                  </div>
                  
                  <p class="mt-2 sm:mt-3 text-xs text-gray-500">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Klik huruf di samping untuk menandai jawaban yang benar
                  </p>
                </div>
                
                <!-- Explanation -->
                <div>
                  <label class="block text-gray-700 text-sm sm:text-base font-medium mb-2 sm:mb-3">
                    <i class="fas fa-comment-dots text-gray-500 mr-1"></i>
                    Pembahasan (Opsional)
                  </label>
                  <textarea 
                    name="explanation" 
                    class="w-full textarea-content"
                    placeholder="Tambahkan pembahasan singkat untuk menjelaskan jawaban yang benar..."
                    oninput="autoResizeTextarea(this)"
                  ><?= htmlspecialchars($edit["explanation"] ?? "") ?></textarea>
                  <p class="mt-1 sm:mt-2 text-xs text-gray-500">
                    Pembahasan akan ditampilkan setelah user menjawab soal
                  </p>
                </div>
                
                <!-- Active Status -->
                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                  <input 
                    type="checkbox" 
                    name="is_active" 
                    id="is_active"
                    class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary focus:ring-2"
                    <?= (!isset($edit) || (($edit["is_active"] ?? 1)==1)) ? 'checked' : '' ?>
                  >
                  <label for="is_active" class="ml-3 text-sm sm:text-base font-medium text-gray-700 cursor-pointer">
                    <i class="fas fa-toggle-on mr-2 text-primary"></i>
                    Aktifkan Soal
                  </label>
                  <span class="ml-auto text-xs text-gray-500">
                    Soal aktif akan ditampilkan kepada pengguna
                  </span>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t border-gray-200">
                  <button 
                    type="submit" 
                    id="submitBtn"
                    class="flex-1 py-3 sm:py-3.5 px-4 bg-primary hover:bg-blue-700 text-white font-medium sm:font-semibold rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center text-sm sm:text-base"
                  >
                    <i class="fas fa-save mr-2"></i>
                    <span id="buttonText">Simpan Soal</span>
                    <span id="buttonLoader" class="hidden ml-2">
                      <i class="fas fa-spinner fa-spin"></i>
                    </span>
                  </button>
                  
                  <a 
                    href="quizList.php?submateri_id=<?= $submateriId ?>" 
                    class="py-3 sm:py-3.5 px-4 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center text-sm sm:text-base"
                  >
                    <i class="fas fa-times mr-2"></i>
                    Batal
                  </a>
                </div>
                
                <!-- Loading Indicator -->
                <div id="loading" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg fade-in">
                  <div class="flex items-center">
                    <i class="fas fa-spinner fa-spin text-blue-600 mr-3"></i>
                    <div>
                      <p class="text-blue-800 font-medium text-sm sm:text-base">Menyimpan soal...</p>
                      <p class="text-blue-700 text-xs mt-1">Mohon tunggu sebentar</p>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          
          <!-- Info Panel -->
          <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-lg border border-gray-200">
              <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                  <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <div>
                  <h3 class="font-medium text-gray-800">Tips Soal Baik</h3>
                  <p class="text-sm text-gray-600 mt-1">Pastikan pertanyaan jelas dan tidak ambigu</p>
                </div>
              </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg border border-gray-200">
              <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                  <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                  <h3 class="font-medium text-gray-800">Jawaban Benar</h3>
                  <p class="text-sm text-gray-600 mt-1">Pilih satu jawaban yang paling tepat</p>
                </div>
              </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg border border-gray-200">
              <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                  <i class="fas fa-graduation-cap text-purple-600"></i>
                </div>
                <div>
                  <h3 class="font-medium text-gray-800">Pembahasan</h3>
                  <p class="text-sm text-gray-600 mt-1">Tambahkan penjelasan untuk membantu pemahaman</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('quizForm');
      const submitBtn = document.getElementById('submitBtn');
      const buttonText = document.getElementById('buttonText');
      const buttonLoader = document.getElementById('buttonLoader');
      const loadingEl = document.getElementById('loading');
      const questionTextarea = document.querySelector('textarea[name="question"]');
      const optionInputs = document.querySelectorAll('input[name="options[]"]');
      const optionRadios = document.querySelectorAll('input[name="correct_index"]');
      const optionLabels = document.querySelectorAll('.option-label');
      
      // Auto-resize textarea
      function autoResizeTextarea(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
      }
      
      // Initialize auto-resize
      if (questionTextarea) {
        questionTextarea.addEventListener('input', function() {
          autoResizeTextarea(this);
        });
        setTimeout(() => autoResizeTextarea(questionTextarea), 100);
      }
      
      // Explanation textarea auto-resize
      const explanationTextarea = document.querySelector('textarea[name="explanation"]');
      if (explanationTextarea) {
        explanationTextarea.addEventListener('input', function() {
          autoResizeTextarea(this);
        });
        setTimeout(() => autoResizeTextarea(explanationTextarea), 100);
      }
      
      // Update radio button styling when selected
      optionRadios.forEach((radio, index) => {
        radio.addEventListener('change', function() {
          // Reset all labels
          optionLabels.forEach(label => {
            label.classList.remove('bg-blue-100', 'border-primary');
            label.classList.add('border-gray-300');
          });
          
          // Style selected label
          if (this.checked) {
            const label = document.querySelector(`label[for="option_${index + 1}"]`);
            if (label) {
              label.classList.add('bg-blue-100', 'border-primary');
              label.classList.remove('border-gray-300');
            }
          }
        });
        
        // Initialize styling for checked radio
        if (radio.checked) {
          const label = document.querySelector(`label[for="option_${index + 1}"]`);
          if (label) {
            label.classList.add('bg-blue-100', 'border-primary');
            label.classList.remove('border-gray-300');
          }
        }
      });
      
      // Option label click handling
      optionLabels.forEach((label, index) => {
        label.addEventListener('click', function() {
          const radioId = this.getAttribute('for');
          const radio = document.getElementById(radioId);
          if (radio) {
            radio.checked = true;
            radio.dispatchEvent(new Event('change'));
          }
        });
      });
      
      // Form submission
      form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validate form
        if (!questionTextarea.value.trim()) {
          alert('Pertanyaan harus diisi');
          questionTextarea.focus();
          return;
        }
        
        let allOptionsFilled = true;
        optionInputs.forEach((input, index) => {
          if (!input.value.trim()) {
            allOptionsFilled = false;
            if (allOptionsFilled === false) {
              input.focus();
            }
          }
        });
        
        if (!allOptionsFilled) {
          alert('Semua pilihan jawaban harus diisi');
          return;
        }
        
        let correctSelected = false;
        optionRadios.forEach(radio => {
          if (radio.checked) correctSelected = true;
        });
        
        if (!correctSelected) {
          alert('Pilih jawaban yang benar');
          return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        buttonText.textContent = 'Menyimpan...';
        buttonLoader.classList.remove('hidden');
        loadingEl.classList.remove('hidden');
        
        try {
          const formData = new FormData(this);
          
          // Use fetch API for submission
          const res = await fetch('quizService.php', { 
            method: 'POST', 
            body: formData 
          });
          
          if (res.ok) {
            // Success - redirect to list page
            buttonText.textContent = 'Berhasil!';
            buttonLoader.classList.add('hidden');
            loadingEl.innerHTML = `
              <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                <div>
                  <p class="text-green-800 font-medium text-sm sm:text-base">Soal berhasil disimpan!</p>
                  <p class="text-green-700 text-xs mt-1">Mengalihkan ke halaman daftar...</p>
                </div>
              </div>
            `;
            
            setTimeout(() => {
              window.location.href = 'quizList.php?submateri_id=<?= $submateriId ?>';
            }, 1500);
          } else {
            throw new Error('Gagal menyimpan soal');
          }
        } catch (error) {
          console.error('Error:', error);
          
          // Reset button state
          submitBtn.disabled = false;
          buttonText.textContent = 'Simpan Soal';
          buttonLoader.classList.add('hidden');
          
          // Show error message
          loadingEl.innerHTML = `
            <div class="flex items-center">
              <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
              <div>
                <p class="text-red-800 font-medium text-sm sm:text-base">Gagal menyimpan soal</p>
                <p class="text-red-700 text-xs mt-1">Silakan coba lagi atau periksa koneksi internet</p>
              </div>
            </div>
          `;
          
          // Auto hide error after 5 seconds
          setTimeout(() => {
            loadingEl.classList.add('hidden');
          }, 5000);
        }
      });
      
      // Handle window resize
      window.addEventListener('resize', function() {
        if (questionTextarea) autoResizeTextarea(questionTextarea);
        if (explanationTextarea) autoResizeTextarea(explanationTextarea);
      });
      
      // Keyboard shortcuts
      document.addEventListener('keydown', function(e) {
        // Ctrl+S or Cmd+S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
          e.preventDefault();
          form.dispatchEvent(new Event('submit'));
        }
        
        // Esc to cancel
        if (e.key === 'Escape') {
          if (confirm('Batalkan perubahan?')) {
            window.location.href = 'quizList.php?submateri_id=<?= $submateriId ?>';
          }
        }
      });
      
      // Warn before leaving if changes were made
      let originalData = {
        question: questionTextarea.value,
        options: Array.from(optionInputs).map(input => input.value),
        correct: Array.from(optionRadios).findIndex(radio => radio.checked) + 1,
        explanation: explanationTextarea ? explanationTextarea.value : ''
      };
      
      let hasUnsavedChanges = false;
      
      function checkForChanges() {
        const currentOptions = Array.from(optionInputs).map(input => input.value);
        const currentCorrect = Array.from(optionRadios).findIndex(radio => radio.checked) + 1;
        
        hasUnsavedChanges = 
          questionTextarea.value !== originalData.question ||
          JSON.stringify(currentOptions) !== JSON.stringify(originalData.options) ||
          currentCorrect !== originalData.correct ||
          (explanationTextarea && explanationTextarea.value !== originalData.explanation);
      }
      
      questionTextarea.addEventListener('input', checkForChanges);
      optionInputs.forEach(input => input.addEventListener('input', checkForChanges));
      optionRadios.forEach(radio => radio.addEventListener('change', checkForChanges));
      if (explanationTextarea) {
        explanationTextarea.addEventListener('input', checkForChanges);
      }
      
      window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges) {
          e.preventDefault();
          e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?';
          return e.returnValue;
        }
      });
      
      // Clean up beforeunload event after form submit
      form.addEventListener('submit', function() {
        hasUnsavedChanges = false;
      });
    });
  </script>
</body>
</html>