<?php
require_once __DIR__ . "/../../config.php";
require_once ROOT_PATH . "/backend/AuthMiddleware.php";
require_once ROOT_PATH . "/backend/materi.php";

use App\AuthMiddleware;
AuthMiddleware::authAdmin();
use App\Materi\Materi;

$tambah_materi = new Materi();
$result = $tambah_materi->lihatMateriArsip();
$data = $result['data'] ?? [];

require_once PUBLIC_PATH . '/partials/header.php';
?>

  <title>Admin - Archived Courses</title>
  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
      animation: fadeIn 0.3s ease-out forwards;
    }
    
    .glass-effect {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.18);
    }
    
    .custom-scrollbar {
      scrollbar-width: thin;
      scrollbar-color: #cbd5e1 transparent;
    }
    
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
      background: transparent;
      border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: linear-gradient(to bottom, #cbd5e1, #94a3b8);
      border-radius: 10px;
    }
    
    .line-clamp-2 {
      overflow: hidden;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 2;
    }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-slate-100">

<!-- Main Container -->
<div class="p-4 md:p-6 lg:p-8 max-w-7xl mx-auto">
  
  <!-- Header Section -->
  <div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <div class="flex items-center gap-4">
        <div class="p-3 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-xl border border-cyan-200 shadow-sm">
          <span class="material-icons text-xl text-cyan-600">
            archive
          </span>
        </div>
        <div>
          <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-700 bg-clip-text text-transparent">
            Archived Courses
          </h1>
          <p class="text-gray-600 mt-1 font-light">Restore or permanently delete archived learning materials</p>
        </div>
      </div>
      
      <!-- Stats and Actions -->
      <div class="flex flex-wrap items-center gap-3">
        <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-xl shadow-sm">
          <div class="w-2 h-2 bg-cyan-400 rounded-full mr-2 animate-pulse"></div>
          <span class="text-sm font-medium text-gray-700"><?= count($data) ?> archived</span>
        </div>
        <a href="lihatMateri.php" 
           class="group inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-white to-gray-50 hover:from-gray-100 hover:to-gray-200 border border-gray-300 hover:border-gray-400 text-gray-700 font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
          <span class="material-icons text-base">arrow_back</span>
          <span>Back to Active</span>
        </a>
      </div>
    </div>
    
    <!-- Progress Bar -->
    <div class="w-full h-1 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full mb-6 overflow-hidden">
      <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-400" style="width: <?= count($data) > 0 ? '100%' : '0%' ?>"></div>
    </div>
  </div>

  <!-- Messages -->
  <?php if (!empty($_GET['ok'])): ?>
    <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl shadow-sm animate-fade-in">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-green-100 to-emerald-100">
          <span class="material-icons text-green-500">check_circle</span>
        </div>
        <div class="flex-1">
          <p class="font-medium text-green-800">Success</p>
          <p class="text-sm text-green-700 mt-1"><?= htmlspecialchars($_GET['ok']) ?></p>
        </div>
        <button onclick="this.parentElement.parentElement.remove()" class="text-green-400 hover:text-green-600">
          <span class="material-icons">close</span>
        </button>
      </div>
    </div>
  <?php endif; ?>
  
  <?php if (!empty($_GET['err'])): ?>
    <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl shadow-sm animate-fade-in">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-red-100 to-pink-100">
          <span class="material-icons text-red-500">error</span>
        </div>
        <div class="flex-1">
          <p class="font-medium text-red-800">Attention Required</p>
          <p class="text-sm text-red-700 mt-1"><?= htmlspecialchars($_GET['err']) ?></p>
        </div>
        <button onclick="this.parentElement.parentElement.remove()" class="text-red-400 hover:text-red-600">
          <span class="material-icons">close</span>
        </button>
      </div>
    </div>
  <?php endif; ?>

  <!-- Archive Content -->
  <div class="bg-gradient-to-br from-white to-gray-50/50 rounded-2xl shadow-lg border border-gray-200/60 overflow-hidden">
    
    <!-- Table Header -->
    <div class="px-6 py-4 border-b border-gray-200/60 bg-gradient-to-r from-cyan-50/30 to-blue-50/30">
      <div class="grid grid-cols-12 gap-4">
        <div class="col-span-2 lg:col-span-1">
          <span class="text-sm font-semibold text-gray-700">ID</span>
        </div>
        <div class="col-span-4 lg:col-span-3">
          <span class="text-sm font-semibold text-gray-700">Course Name</span>
        </div>
        <div class="hidden lg:col-span-4 lg:block">
          <span class="text-sm font-semibold text-gray-700">Description</span>
        </div>
        <div class="col-span-3 lg:col-span-2">
          <span class="text-sm font-semibold text-gray-700">Archived Date</span>
        </div>
        <div class="col-span-3 lg:col-span-2">
          <span class="text-sm font-semibold text-gray-700">Actions</span>
        </div>
      </div>
    </div>

    <!-- Table Body -->
    <div class="custom-scrollbar max-h-[calc(100vh-300px)] overflow-y-auto">
      <?php if (count($data) === 0): ?>
        <!-- Empty State -->
        <div class="py-16 text-center">
          <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
            <span class="material-icons text-4xl text-gray-400">
              folder_off
            </span>
          </div>
          <h3 class="text-lg font-semibold text-gray-700 mb-2">Arsip kosong</h3>
          <p class="text-gray-500 max-w-md mx-auto mb-8">
            belum ada materi yang diarsipkan. Kembali ke daftar materi aktif untuk mengelola materi .
          </p>
          <a href="lihatMateri.php" 
             class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
            <span class="material-icons">arrow_back</span>
            <span>Lihat Materi Aktif</span>
          </a>
        </div>
      <?php else: ?>
        <?php foreach ($data as $index => $row): ?>
          <div class="px-6 py-4 border-b border-gray-100 hover:bg-gray-50/50 transition-colors duration-200 animate-fade-in" 
               style="animation-delay: <?= $index * 50 ?>ms">
            <div class="grid grid-cols-12 gap-4 items-center">
              
              <!-- ID -->
              <div class="col-span-2 lg:col-span-1">
                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-gray-100 to-gray-200">
                  <span class="text-sm font-semibold text-gray-700">#<?= (int)$row['id_materi'] ?></span>
                </div>
              </div>
              
              <!-- Course Name -->
              <div class="col-span-4 lg:col-span-3">
                <h3 class="font-medium text-gray-800 line-clamp-2"><?= htmlspecialchars($row['nama_materi'] ?? 'Untitled') ?></h3>
              </div>
              
              <!-- Description (Desktop only) -->
              <div class="hidden lg:col-span-4 lg:block">
                <p class="text-sm text-gray-600 line-clamp-2">
                  <?= htmlspecialchars($row['deskripsi_materi'] ?? 'No description') ?>
                </p>
              </div>
              
              <!-- Archived Date -->
              <div class="col-span-3 lg:col-span-2">
                <div class="flex items-center gap-2">
                  <span class="material-icons text-gray-400 text-sm">
                    schedule
                  </span>
                  <span class="text-sm text-gray-600">
                    <?= !empty($row['deleted_at']) ? date('d M Y', strtotime($row['deleted_at'])) : 'Unknown' ?>
                  </span>
                </div>
              </div>
              
              <!-- Actions -->
              <div class="col-span-3 lg:col-span-2">
                <div class="flex items-center gap-2">
                  
                  <!-- Restore Button -->
                  <form action="restoreMateri.php" method="POST" class="flex-1">
                    <input type="hidden" name="action" value="restore">
                    <input type="hidden" name="id_materi" value="<?= (int)$row['id_materi'] ?>">
                    <button type="submit" 
                            onclick="return confirm('Restore this course? All sub-materials will also be restored.')"
                            class="group w-full flex items-center justify-center gap-1 px-3 py-2 bg-gradient-to-r from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 border border-green-200 hover:border-green-300 text-green-700 rounded-lg shadow-xs hover:shadow-sm transition-all duration-200">
                      <span class="material-icons text-sm group-hover:scale-110 transition-transform">
                        restore
                      </span>
                      <span class="text-xs font-medium hidden sm:inline">Restore</span>
                    </button>
                  </form>
                  
                  <!-- Delete Button -->
                  <form action="hapusMateri.php" method="POST" class="flex-1">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_materi" value="<?= (int)$row['id_materi'] ?>">
                    <button type="submit" 
                            onclick="return confirm('Permanently delete this course? All sub-materials will also be deleted. This action cannot be undone.')"
                            class="group w-full flex items-center justify-center gap-1 px-3 py-2 bg-gradient-to-r from-red-50 to-pink-50 hover:from-red-100 hover:to-pink-100 border border-red-200 hover:border-red-300 text-red-700 rounded-lg shadow-xs hover:shadow-sm transition-all duration-200">
                      <span class="material-icons text-sm group-hover:scale-110 transition-transform">
                        delete_forever
                      </span>
                      <span class="text-xs font-medium hidden sm:inline">Delete</span>
                    </button>
                  </form>
                  
                </div>
              </div>
              
            </div>
            
            <!-- Description (Mobile only) -->
            <div class="mt-3 lg:hidden pt-3 border-t border-gray-100">
              <p class="text-sm text-gray-600 line-clamp-2">
                <?= htmlspecialchars($row['deskripsi_materi'] ?? 'No description') ?>
              </p>
            </div>
            
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  
</div>

<script>
// Add confirmation for delete actions
document.addEventListener('DOMContentLoaded', function() {
  // Add ripple effect to buttons
  const buttons = document.querySelectorAll('button');
  buttons.forEach(button => {
    button.addEventListener('click', function(e) {
      // Only add ripple to action buttons
      if (this.closest('form')) {
        const rect = this.getBoundingClientRect();
        const ripple = document.createElement('span');
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
          position: absolute;
          border-radius: 50%;
          background: rgba(255, 255, 255, 0.7);
          transform: scale(0);
          animation: ripple 0.6s linear;
          width: ${size}px;
          height: ${size}px;
          top: ${y}px;
          left: ${x}px;
          pointer-events: none;
        `;
        
        this.style.position = 'relative';
        this.style.overflow = 'hidden';
        this.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
      }
    });
  });
  
  // Add animation for new rows
  const rows = document.querySelectorAll('[style*="animation-delay"]');
  rows.forEach(row => {
    row.style.opacity = '0';
    setTimeout(() => {
      row.style.opacity = '1';
    }, parseInt(row.style.animationDelay));
  });
});

// Add ripple animation CSS
const style = document.createElement('style');
style.textContent = `
@keyframes ripple {
  to {
    transform: scale(4);
    opacity: 0;
  }
}

* {
  transition-property: color, background-color, border-color, transform, box-shadow;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .text-2xl {
    font-size: 1.5rem;
  }
  
  .text-3xl {
    font-size: 1.75rem;
  }
}

/* Focus styles for accessibility */
button:focus, a:focus {
  outline: 2px solid #06b6d4;
  outline-offset: 2px;
}

/* Material icons sizing */
.material-icons {
  font-size: inherit;
}

/* Hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}
`;
document.head.appendChild(style);
</script>

</body>
</html>