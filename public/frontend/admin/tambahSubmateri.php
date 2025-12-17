<?php
require_once __DIR__ . "/../../../backend/AuthMiddleware.php";
use App\AuthMiddleware;
AuthMiddleware::authAdmin();

require_once __DIR__ . "/../../../backend/subMateri.php";
use App\submateri\Submateri;

// Tambahkan require untuk materi.php
require_once __DIR__ . "/../../../backend/materi.php";
use App\Materi\Materi;

$tambah_submateri = new Submateri();
$idMateri = $_GET['id'];

// Ambil data materi berdasarkan ID
$materiObj = new Materi();
$materiData = $materiObj->getMateriById($idMateri); // Asumsikan ada method ini

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $urutan = $_POST['urutan'];
    $judulsubMateri = $_POST['judul']; 
    $isi = $_POST['isi'];
    $tambah_submateri -> tambahSubmateri($urutan, $judulsubMateri, $isi, $idMateri);
    header("Location: lihatSubmateri.php?id=" . $idMateri . "&msg=updated");
    exit();
}
require_once __DIR__ . '/../../assets/layout/header.php';
require_once __DIR__ . '/../../assets/layout/sbAdmin.php';
?>

<body class="bg-gray-50">

<div class="ml-0 lg:ml-64 min-h-screen transition-all duration-300">
    
    <!-- Main Container -->
    <div class="p-4 md:p-6 lg:p-8">
        
        <!-- Form Header -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="flex items-center mb-4">
                <a href="lihatSubmateri.php?id=<?php echo $idMateri; ?>" class="mr-4 p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Tambah Submateri Baru</h1>
                    <p class="text-gray-600 mt-1">Tambahkan submateri baru untuk materi pembelajaran</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                
                <!-- Form Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="flex items-center mb-4 md:mb-0">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl mr-4 shadow-md">
                                <i class="fas fa-layer-group text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Form Tambah Submateri</h2>
                                <div class="flex items-center mt-1">
                                    <span class="text-sm font-medium text-gray-600 mr-2">Untuk Materi:</span>
                                    <span class="text-sm font-semibold text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
                                        <?php 
                                        if (isset($materiData['nama_materi'])) {
                                            echo htmlspecialchars($materiData['nama_materi']);
                                        } 
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Materi Info Box -->
                        <div class="bg-white border border-blue-200 rounded-lg p-3 shadow-sm">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-info-circle text-blue-500"></i>
                                <span class="text-sm font-medium text-gray-700">ID Materi:</span>
                                <span class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($idMateri); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-6 md:p-8">
                    <form action="" method="POST" class="space-y-8">
                        
                        <!-- Informasi Materi -->
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 mb-6">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-book-open text-blue-600 mr-3 text-lg"></i>
                                <h3 class="font-semibold text-blue-800">Informasi Materi</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Nama Materi</label>
                                    <div class="p-3 bg-white rounded-lg border border-gray-200">
                                        <p class="font-medium text-gray-800">
                                            <?php 
                                            if (isset($materiData['nama_materi'])) {
                                                echo htmlspecialchars($materiData['nama_materi']);
                                            } else {
                                                echo "Materi #" . htmlspecialchars($idMateri);
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">ID Materi</label>
                                    <div class="p-3 bg-white rounded-lg border border-gray-200">
                                        <p class="font-medium text-gray-800"><?php echo htmlspecialchars($idMateri); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Urutan Submateri -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3 shadow-sm">
                                    <span class="text-white font-semibold">1</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Urutan Submateri</h3>
                            </div>
                            
                            <div class="pl-11">
                                <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Urutan
                                </label>
                                <div class="relative max-w-xs">
                                    <input 
                                        type="number" 
                                        name="urutan" 
                                        id="urutan" 
                                        min="1"
                                        required
                                        class="form-input w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                                        value="1"
                                    >
                                    <div class="absolute left-3 top-3 text-blue-500">
                                        <i class="fas fa-sort-numeric-up"></i>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Tentukan urutan tampilan submateri (1 untuk pertama)</p>
                            </div>
                        </div>

                        <!-- Judul Submateri -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mr-3 shadow-sm">
                                    <span class="text-white font-semibold">2</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Judul Submateri</h3>
                            </div>
                            
                            <div class="pl-11">
                                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                                    Judul Materi
                                </label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        name="judul" 
                                        id="judul" 
                                        required
                                        class="form-input w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200"
                                    >
                                    <div class="absolute left-3 top-3 text-green-500">
                                        <i class="fas fa-heading"></i>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Berikan judul yang jelas dan deskriptif</p>
                            </div>
                        </div>

                        <!-- Isi Submateri -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mr-3 shadow-sm">
                                    <span class="text-white font-semibold">3</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Konten Submateri</h3>
                            </div>
                            
                            <div class="pl-11">
                                <label for="isi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Isi Materi
                                </label>
                                <div class="relative">
                                    <textarea 
                                        name="isi" 
                                        id="isi" 
                                        required
                                        rows="8"
                                        class="form-input w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition duration-200 resize-none"
                                    ></textarea>
                                    <div class="absolute left-3 top-3 text-purple-500">
                                        <i class="fas fa-align-left"></i>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <p class="text-sm text-gray-500">Tulis konten materi secara lengkap dan terstruktur</p>
                                    <span id="charCount" class="text-sm font-medium text-gray-500">0 karakter</span>
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 pt-6"></div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between items-center pt-6 space-y-4 sm:space-y-0">
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="p-2 bg-gray-100 rounded-lg mr-3">
                                    <i class="fas fa-info-circle text-gray-500"></i>
                                </div>
                                <span>Semua kolom wajib diisi sebelum disimpan</span>
                            </div>
                            
                            <div class="flex space-x-3 w-full sm:w-auto">
                                <a href="lihatSubmateri.php?id=<?php echo $idMateri; ?>" 
                                   class="flex-1 sm:flex-none px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-400 transition duration-200 flex items-center justify-center space-x-3">
                                    <i class="fas fa-times"></i>
                                    <span>Batal</span>
                                </a>
                                <button 
                                    type="submit" 
                                    name="input"
                                    class="flex-1 sm:flex-none px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-opacity-50 transition duration-200 flex items-center justify-center space-x-3 shadow-md hover:shadow-lg"
                                >
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Simpan Submateri</span>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Quick Guidelines -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-xl p-5 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-blue-100 rounded-lg mr-4">
                            <i class="fas fa-lightbulb text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="font-bold text-blue-800">Tips Penulisan</h3>
                    </div>
                    <ul class="space-y-2">
                        <li class="flex items-start text-sm text-blue-700">
                            <i class="fas fa-check-circle text-blue-500 mt-1 mr-2"></i>
                            <span>Gunakan bahasa yang mudah dipahami</span>
                        </li>
                        <li class="flex items-start text-sm text-blue-700">
                            <i class="fas fa-check-circle text-blue-500 mt-1 mr-2"></i>
                            <span>Struktur konten dengan heading dan paragraf</span>
                        </li>
                        <li class="flex items-start text-sm text-blue-700">
                            <i class="fas fa-check-circle text-blue-500 mt-1 mr-2"></i>
                            <span>Sertakan contoh jika diperlukan</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gradient-to-br from-green-50 to-white border border-green-100 rounded-xl p-5 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-green-100 rounded-lg mr-4">
                            <i class="fas fa-clipboard-check text-green-600 text-xl"></i>
                        </div>
                        <h3 class="font-bold text-green-800">Penting</h3>
                    </div>
                    <ul class="space-y-2">
                        <li class="flex items-start text-sm text-green-700">
                            <i class="fas fa-exclamation-circle text-green-500 mt-1 mr-2"></i>
                            <span>Urutan menentukan tampilan di halaman</span>
                        </li>
                        <li class="flex items-start text-sm text-green-700">
                            <i class="fas fa-exclamation-circle text-green-500 mt-1 mr-2"></i>
                            <span>Judul harus unik dan spesifik</span>
                        </li>
                        <li class="flex items-start text-sm text-green-700">
                            <i class="fas fa-exclamation-circle text-green-500 mt-1 mr-2"></i>
                            <span>Konten akan langsung tersimpan ke sistem</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter for textarea
const textarea = document.getElementById('isi');
const charCount = document.getElementById('charCount');

textarea.addEventListener('input', function() {
    const length = this.value.length;
    charCount.textContent = length.toLocaleString() + ' karakter';
    
    // Change color based on length
    if (length === 0) {
        charCount.classList.remove('text-green-500', 'text-yellow-500', 'text-red-500');
        charCount.classList.add('text-gray-500');
    } else if (length < 100) {
        charCount.classList.remove('text-gray-500', 'text-yellow-500', 'text-red-500');
        charCount.classList.add('text-red-500');
    } else if (length < 500) {
        charCount.classList.remove('text-gray-500', 'text-red-500', 'text-green-500');
        charCount.classList.add('text-yellow-500');
    } else {
        charCount.classList.remove('text-gray-500', 'text-yellow-500', 'text-red-500');
        charCount.classList.add('text-green-500');
    }
});

// Auto-resize textarea
textarea.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

// Form validation and loading state
const form = document.querySelector('form');
const submitBtn = form.querySelector('button[type="submit"]');

form.addEventListener('submit', function(e) {
    const urutan = document.getElementById('urutan').value;
    const judul = document.getElementById('judul').value.trim();
    const isi = document.getElementById('isi').value.trim();
    
    if (!urutan || !judul || !isi) {
        e.preventDefault();
        showNotification('Harap isi semua kolom yang diperlukan', 'error');
        return;
    }
    
    if (parseInt(urutan) < 1) {
        e.preventDefault();
        showNotification('Nomor urutan minimal 1', 'error');
        return;
    }
    
    // Show loading state
    const originalContent = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span>Menyimpan...</span>';
    submitBtn.disabled = true;
});

// Notification function
function showNotification(message, type) {
    // Remove existing notification
    const existing = document.querySelector('.custom-notification');
    if (existing) existing.remove();
    
    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-xl transform transition-all duration-300 animate-fadeIn ${
        type === 'error' ? 'bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500' : 
        'bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} mr-4 text-xl ${
                type === 'error' ? 'text-red-600' : 'text-green-600'
            }"></i>
            <div>
                <p class="font-medium ${type === 'error' ? 'text-red-800' : 'text-green-800'}">${message}</p>
            </div>
            <button class="ml-8 text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
`;
document.head.appendChild(style);
</script>

</body>
</html>