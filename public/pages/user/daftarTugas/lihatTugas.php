<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/tugas.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

use App\AuthMiddleware;
use App\Tugas;

$userAuth = AuthMiddleware::authUser();
$userId   = $userAuth['id'] ?? ($_SESSION['user_id'] ?? 0);

$tugas = new Tugas((int)$userId);

// optional filter status - handle empty string
$status = $_GET['status'] ?? null;
if ($status === "") {
    $status = null;
}

// DEBUG sebelum ambil data
echo "<!-- DEBUG sebelum listTugas: User ID = $userId, Status = " . ($status ?? 'NULL') . " -->";

// ambil data list tugas
$res = $tugas->listTugas($status);

// DEBUG setelah ambil data
echo "<!-- DEBUG setelah listTugas: ";
print_r($res);
echo " -->";

// ambil flash message (kalau ada)
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// PERBAIKAN: Periksa success terlebih dahulu
if (isset($res['success']) && $res['success'] === true) {
    $tasks = $res['data'] ?? [];
} else {
    $tasks = [];
    // Tampilkan pesan error jika ada
    $errorMessage = $res['message'] ?? 'Gagal mengambil data tugas';
}

require_once PUBLIC_PATH . '/partials/header.php'; 
require_once PUBLIC_PATH . '/partials/sidebar.php';
?>


<div class="min-h-screen bg-[#F9FAFB]">
    <div class="md:ml-[152px] lg:ml-[152px] xl:ml-[152px] transition-all duration-300">
        <div class="w-full">
            <div class="w-full px-3 sm:px-4 md:px-6 py-3 sm:py-4 bg-white border-b border-gray-100">
                <div class="max-w-full">
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 items-start md:items-center gap-3 sm:gap-4">
                        
                        <!-- kolom kiri -->
                        <div class="w-full md:col-span-1">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-1.5 h-10 bg-gradient-to-b from-[#2563EB] to-[#93C5FD] rounded-full"></div>
                                <div>
                                    <h1 class="text-xl sm:text-2xl font-semibold text-[#1F2937]">
                                        Daftar Tugas
                                    </h1>
                                    <p class="text-gray-500 text-xs sm:text-sm mt-0.5">
                                        Kelola semua tugas Anda di satu tempat
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:mt-3 flex flex-wrap items-center gap-2">
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-[#2563EB]/10 border border-[#2563EB]/20 rounded-lg">
                                    <div class="w-1.5 h-1.5 bg-[#2563EB] rounded-full mr-1.5"></div>
                                    <span class="text-xs font-medium text-[#2563EB]">
                                        <?= count($tasks) ?> Tugas
                                    </span>
                                </div>
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-[#10B981]/10 border border-[#10B981]/20 rounded-lg">
                                    <svg class="w-3 h-3 text-[#10B981] mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs font-medium text-[#10B981]">
                                        Terakhir update: <?= date('d M') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Button di kanan -->
                        <div class="w-full md:col-span-1 md:flex md:justify-end">
                            <a href="tambahTugas.php"
                               class="group w-full md:w-auto inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-[#2563EB] text-white font-medium rounded-lg hover:bg-[#2563EB]/90 transition-all duration-200 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Tambah Tugas</span>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- konten utama -->
            <div class="w-full p-3 sm:p-4 md:p-6">
                <div class="w-full max-w-full mx-auto">
                    
                    <!-- tampilan jika belum ada daftar tugas -->
                    <?php if (!empty($flash)): ?>
                    <div class="w-full mb-4 sm:mb-6 p-3 sm:p-4 rounded-lg border <?= $flash['success'] ? 'border-[#10B981]/30 bg-[#10B981]/10' : 'border-red-200 bg-red-50' ?>">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <?php if ($flash['success']): ?>
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#10B981]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium <?= $flash['success'] ? 'text-[#10B981]' : 'text-red-700' ?>">
                                    <?= htmlspecialchars($flash['message']) ?>
                                </p>
                                <?php if (!empty($flash['errors'])): ?>
                                <ul class="mt-2 list-disc pl-5 text-sm <?= $flash['success'] ? 'text-[#10B981]' : 'text-red-700' ?> space-y-1">
                                    <?php foreach ($flash['errors'] as $e): ?>
                                    <li><?= htmlspecialchars($e) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- bagian filter tugas dan stats tugas -->
                    <div class="w-full bg-white border border-gray-200 rounded-xl p-3 sm:p-4 md:p-6 mb-4 sm:mb-6">
                        <div class="w-full flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4">
                            <!-- form filter tugas-->
                            <form method="GET" action="" class="w-full md:w-auto">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                            </svg>
                                        </div>
                                        <select name="status" 
                                                onchange="this.form.submit()"
                                                class="w-full pl-10 pr-3 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] transition appearance-none bg-white text-sm">
                                            <option value="" <?= empty($status) ? 'selected' : '' ?>>Semua Status</option>
                                            <option value="belum progres" <?= $status == 'belum progres' ? 'selected' : '' ?>>Belum Progres</option>
                                            <option value="dalam progres" <?= $status == 'dalam progres' ? 'selected' : '' ?>>Dalam Progres</option>
                                            <option value="selesai" <?= $status == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                            
                            <!-- Stats -->
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full bg-red-500 mr-2"></div>
                                        <span class="hidden xs:inline">Belum</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full bg-yellow-500 mr-2"></div>
                                        <span class="hidden xs:inline">Progres</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
                                        <span class="hidden xs:inline">Selesai</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- daftar tugas -->
                    <div class="w-full">
                        
                        <?php if (empty($tasks)): ?>
                            <!-- kalo filter gada tugasnya -->
                            <div class="w-full text-center py-8 sm:py-12 px-3 sm:px-4">
                                <div class="relative mb-6">
                                    <div class="w-32 h-32 sm:w-40 sm:h-40 bg-gradient-to-br from-[#93C5FD]/20 to-[#2563EB]/20 rounded-full flex items-center justify-center">
                                        <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-[#93C5FD]/30 to-[#2563EB]/30 rounded-full flex items-center justify-center">
                                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <h3 class="text-lg sm:text-xl font-medium text-[#1F2937] mb-2">
                                    <?php if ($status): ?>
                                        Tidak ada tugas dengan status "<?= htmlspecialchars($status) ?>"
                                    <?php else: ?>
                                        Belum ada tugas
                                    <?php endif; ?>
                                </h3>
                                <p class="text-gray-500 mb-6 max-w-sm text-sm sm:text-base mx-auto">
                                    <?php if ($status): ?>
                                        Coba ubah filter atau tambah tugas baru.
                                    <?php else: ?>
                                        Mulai dengan menambahkan tugas pertama Anda.
                                    <?php endif; ?>
                                </p>
                                
                                <a href="tambahTugas.php"
                                   class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 bg-[#2563EB] text-white font-medium rounded-lg hover:bg-[#2563EB]/90 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span>Tambah Tugas Pertama</span>
                                </a>
                            </div>
                        <?php else: ?>
                            <!-- tampilan daftar tugas -->
                            <div class="w-full mb-6">
                                <div class="w-full grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                                    <?php foreach ($tasks as $t): 
                                        // Tentukan status dan warna 
                                        $statusColor = '';
                                        $statusText = '';
                                        $statusIcon = '';
                                    
                                        switch($t['status']) {
                                            case 'belum progres':
                                                $statusColor = 'bg-red-50 border-red-100';
                                                $statusText = 'text-red-700';
                                                $statusIcon = 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z';
                                                break;
                                            case 'dalam progres':
                                                $statusColor = 'bg-yellow-50 border-yellow-100';
                                                $statusText = 'text-yellow-700';
                                                $statusIcon = 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15';
                                                break;
                                            case 'selesai':
                                                $statusColor = 'bg-green-50 border-green-100';
                                                $statusText = 'text-green-700';
                                                $statusIcon = 'M5 13l4 4L19 7';
                                                break;
                                        }
                                        
                                        // Format deadline
                                        $deadlineFormatted = '';
                                        $deadlineClass = 'text-gray-600';
                                        if (!empty($t['deadline'])) {
                                            $deadlineFormatted = date('d M Y', strtotime($t['deadline']));
                                            if (strtotime($t['deadline']) < time() && $t['status'] != 'selesai') {
                                                $deadlineClass = 'text-red-600';
                                            }
                                        }
                                    ?>
                                    
                                    <!-- kartu daftar tugas -->
                                    <div class="w-full group bg-white border border-gray-100 rounded-lg hover:border-gray-200 transition-all duration-200 flex flex-col h-full">
                                        
                                        <!-- Card Header -->
                                        <div class="w-full flex items-start justify-between p-3 sm:p-4">
                                            <div class="flex-1 min-w-0">
                                                <!-- Judul -->
                                                <h3 class="w-full text-sm sm:text-base font-medium text-[#1F2937] line-clamp-1 mb-1">
                                                    <?= htmlspecialchars($t['title']) ?>
                                                </h3>
                                                
                                                <!-- Status badge -->
                                                <div class="inline-flex items-center px-2 py-0.5 <?= $statusColor ?> border <?= $statusText ?> rounded text-xs">
                                                    <svg class="w-3 h-3 mr-1 <?= $statusText ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $statusIcon ?>"></path>
                                                    </svg>
                                                    <span class="<?= $statusText ?> font-medium">
                                                        <?= htmlspecialchars($t['status']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Menu Icon -->
                                            <div class="ml-2 flex-shrink-0">
                                                <div class="p-1.5 bg-gray-50 rounded-lg">
                                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content Preview -->
                                        <div class="w-full flex-1 px-3 sm:px-4">
                                            <div class="w-full bg-gray-50 border border-gray-100 p-3 rounded-lg h-24 overflow-hidden">
                                                <div class="w-full h-full overflow-y-auto pr-2">
                                                    <p class="w-full text-gray-600 text-xs sm:text-sm leading-relaxed line-clamp-3">
                                                        <?= htmlspecialchars($t['description'] ?? 'Tidak ada deskripsi') ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Deadline & Actions -->
                                        <div class="w-full p-3 sm:p-4">
                                            <!-- Deadline -->
                                            <?php if ($deadlineFormatted): ?>
                                            <div class="mb-3 flex items-center justify-between text-xs <?= $deadlineClass ?>">
                                                <div class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span>Deadline: <?= $deadlineFormatted ?></span>
                                                </div>
                                                <?php if (strtotime($t['deadline']) < time() && $t['status'] != 'selesai'): ?>
                                                <div class="flex items-center text-red-500">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.464-.833-2.232 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                    </svg>
                                                    <span class="text-xs">Terlambat</span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <!-- Action Buttons -->
                                            <div class="w-full flex items-center gap-1.5">
                                                <!-- Edit Button -->
                                                <a href="editTugas.php?id_catatan=<?= (int)$t['id_catatan'] ?>"
                                                   class="flex-1 flex items-center justify-center gap-1.5 px-2 py-1.5 bg-[#2563EB]/10 text-[#2563EB] font-medium rounded hover:bg-[#2563EB] hover:text-white transition-colors duration-200 text-xs sm:text-sm text-center whitespace-nowrap">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    <span class="hidden xs:inline">Edit</span>
                                                    <span class="xs:hidden">Ubah</span>
                                                </a>
                                                
                                                <!-- Delete Button -->
                                                <form method="POST" 
                                                      action="hapusTugas.php" 
                                                      onsubmit="return confirm('Yakin ingin menghapus tugas ini?');"
                                                      class="flex-1">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id_catatan" value="<?= (int)$t['id_catatan'] ?>">
                                                    <button type="submit" 
                                                            class="w-full flex items-center justify-center gap-1.5 px-2 py-1.5 bg-red-50 text-red-600 font-medium rounded hover:bg-red-600 hover:text-white transition-colors duration-200 text-xs sm:text-sm text-center whitespace-nowrap">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        <span class="hidden xs:inline">Hapus</span>
                                                        <span class="xs:hidden">Hapus</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Stats Footer -->
                                <div class="w-full mt-6 pt-4 border-t border-gray-200">
                                    <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                                        <div class="text-xs sm:text-sm text-gray-600">
                                            Menampilkan <span class="font-medium text-[#2563EB]"><?= count($tasks) ?></span> tugas
                                            <?php if ($status): ?>
                                                dengan status "<span class="font-medium"><?= htmlspecialchars($status) ?></span>"
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <?php
                                                $completed = array_filter($tasks, fn($t) => $t['status'] === 'selesai');
                                                $inProgress = array_filter($tasks, fn($t) => $t['status'] === 'dalam progres');
                                                ?>
                                                <span class="text-green-600 font-medium"><?= count($completed) ?> selesai</span>
                                                <span class="text-gray-300">|</span>
                                                <span class="text-yellow-600 font-medium"><?= count($inProgress) ?> progres</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles - Konsisten -->
<style>
/* Line clamp utilities */
.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
}

.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}

.line-clamp-3 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
}

.line-clamp-4 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 4;
}

.line-clamp-5 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 5;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 4px;
    height: 4px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #93C5FD;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #2563EB;
}

/* Responsive adjustments */
@media (max-width: 479px) {
    .xs\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .xs\:grid-cols-3 {
        grid-template-columns: 1fr;
    }
    
    .text-sm {
        font-size: 0.8125rem;
    }
    
    .text-xs {
        font-size: 0.75rem;
    }
    
    .gap-1\.5 {
        gap: 0.25rem;
    }
    
    .p-3 {
        padding: 0.5rem;
    }
}

/* Custom breakpoint untuk extra small */
@media (min-width: 480px) and (max-width: 639px) {
    .xs\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .xs\:grid-cols-3 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Tablet */
@media (min-width: 640px) and (max-width: 767px) {
    .sm\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Desktop kecil */
@media (min-width: 768px) and (max-width: 1023px) {
    .md\:grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

/* Desktop besar */
@media (min-width: 1024px) {
    .lg\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

/* Untuk layar sangat kecil */
@media (max-width: 359px) {
    .text-xs {
        font-size: 0.7rem;
    }
    
    .gap-1\.5 {
        gap: 0.125rem;
    }
    
    .px-2 {
        padding-left: 0.375rem;
        padding-right: 0.375rem;
    }
    
    .py-1\.5 {
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
    }
    
    .w-3 {
        width: 0.75rem;
    }
    
    .h-3 {
        height: 0.75rem;
    }
}
</style>

<script>
// Responsive handling untuk sidebar
const checkSidebar = () => {
    const wrapper = document.querySelector('.min-h-screen > div');
    if (window.innerWidth < 768) {
        wrapper.classList.remove('md:ml-[152px]', 'lg:ml-[152px]', 'xl:ml-[152px]');
    } else {
        wrapper.classList.add('md:ml-[152px]', 'lg:ml-[152px]', 'xl:ml-[152px]');
    }
};

// Initial check
checkSidebar();

// Adjust on resize
window.addEventListener('resize', checkSidebar);

// Auto-submit filter on mobile
document.querySelector('select[name="status"]').addEventListener('change', function() {
    if (window.innerWidth < 768) {
        this.form.submit();
    }
});
</script>