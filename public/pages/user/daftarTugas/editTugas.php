<?php
session_start();

require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/tugas.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

use App\AuthMiddleware;
use App\Tugas;

// AUTH
$userAuth = AuthMiddleware::authUser();
$userId = $userAuth['id'] ?? 0;

$tugas = new Tugas((int)$userId);

// ambil id dari query string
$idCatatan = isset($_GET['id_catatan']) ? (int)$_GET['id_catatan'] : 0;

// kalau submit update (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCatatanPost = isset($_POST['id_catatan']) ? (int)$_POST['id_catatan'] : 0;

    $title       = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? null;
    $status      = $_POST['status'] ?? 'belum progres';
    $deadline    = $_POST['deadline'] ?? null;

    $res = $tugas->updateTugas($idCatatanPost, $title, $description, $status, $deadline);

    $_SESSION['flash'] = $res;

    header("Location: lihatTugas.php");
    exit;
}

// GET: ambil data tugas untuk ditampilkan
$detail = $tugas->getTugasById($idCatatan);

// kalau tugas tidak ketemu / bukan milik user
if (!$detail['success']) {
    $_SESSION['flash'] = $detail;
    header("Location: lihatTugas.php");
    exit;
}

$data = $detail['data'];

require_once PUBLIC_PATH . '/partials/header.php';
require_once PUBLIC_PATH . '/partials/sidebar.php';

// flash
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<!-- WRAPPER UTAMA - Konsisten dengan lihatCatatan.php -->
<div class="min-h-screen bg-[#F9FAFB]">
    
    <!-- Desktop: setelah sidebar w-38 (152px) -->
    <div class="md:ml-[152px] lg:ml-[152px] xl:ml-[152px] transition-all duration-300">
        
        <!-- Full width container -->
        <div class="w-full">
            
            <!-- HEADER SECTION - Konsisten -->
            <div class="w-full px-3 sm:px-4 md:px-6 py-3 sm:py-4 bg-white border-b border-gray-100">
                <div class="max-w-full">
                    <!-- Gunakan grid untuk control yang lebih baik -->
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 items-start md:items-center gap-3 sm:gap-4">
                        
                        <!-- Left Column -->
                        <div class="w-full md:col-span-1">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-1.5 h-10 bg-gradient-to-b from-[#2563EB] to-[#93C5FD] rounded-full"></div>
                                <div>
                                    <h1 class="text-xl sm:text-2xl font-semibold text-[#1F2937]">
                                        Edit Tugas
                                    </h1>
                                    <p class="text-gray-500 text-xs sm:text-sm mt-0.5">
                                        Perbarui detail tugas yang sudah ada
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:mt-3 flex flex-wrap items-center gap-2">
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-[#2563EB]/10 border border-[#2563EB]/20 rounded-lg">
                                    <div class="w-1.5 h-1.5 bg-[#2563EB] rounded-full mr-1.5"></div>
                                    <span class="text-xs font-medium text-[#2563EB]">
                                        ID: <?= (int)$data['id_catatan'] ?>
                                    </span>
                                </div>
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-[#10B981]/10 border border-[#10B981]/20 rounded-lg">
                                    <svg class="w-3 h-3 text-[#10B981] mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-xs font-medium text-[#10B981]">
                                        <?= date('d M Y', strtotime($data['updated_at'] ?? 'now')) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Button di kanan -->
                        <div class="w-full md:col-span-1 md:flex md:justify-end">
                            <a href="lihatTugas.php"
                               class="group w-full md:w-auto inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Kembali</span>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="w-full p-3 sm:p-4 md:p-6">
                <div class="w-full max-w-full mx-auto">
                    
                    <!-- Flash Message -->
                    <?php if ($flash): ?>
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

                    <!-- FORM SECTION -->
                    <div class="w-full bg-white border border-gray-200 rounded-xl overflow-hidden">
                        
                        <!-- Form Header -->
                        
                        
                        <!-- Form Content -->
                        <div class="w-full p-4 sm:p-6 md:p-8">
                            <form method="POST" class="space-y-4 sm:space-y-6">
                                <input type="hidden" name="id_catatan" value="<?= (int)$data['id_catatan'] ?>">
                                
                                <!-- Judul Input -->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-[#1F2937]">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Judul Tugas <span class="text-red-500">*</span>
                                        </span>
                                    </label>
                                    <input type="text" 
                                           name="title"
                                           required
                                           value="<?= htmlspecialchars($data['title'] ?? '') ?>"
                                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors text-sm sm:text-base"
                                           placeholder="Masukkan judul tugas">
                                    <p class="mt-1 text-xs text-gray-500">Judul harus jelas dan deskriptif</p>
                                </div>

                                <!-- Deskripsi Input -->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-[#1F2937]">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Deskripsi (Opsional)
                                        </span>
                                    </label>
                                    <textarea name="description" 
                                              rows="4"
                                              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors resize-none text-sm sm:text-base"
                                              placeholder="Tambahkan detail atau instruksi tugas"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Detail tambahan tentang tugas ini</p>
                                </div>

                                <!-- Status dan Deadline Row -->
                                <div class="grid grid-cols-1 xs:grid-cols-2 gap-3 sm:gap-4">
                                    
                                    <!-- Status Field -->
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-[#1F2937]">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                Status
                                            </span>
                                        </label>
                                        <select name="status" 
                                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors text-sm sm:text-base">
                                            <?php
                                            $statuses = [
                                                'belum progres' => ['label' => 'Belum Progres', 'color' => 'red'],
                                                'dalam progres' => ['label' => 'Dalam Progres', 'color' => 'yellow'],
                                                'selesai' => ['label' => 'Selesai', 'color' => 'green']
                                            ];
                                            foreach ($statuses as $key => $info):
                                                $selected = (($data['status'] ?? '') === $key) ? 'selected' : '';
                                            ?>
                                            <option value="<?= htmlspecialchars($key) ?>" <?= $selected ?>>
                                                <?= htmlspecialchars($info['label']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        
                                        <!-- Status Preview -->
                                        <div class="mt-2">
                                            <?php
                                            $currentStatus = $data['status'] ?? 'belum progres';
                                            $statusInfo = $statuses[$currentStatus] ?? $statuses['belum progres'];
                                            ?>
                                            <div class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium
                                                <?= $statusInfo['color'] === 'red' ? 'bg-red-50 text-red-700 border-red-100' : '' ?>
                                                <?= $statusInfo['color'] === 'yellow' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : '' ?>
                                                <?= $statusInfo['color'] === 'green' ? 'bg-green-50 text-green-700 border-green-100' : '' ?>
                                                border">
                                                <div class="w-1.5 h-1.5 rounded-full mr-1.5
                                                    <?= $statusInfo['color'] === 'red' ? 'bg-red-500' : '' ?>
                                                    <?= $statusInfo['color'] === 'yellow' ? 'bg-yellow-500' : '' ?>
                                                    <?= $statusInfo['color'] === 'green' ? 'bg-green-500' : '' ?>">
                                                </div>
                                                <?= htmlspecialchars($statusInfo['label']) ?>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">Status saat ini</p>
                                        </div>
                                    </div>

                                    <!-- Deadline Field -->
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-[#1F2937]">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                Deadline (Opsional)
                                            </span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" 
                                                   id="deadline"
                                                   name="deadline"
                                                   value="<?= htmlspecialchars($data['deadline'] ?? '') ?>"
                                                   class="w-full pl-3 sm:pl-4 pr-10 py-2.5 sm:py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-none transition-colors text-sm sm:text-base"
                                                   placeholder="Format: 2025-12-25 23:59:00">
                                            <button type="button" 
                                                    onclick="document.getElementById('deadline').value = ''"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-red-500 transition-colors"
                                                    title="Hapus deadline">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada deadline</p>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex items-center justify-between pt-4 sm:pt-6 border-t border-gray-200">
                                    <a href="lihatTugas.php"
                                       class="px-4 sm:px-6 py-2 sm:py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-sm sm:text-base">
                                        Batal
                                    </a>
                                    
                                    <button type="submit"
                                            class="px-4 sm:px-6 py-2 sm:py-2.5 bg-[#2563EB] hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm sm:text-base">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- INFO SECTION -->
                    <div class="w-full mt-4 sm:mt-6 bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs sm:text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Terakhir diubah: <?= date('d/m/Y H:i', strtotime($data['updated_at'] ?? 'now')) ?></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles - Konsisten -->
<style>
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
    
    .text-sm {
        font-size: 0.8125rem;
    }
    
    .text-xs {
        font-size: 0.75rem;
    }
    
    .px-3 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
}

/* Custom breakpoint untuk extra small */
@media (min-width: 480px) and (max-width: 639px) {
    .xs\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Focus styles untuk form */
input:focus, textarea:focus, select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Untuk layar sangat kecil */
@media (max-width: 359px) {
    .text-xs {
        font-size: 0.7rem;
    }
    
    .px-3 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .py-2\.5 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
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

// Update status preview saat dropdown berubah
document.querySelector('select[name="status"]').addEventListener('change', function() {
    const statusMap = {
        'belum progres': { label: 'Belum Progres', color: 'red' },
        'dalam progres': { label: 'Dalam Progres', color: 'yellow' },
        'selesai': { label: 'Selesai', color: 'green' }
    };
    
    const selected = this.value;
    const statusInfo = statusMap[selected];
    
    const previewElement = document.querySelector('.inline-flex.items-center.px-2\\.5');
    previewElement.className = `inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium border
        ${statusInfo.color === 'red' ? 'bg-red-50 text-red-700 border-red-100' : ''}
        ${statusInfo.color === 'yellow' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : ''}
        ${statusInfo.color === 'green' ? 'bg-green-50 text-green-700 border-green-100' : ''}`;
    
    const dotElement = previewElement.querySelector('.w-1\\.5');
    dotElement.className = `w-1.5 h-1.5 rounded-full mr-1.5
        ${statusInfo.color === 'red' ? 'bg-red-500' : ''}
        ${statusInfo.color === 'yellow' ? 'bg-yellow-500' : ''}
        ${statusInfo.color === 'green' ? 'bg-green-500' : ''}`;
    
    previewElement.innerHTML = dotElement.outerHTML + statusInfo.label;
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const title = this.querySelector('input[name="title"]').value.trim();
    
    if (!title) {
        e.preventDefault();
        alert('Judul tugas harus diisi!');
        return false;
    }
    
    // Optional: Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Menyimpan...';
    submitBtn.disabled = true;
    
    // Re-enable after 3 seconds if still processing (safety)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 3000);
});
</script>