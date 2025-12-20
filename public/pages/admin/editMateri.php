<?php
require_once __DIR__ . "/../../config.php";  
require_once ROOT_PATH . "/backend/materi.php";
require_once ROOT_PATH . "/backend/AuthMiddleware.php";
use App\AuthMiddleware;

AuthMiddleware::authAdmin();
use App\Materi\Materi;

$tambah_materi = new Materi();

// Ambil ID materi dari GET atau POST
$id = $_GET['id'] ?? $_POST['id'] ?? null;
if (!$id) {
    die("ID materi tidak ditemukan");
}

// Ambil data materi dari database
$response = $tambah_materi->getMateriById((int)$id);
if (!$response['success']) {
    die($response['message']);
}
$data = $response['data'];

// Jika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $ytURL = $_POST['ytURL'] ?? '';

    $result = $tambah_materi->updateMateri((int)$id, $nama, $deskripsi, $ytURL);

    header('Location: lihatMateri.php?msg=' . urlencode($result['message']));
    exit;
}
require_once PUBLIC_PATH . '/partials/header.php';

?>


    <title>Edit Course <?= htmlspecialchars($data['nama_materi'] ?? '') ?></title>
<body class="min-h-screen bg-gray-50">

<div class="w-full">
    
    <!-- Header Section - Full Width -->
    <div class="bg-white border-b border-gray-200 w-full px-4 md:px-6 lg:px-8 py-4 md:py-6">
        <div class="mx-auto w-full">
            <div class="flex items-center gap-4">
                <a href="lihatMateri.php" 
                   class="group p-2.5 bg-white border border-gray-200 hover:border-blue-300 rounded-lg shadow-sm hover:shadow transition-all duration-200">
                    <span class="material-icons text-gray-600 group-hover:text-blue-600 transition-colors">
                        arrow_back
                    </span>
                </a>
                <div class="flex-1">
                    <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800">
                        Edit Matari Pembelajaran
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm md:text-base">Ubah Informasi Materi Pembelajaran</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Container - Full Width with responsive padding -->
    <div class="w-full px-4 md:px-6 lg:px-8 py-6 md:py-8">
        <div class="mx-auto w-full">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                
                <!-- Form Header -->
                <div class="px-4 md:px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg border border-blue-200">
                            <span class="material-icons text-blue-600 text-lg md:text-xl">
                                edit_note
                            </span>
                        </div>
                        <div>
                            <h2 class="text-base md:text-lg font-semibold text-gray-800">Informasi Materi Pembelajaran</h2>
                            <p class="text-gray-600 mt-1 text-xs md:text-sm">Ubah Materi dibawah</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-4 md:p-6 lg:p-8">
                    <form action="" method="POST" class="space-y-6 md:space-y-8">
                        <input type="hidden" name="id" value="<?= htmlspecialchars((string)$id) ?>">

                        <!-- Course Name Field -->
                        <div class="w-full">
                            <label for="nama" class="block text-sm md:text-base font-medium text-gray-700 mb-2 md:mb-3">
                                Course Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-3 md:left-4 top-1/2 transform -translate-y-1/2">
                                    <span class="material-icons text-gray-400 text-lg md:text-xl">
                                        title
                                    </span>
                                </div>
                                <input 
                                    type="text"
                                    name="nama"
                                    id="nama"
                                    value="<?= htmlspecialchars($data['nama_materi'] ?? '') ?>"
                                    required
                                    class="w-full pl-12 md:pl-14 pr-4 py-3 md:py-4 text-sm md:text-base border border-gray-300 rounded-lg md:rounded-xl focus:border-blue-500 focus:ring-3 focus:ring-blue-100 transition-all duration-200"
                                    placeholder="Enter course title"
                                    autofocus
                                >
                            </div>
                        </div>

                        <!-- Course Description Field -->
                        <div class="w-full">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 md:mb-3 gap-2">
                                <label for="deskripsi" class="block text-sm md:text-base font-medium text-gray-700">
                                    Course Description <span class="text-red-500">*</span>
                                </label>
                                <span id="charCount" class="text-xs md:text-sm text-gray-500 font-medium">
                                    <?= strlen($data['deskripsi_materi'] ?? '') ?>/5000
                                </span>
                            </div>
                            <div class="relative">
                                <div class="absolute left-3 md:left-4 top-3 md:top-4">
                                    <span class="material-icons text-gray-400 text-lg md:text-xl">
                                        description
                                    </span>
                                </div>
                                <textarea 
                                    name="deskripsi"
                                    id="deskripsi"
                                    required
                                    rows="6"
                                    class="w-full pl-12 md:pl-14 pr-4 py-3 md:py-4 text-sm md:text-base border border-gray-300 rounded-lg md:rounded-xl focus:border-blue-500 focus:ring-3 focus:ring-blue-100 transition-all duration-200 resize-none"
                                    placeholder="Describe what students will learn in this course"
                                    maxlength="5000"
                                ><?= htmlspecialchars($data['deskripsi_materi'] ?? '') ?></textarea>
                            </div>
                            <div class="mt-3 md:mt-4">
                                <div class="w-full h-2 md:h-2.5 bg-gray-100 rounded-full overflow-hidden border border-gray-200">
                                    <div id="progressBar" class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 transition-all duration-300" 
                                         style="width: <?= min((strlen($data['deskripsi_materi'] ?? '') / 5000) * 100, 100) ?>%">
                                    </div>
                                </div>
                                <p class="text-xs md:text-sm text-gray-500 mt-2">
                                    Maximum 5000 characters
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 md:pt-8 border-t border-gray-200">
                            <a href="lihatMateri.php" 
                               class="inline-flex items-center justify-center gap-2 px-5 md:px-6 py-3 text-sm md:text-base bg-gray-50 hover:bg-gray-100 border border-gray-300 hover:border-gray-400 text-gray-700 font-medium rounded-lg md:rounded-xl transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto">
                                <span class="material-icons text-base md:text-lg">
                                    close
                                </span>
                                <span>Cancel</span>
                            </a>
                            <button 
                                type="submit"
                                name="editMateri"
                                class="inline-flex items-center justify-center gap-2 px-5 md:px-6 py-3 text-sm md:text-base bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg md:rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl w-full sm:w-auto"
                            >
                                <span class="material-icons text-base md:text-lg">
                                    save
                                </span>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter with progress bar
const textarea = document.getElementById('deskripsi');
const charCount = document.getElementById('charCount');
const progressBar = document.getElementById('progressBar');
const maxChars = 5000;

function updateCharacterCounter() {
    const length = textarea.value.length;
    const percentage = Math.min((length / maxChars) * 100, 100);
    
    // Update counter
    charCount.textContent = `${length}/${maxChars}`;
    
    // Update progress bar
    progressBar.style.width = `${percentage}%`;
    
    // Update color based on length
    if (length > maxChars * 0.9) {
        charCount.classList.remove('text-gray-500', 'text-blue-500');
        charCount.classList.add('text-red-500');
        progressBar.classList.remove('bg-gradient-to-r', 'from-blue-500', 'to-indigo-500');
        progressBar.classList.add('bg-gradient-to-r', 'from-red-500', 'to-red-600');
    } else if (length > 0) {
        charCount.classList.remove('text-gray-500', 'text-red-500');
        charCount.classList.add('text-blue-600');
        progressBar.classList.remove('bg-gradient-to-r', 'from-red-500', 'to-red-600');
        progressBar.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-indigo-500');
    } else {
        charCount.classList.remove('text-blue-600', 'text-red-500');
        charCount.classList.add('text-gray-500');
    }
}

textarea.addEventListener('input', updateCharacterCounter);

// Auto-adjust textarea height
textarea.addEventListener('input', function() {
    this.style.height = 'auto';
    const newHeight = Math.min(this.scrollHeight, 400); // Max height 400px
    this.style.height = newHeight + 'px';
});

// Form validation and submission
const form = document.querySelector('form');
const submitBtn = form.querySelector('button[type="submit"]');

form.addEventListener('submit', function(e) {
    const nama = document.getElementById('nama').value.trim();
    const deskripsi = document.getElementById('deskripsi').value.trim();
    
    if (!nama || !deskripsi) {
        e.preventDefault();
        showNotification('Please fill in all required fields', 'error');
        return;
    }
    
    // Validate character limit
    if (deskripsi.length > maxChars) {
        e.preventDefault();
        showNotification(`Description must be less than ${maxChars} characters`, 'error');
        return;
    }
    
    // Show loading state
    const originalHTML = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <span class="material-icons animate-spin">refresh</span>
        <span>Saving Changes...</span>
    `;
    
    // Reset button state if form submission fails
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
    }, 3000);
});

// Notification system
function showNotification(message, type) {
    // Remove existing notifications
    document.querySelectorAll('.notification-alert').forEach(el => el.remove());
    
    const notification = document.createElement('div');
    notification.className = `
        notification-alert fixed top-4 right-4 z-50 px-4 md:px-5 py-3 md:py-4 rounded-lg shadow-lg 
        transform transition-all duration-300 animate-slideIn
        ${type === 'error' ? 
            'bg-red-50 border border-red-200 text-red-700' : 
            'bg-green-50 border border-green-200 text-green-700'
        }
    `;
    
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <div class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center rounded-full 
                ${type === 'error' ? 'bg-red-100' : 'bg-green-100'}">
                <span class="material-icons text-sm md:text-base ${type === 'error' ? 'text-red-500' : 'text-green-500'}">
                    ${type === 'error' ? 'error' : 'check_circle'}
                </span>
            </div>
            <span class="font-medium text-sm md:text-base">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Initialize character counter
    updateCharacterCounter();
    
    // Auto-adjust textarea height
    textarea.style.height = 'auto';
    const initialHeight = Math.min(textarea.scrollHeight, 400);
    textarea.style.height = initialHeight + 'px';
    
    // Check for success message in URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('msg')) {
        showNotification(decodeURIComponent(urlParams.get('msg')), 'success');
    }
    
    // Responsive adjustments
    function handleResize() {
        // Adjust textarea height on resize
        textarea.style.height = 'auto';
        const newHeight = Math.min(textarea.scrollHeight, 400);
        textarea.style.height = newHeight + 'px';
    }
    
    // Add resize listener
    window.addEventListener('resize', handleResize);
});

</script>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

.animate-slideIn {
    animation: slideIn 0.3s ease-out;
}

/* Textarea auto-resize */
textarea {
    min-height: 120px;
    max-height: 400px;
    line-height: 1.5;
}

/* Focus styles for accessibility */
button:focus, a:focus, input:focus, textarea:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .material-icons {
        font-size: 20px;
    }
    
    input, textarea {
        font-size: 16px; /* Prevents zoom on mobile */
    }
}

/* Full width container */
.container-full {
    max-width: 100%;
    width: 100%;
}

/* Smooth transitions */
* {
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Improve button touch targets on mobile */
@media (max-width: 640px) {
    button, a[role="button"] {
        min-height: 44px;
        min-width: 44px;
    }
}

/* Better scrolling on mobile */
@media (max-width: 768px) {
    body {
        -webkit-overflow-scrolling: touch;
    }
}
</style>
</body>
</html>