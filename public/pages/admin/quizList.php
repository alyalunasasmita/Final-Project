<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/config/nyambung.php';
require_once ROOT_PATH . "/backend/AuthMiddleware.php";
require_once ROOT_PATH . '/backend/service/quizAdminService.php';
require_once ROOT_PATH . "/backend/subMateri.php";
require_once ROOT_PATH . "/backend/materi.php";

use App\AuthMiddleware;
AuthMiddleware::authAdmin();
use App\Service\QuizAdminService;
use App\submateri\Submateri;
use App\materi\Materi;

$submateriId = (int)($_GET['submateri_id'] ?? 0);
if ($submateriId <= 0) { die("submateri_id invalid"); }

$subModel = new Submateri();
$materiModel = new Materi();

$sub = $subModel->lihatSubmateriById($submateriId);

$namaSubmateri = $sub['nama_subMateri'] ?? '-';
$materiId = $sub['materi_id_materi'] ?? 0;

$materi = $materiModel->getMateriById($materiId);
$namaMateri = $materi['data']['nama_materi'] ?? '-';

$svc = new QuizAdminService();
$rows = $svc->listQuestionsBySubmateri($submateriId);

require_once PUBLIC_PATH . '/partials/header.php';
?>

<body class="min-h-screen bg-gray-50">
  <!-- Top Navigation -->
  <div class="bg-white border-b border-gray-200 px-4 py-4 sm:px-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between">
      <div class="mb-4 sm:mb-0">
        <h1 class="text-2xl font-bold text-gray-800">
          <i class="fas fa-question-circle text-blue-600 mr-2"></i>
          Kelola Soal Quiz
        </h1>
        <div class="flex items-center mt-2 text-sm text-gray-600">
          <a href="lihatSubmateri.php?id=<?= (int)$materiId ?>" class="text-blue-600 hover:text-blue-800 hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Submateri
          </a>
          <span class="mx-2">•</span>
          <span>ID: <span class="font-mono bg-gray-100 px-2 py-1 rounded"><?= $submateriId ?></span></span>
        </div>
      </div>
      
      <a href="quizForm.php?submateri_id=<?= $submateriId ?>"
         class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
        <i class="fas fa-plus-circle mr-2"></i>
        + Tambah Soal
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="p-4 sm:p-6">
    <div class="max-w-7xl mx-auto">
      <!-- Info Panel -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h2 class="text-lg font-bold text-gray-800 mb-3">Informasi Materi</h2>
            <div class="space-y-3">
              <div class="flex items-start">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                  <i class="fas fa-book text-blue-600"></i>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Materi</p>
                  <p class="font-medium text-gray-800"><?= htmlspecialchars($namaMateri) ?></p>
                </div>
              </div>
              
              <div class="flex items-start">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                  <i class="fas fa-file-alt text-green-600"></i>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Submateri</p>
                  <p class="font-medium text-gray-800"><?= htmlspecialchars($namaSubmateri) ?></p>
                </div>
              </div>
            </div>
          </div>
          
          <div>
            <h2 class="text-lg font-bold text-gray-800 mb-3">Statistik Soal</h2>
            <div class="grid grid-cols-2 gap-4">
              <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Total Soal</p>
                <p class="text-2xl font-bold text-gray-800"><?= count($rows) ?></p>
              </div>
              
              <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Soal Aktif</p>
                <p class="text-2xl font-bold text-green-600">
                  <?= count(array_filter($rows, fn($r) => (int)$r['is_active'] === 1)) ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Questions Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <?php if (!$rows): ?>
          <!-- Empty State -->
          <div class="p-8 text-center">
            <div class="mb-4">
              <i class="fas fa-inbox text-gray-300 text-5xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-700 mb-2">Belum ada soal</h3>
            <p class="text-gray-500 mb-6">Mulai dengan menambahkan soal pertama Anda</p>
            <a href="quizForm.php?submateri_id=<?= $submateriId ?>"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
              <i class="fas fa-plus mr-2"></i>
              Tambah Soal Pertama
            </a>
          </div>
        <?php else: ?>
          <!-- Desktop Table -->
          <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="text-left p-4 font-medium text-gray-700">ID</th>
                  <th class="text-left p-4 font-medium text-gray-700">Pertanyaan</th>
                  <th class="text-left p-4 font-medium text-gray-700">Status</th>
                  <th class="text-left p-4 font-medium text-gray-700">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $r): ?>
                  <tr class="border-t border-gray-200 hover:bg-gray-50">
                    <td class="p-4 font-mono text-sm text-gray-600"><?= (int)$r['id'] ?></td>
                    <td class="p-4">
                      <div class="font-medium text-gray-800 mb-1">
                        <?= htmlspecialchars(mb_strimwidth($r['question'], 0, 80, '...')) ?>
                      </div>
                      <?php if ($r['explanation']): ?>
                        <div class="text-xs text-gray-500 mt-1">
                          <i class="fas fa-comment mr-1"></i>
                          <?= htmlspecialchars(mb_strimwidth($r['explanation'], 0, 50, '...')) ?>
                        </div>
                      <?php endif; ?>
                    </td>
                    <td class="p-4">
                      <?php if ((int)$r['is_active'] === 1): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                          <i class="fas fa-check-circle mr-1.5"></i>
                          Aktif
                        </span>
                      <?php else: ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                          <i class="fas fa-times-circle mr-1.5"></i>
                          Nonaktif
                        </span>
                      <?php endif; ?>
                    </td>
                    <td class="p-4">
                      <div class="flex flex-wrap gap-2">
                        <a href="quizForm.php?submateri_id=<?= $submateriId ?>&soal_id=<?= (int)$r['id'] ?>"
                           class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-sm transition-colors">
                          <i class="fas fa-edit mr-1.5"></i>
                          Edit
                        </a>
                        
                        <a href="quizToggle.php?submateri_id=<?= $submateriId ?>&soal_id=<?= (int)$r['id'] ?>"
                           class="inline-flex items-center px-3 py-1.5 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 rounded-lg text-sm transition-colors">
                          <i class="fas fa-toggle-on mr-1.5"></i>
                          Toggle
                        </a>
                        
                        <a onclick="return confirm('Apakah Anda yakin ingin menghapus soal ini?')"
                           href="quizDelete.php?submateri_id=<?= $submateriId ?>&soal_id=<?= (int)$r['id'] ?>"
                           class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-sm transition-colors">
                          <i class="fas fa-trash-alt mr-1.5"></i>
                          Hapus
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          
          <!-- Mobile Cards -->
          <div class="md:hidden">
            <div class="divide-y divide-gray-200">
              <?php foreach ($rows as $r): ?>
                <div class="p-4">
                  <div class="flex justify-between items-start mb-3">
                    <span class="font-mono text-sm text-gray-500">#<?= (int)$r['id'] ?></span>
                    <?php if ((int)$r['is_active'] === 1): ?>
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>
                        Aktif
                      </span>
                    <?php else: ?>
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <i class="fas fa-times-circle mr-1"></i>
                        Nonaktif
                      </span>
                    <?php endif; ?>
                  </div>
                  
                  <div class="mb-3">
                    <h3 class="text-gray-800 font-medium mb-1">
                      <?= htmlspecialchars(mb_strimwidth($r['question'], 0, 120, '...')) ?>
                    </h3>
                    <?php if ($r['explanation']): ?>
                      <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-comment mr-1"></i>
                        <?= htmlspecialchars(mb_strimwidth($r['explanation'], 0, 80, '...')) ?>
                      </p>
                    <?php endif; ?>
                  </div>
                  
                  <div class="flex flex-wrap gap-2">
                    <a href="quizForm.php?submateri_id=<?= $submateriId ?>&soal_id=<?= (int)$r['id'] ?>"
                       class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-sm flex-1 justify-center">
                      <i class="fas fa-edit mr-1.5"></i>
                      Edit
                    </a>
                    
                    <a href="quizToggle.php?submateri_id=<?= $submateriId ?>&soal_id=<?= (int)$r['id'] ?>"
                       class="inline-flex items-center px-3 py-1.5 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 rounded-lg text-sm flex-1 justify-center">
                      <i class="fas fa-toggle-on mr-1.5"></i>
                      Toggle
                    </a>
                    
                    <a onclick="return confirm('Apakah Anda yakin ingin menghapus soal ini?')"
                       href="quizDelete.php?submateri_id=<?= $submateriId ?>&soal_id=<?= (int)$r['id'] ?>"
                       class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-sm flex-1 justify-center">
                      <i class="fas fa-trash-alt mr-1.5"></i>
                      Hapus
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Footer -->
      <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-600">
          <i class="fas fa-info-circle mr-1"></i>
          Menampilkan <?= count($rows) ?> soal
        </div>
        
        <div class="flex items-center space-x-3">
          <a href="lihatSubmateri.php?id=<?= (int)$materiId ?>"
             class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Submateri
          </a>
          
          <?php if ($rows): ?>
            <a href="quizForm.php?submateri_id=<?= $submateriId ?>"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
              <i class="fas fa-plus mr-2"></i>
              Tambah Soal Lain
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Add smooth fade-in animation
      const tableRows = document.querySelectorAll('tbody tr, .md\\:hidden > div > div');
      tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(10px)';
        
        setTimeout(() => {
          row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
          row.style.opacity = '1';
          row.style.transform = 'translateY(0)';
        }, index * 50);
      });
    });
  </script>
</body>
</html>