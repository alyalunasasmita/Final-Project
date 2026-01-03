<?php
require_once __DIR__ . "/../../config.php";
require_once ROOT_PATH . '/backend/subMateri.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

use App\AuthMiddleware;
AuthMiddleware::authAdmin();
use App\submateri\Submateri;

$submateri = new Submateri();

// Ambil ID submateri & ID materi
$id_subMateri = $_GET['id'] ?? $_POST['id'] ?? null;
$materiId = $_GET['materi'] ?? $_POST['materi'] ?? null;

if (!$id_subMateri || !$materiId) {
    die("ID submateri atau ID materi tidak ditemukan.");
}

// Ambil data lama
$data = $submateri->lihatSubmateriById($id_subMateri);

if (!$data) {
    die("Submateri tidak ditemukan!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $isi = $_POST['isi'] ?? '';

    $result = $submateri->updateSubmateri($id_subMateri, $nama, $isi, $materiId);

    if ($result) {
        header("Location: lihatSubmateri.php?id=" . $materiId . "&msg=updated");
        exit;
    } else {
        $error = "Gagal update submateri!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Submateri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        /* Textarea styles */
        .textarea-content {
            min-height: 300px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
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
        
        @media (max-width: 768px) {
            .textarea-content {
                min-height: 250px;
                padding: 12px;
                font-size: 13px;
            }
        }
        
        @media (max-width: 480px) {
            .textarea-content {
                min-height: 200px;
                padding: 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex min-h-screen">
        <div class="hidden lg:block w-38 bg-white border-r border-gray-200"></div>
        <div class="flex-1 flex flex-col">
            <div class="bg-white border-b border-gray-200 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Submateri</h1>
                        <p class="text-gray-600 text-sm sm:text-base mt-1">
                            ID Materi: <span class="font-mono bg-gray-100 px-2 py-1 rounded"><?= htmlspecialchars($materiId) ?></span>
                        </p>
                    </div>
                    <div>
                        <a href="lihatSubmateri.php?id=<?= $materiId ?>" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors text-sm sm:text-base">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
            

            <div class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-4xl mx-auto">
                    <?php if (isset($error)): ?>
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg fade-in">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-circle text-red-600 mt-0.5 mr-3 flex-shrink-0"></i>
                                <div class="text-red-800 text-sm sm:text-base"><?= htmlspecialchars($error) ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="bg-white rounded-xl card-shadow border border-gray-200 overflow-hidden">
                        <div class="px-4 py-6 sm:px-6 sm:py-8 md:px-8 md:py-10">
                            <div class="mb-6">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-edit text-primary mr-2"></i>
                                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">
                                        Edit: <?= htmlspecialchars(substr($data['nama_subMateri'], 0, 50)) . (strlen($data['nama_subMateri']) > 50 ? '...' : '') ?>
                                    </h2>
                                </div>
                                <p class="text-gray-600 text-sm sm:text-base">
                                    Perubahan yang Anda simpan akan langsung diterapkan
                                </p>
                            </div>
                            
                            <form action="" method="POST" id="editForm">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($id_subMateri) ?>">
                                <input type="hidden" name="materi" value="<?= htmlspecialchars($materiId) ?>">
                                
                                <div class="mb-6 sm:mb-8">
                                    <label for="nama" class="block text-gray-700 text-sm sm:text-base font-medium mb-2 sm:mb-3">
                                        <i class="fas fa-heading mr-1 text-gray-500"></i>
                                        Nama Submateri
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            name="nama" 
                                            id="nama" 
                                            value="<?= htmlspecialchars($data['nama_subMateri']) ?>" 
                                            required
                                            class="w-full px-4 py-3 sm:py-3.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 text-sm sm:text-base"
                                            placeholder="Masukkan nama submateri"
                                            maxlength="200"
                                        />
                                    </div>
                                    <p class="mt-1 sm:mt-2 text-xs text-gray-500">
                                        Maksimal 200 karakter
                                    </p>
                                </div>
                                

                                <div class="mb-6 sm:mb-8">
                                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                                        <label for="isi" class="block text-gray-700 text-sm sm:text-base font-medium">
                                            <i class="fas fa-file-alt mr-1 text-gray-500"></i>
                                            Isi Materi
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <span class="text-xs sm:text-sm text-gray-500" id="charCount">
                                            <?= strlen($data['isi_materi']) ?> karakter
                                        </span>
                                    </div>
                                    <textarea 
                                        name="isi" 
                                        id="isi" 
                                        required
                                        class="w-full textarea-content"
                                        placeholder="Tulis isi materi di sini..."
                                        oninput="updateCharCount(this)"
                                    ><?= htmlspecialchars($data['isi_materi']) ?></textarea>
                                    
                                    <div class="mt-2">
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-lightbulb mr-1"></i>
                                            Anda dapat menggunakan format HTML sederhana jika diperlukan
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t border-gray-200">
                                    <button 
                                        type="submit" 
                                        id="submitBtn"
                                        class="flex-1 py-3 sm:py-3.5 px-4 bg-primary hover:bg-blue-700 text-white font-medium sm:font-semibold rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center text-sm sm:text-base"
                                    >
                                        <i class="fas fa-save mr-2"></i>
                                        <span id="buttonText">Simpan Perubahan</span>
                                        <span id="buttonLoader" class="hidden ml-2">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </span>
                                    </button>
                                    
                                    <a 
                                        href="lihatSubmateri.php?id=<?= $materiId ?>" 
                                        class="py-3 sm:py-3.5 px-4 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center text-sm sm:text-base"
                                    >
                                        <i class="fas fa-times mr-2"></i>
                                        Batal
                                    </a>
                                </div>
                                <div id="loading" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg fade-in">
                                    <div class="flex items-center">
                                        <i class="fas fa-spinner fa-spin text-blue-600 mr-3"></i>
                                        <div>
                                            <p class="text-blue-800 font-medium text-sm sm:text-base">Menyimpan perubahan...</p>
                                            <p class="text-blue-700 text-xs mt-1">Mohon tunggu sebentar</p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editForm');
            const submitBtn = document.getElementById('submitBtn');
            const buttonText = document.getElementById('buttonText');
            const buttonLoader = document.getElementById('buttonLoader');
            const loadingEl = document.getElementById('loading');
            const charCount = document.getElementById('charCount');
            const isiTextarea = document.getElementById('isi');
            const namaInput = document.getElementById('nama');
            
            // Update character count
            function updateCharCount(textarea) {
                const text = textarea.value || '';
                charCount.textContent = text.length + ' karakter';
            }
            
            // Initial char count
            updateCharCount(isiTextarea);
            
            // Auto-resize textarea
            function autoResizeTextarea(textarea) {
                textarea.style.height = 'auto';
                textarea.style.height = (textarea.scrollHeight) + 'px';
            }
            
            // Setup auto-resize
            isiTextarea.addEventListener('input', function() {
                autoResizeTextarea(this);
            });
            
            // Initial resize
            setTimeout(() => autoResizeTextarea(isiTextarea), 100);
            
            // Form submission
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Validate form
                if (!namaInput.value.trim()) {
                    alert('Nama submateri harus diisi');
                    namaInput.focus();
                    return;
                }
                
                if (!isiTextarea.value.trim()) {
                    alert('Isi materi harus diisi');
                    isiTextarea.focus();
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
                    const res = await fetch('', { 
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
                                    <p class="text-green-800 font-medium text-sm sm:text-base">Submateri berhasil diperbarui!</p>
                                    <p class="text-green-700 text-xs mt-1">Mengalihkan ke halaman daftar...</p>
                                </div>
                            </div>
                        `;
                        
                        setTimeout(() => {
                            window.location.href = 'lihatSubmateri.php?id=<?= $materiId ?>&msg=updated';
                        }, 1500);
                    } else {
                        throw new Error('Gagal menyimpan perubahan');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    buttonText.textContent = 'Simpan Perubahan';
                    buttonLoader.classList.add('hidden');
                    
                    // Show error message
                    loadingEl.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
                            <div>
                                <p class="text-red-800 font-medium text-sm sm:text-base">Gagal menyimpan perubahan</p>
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
                autoResizeTextarea(isiTextarea);
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
                        window.location.href = 'lihatSubmateri.php?id=<?= $materiId ?>';
                    }
                }
            });
            
            // Warn before leaving if changes were made
            let originalData = {
                nama: namaInput.value,
                isi: isiTextarea.value
            };
            let hasUnsavedChanges = false;
            
            function checkForChanges() {
                hasUnsavedChanges = 
                    namaInput.value !== originalData.nama || 
                    isiTextarea.value !== originalData.isi;
            }
            
            namaInput.addEventListener('input', checkForChanges);
            isiTextarea.addEventListener('input', checkForChanges);
            
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