<?php
require_once __DIR__ . '/../../../../backend/AuthMiddleware.php';
use App\AuthMiddleware;
AuthMiddleware::authUser();

// INCLUDE BACKEND CLASSES
require_once __DIR__ . '/../../../../backend/catatanUser.php';
use App\catat\Catatan;

$catat = new Catatan($_SESSION['user_id']);

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? null;
    $catatan = $_POST['catatan'] ?? '';

    if ($catat->tambahCatatan($catatan, $judul)) {
        header('Location: lihatCatatan.php');
        exit();
    } else {
        $msg = 'Gagal menambah catatan.';
    }
}

require_once __DIR__ . '/../../../assets/layout/header.php';
require_once __DIR__ . '/../../../assets/layout/sidebar.php';
?>

<!-- WRAPPER UTAMA - FULL WIDTH DAN BACKGROUND PUTIH -->
<div class="min-h-screen bg-white" style="margin-left: 16rem; width: calc(100% - 16rem);">

    <!-- HEADER SECTION - FULL WIDTH -->
    <div class="bg-white border-b border-gray-200 px-6 py-5">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Tambah Catatan Baru
                </h1>
            </div>
            
            <a href="lihatCatatan.php"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Catatan</span>
            </a>
        </div>
    </div>

    <!-- MAIN CONTENT - FULL WIDTH -->
    <div class="w-full p-6">
        <div class="max-w-4xl mx-auto">
            
            <?php if ($msg): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-red-700"><?= htmlspecialchars($msg) ?></span>
                </div>
            </div>
            <?php endif; ?>

            <!-- FORM SECTION - SIMPLE WHITE DESIGN -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-8">
                    <!-- Form Header -->
                    <div class="mb-8 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 rounded-full mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Buat Catatan Baru</h2>
                        <p class="text-gray-600">Isi detail catatan pembelajaranmu di bawah ini</p>
                    </div>

                    <form method="POST" class="space-y-6">
                        <!-- Judul Input -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Judul Catatan
                                </span>
                            </label>
                            <input type="text" 
                                   name="judul" 
                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors"
                                   required>
                        </div>

                        <!-- Isi Catatan Input -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Isi Catatan
                                </span>
                            </label>
                            <textarea name="catatan" 
                                      rows="12"
                                      class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors resize-none"
                                      required></textarea>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="lihatCatatan.php"
                               class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                Batal
                            </a>
                            
                            <button type="submit"
                                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Simpan Catatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Reset untuk memastikan full width */
html, body {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

/* Pastikan wrapper mengambil seluruh width setelah sidebar */
div[style*="margin-left: 16rem"] {
    position: relative;
    left: 0;
    right: 0;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    div[style*="margin-left: 16rem"] {
        margin-left: 0 !important;
        width: 100% !important;
    }
}

@media (max-width: 640px) {
    .p-8 {
        padding: 1.5rem;
    }
}
</style>