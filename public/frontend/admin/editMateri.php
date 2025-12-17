<?php
require_once __DIR__ . "/../../../backend/AuthMiddleware.php";
use App\AuthMiddleware;

AuthMiddleware::authAdmin();

require_once __DIR__ . "/../../../backend/materi.php";
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

// Buat tampilan URL YouTube yang enak dibaca (DB simpan ID saja)
$ytDisplay = '';
if (!empty($data['playlist_id'])) {
    if (($data['playlist_type'] ?? 'video') === 'playlist') {
        $ytDisplay = "https://www.youtube.com/playlist?list=" . $data['playlist_id'];
    } else {
        $ytDisplay = "https://youtu.be/" . $data['playlist_id'];
    }
}

// Jika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $ytURL = $_POST['ytURL'] ?? '';

    $result = $tambah_materi->updateMateri((int)$id, $nama, $deskripsi, $ytURL);

    header('Location: lihatMateri.php?msg=' . urlencode($result['message']));
    exit;
}

require_once __DIR__ . '/../../assets/layout/header.php';
//require_once __DIR__ . '/../../assets/layout/sbAdmin.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Course</title>
    <!-- Include Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-slate-100 lg:ml-64 transition-all duration-300">

<div class="p-4 md:p-6 lg:p-8">
    
    <!-- Header Section -->
    <div class="max-w-4xl mx-auto mb-8">
        <div class="flex items-center gap-4 mb-6">
            <a href="lihatMateri.php" 
               class="group p-2.5 bg-gradient-to-r from-white to-gray-50 border border-gray-200 hover:border-gray-300 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                <span class="material-icons text-gray-600 group-hover:text-cyan-600 transition-colors">
                    arrow_back
                </span>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-cyan-700 to-blue-700 bg-clip-text text-transparent">
                    Edit Course
                </h1>
                <p class="text-gray-600 mt-2 font-light">Update course information and content</p>
            </div>
        </div>
        
        <!-- Progress Indicator -->
        <div class="flex items-center justify-between max-w-2xl mb-8">
            <div class="flex items-center">
                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-gradient-to-r from-cyan-600 to-blue-600 text-white font-semibold shadow-md">
                    <span class="material-icons text-sm">edit</span>
                </div>
                <div class="h-1 w-24 bg-gradient-to-r from-cyan-600 to-blue-600"></div>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-gradient-to-r from-gray-200 to-gray-300 text-gray-500 font-semibold">
                    <span class="material-icons text-sm">preview</span>
                </div>
                <div class="h-1 w-24 bg-gradient-to-r from-gray-200 to-gray-300"></div>
            </div>
            <div class="flex items-center">
                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-gradient-to-r from-gray-200 to-gray-300 text-gray-500 font-semibold">
                    <span class="material-icons text-sm">check</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-br from-white to-gray-50/50 rounded-2xl shadow-xl border border-gray-200/60 overflow-hidden">
            
            <!-- Form Header -->
            <div class="px-6 py-5 border-b border-gray-200/60 bg-gradient-to-r from-cyan-50/30 to-blue-50/30">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-xl border border-cyan-200 shadow-sm">
                        <span class="material-icons text-xl text-cyan-600">
                            edit_note
                        </span>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Edit Course Information</h2>
                        <p class="text-sm text-gray-600 mt-1 font-light">Update the course details below</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6 md:p-8">
                <form action="" method="POST" class="space-y-8">
                    <input type="hidden" name="id" value="<?= htmlspecialchars((string)$id) ?>">

                    <!-- Course Information Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-gradient-to-b from-cyan-400 to-blue-400 rounded-full"></div>
                            <h3 class="text-lg font-semibold text-gray-800">Course Details</h3>
                        </div>
                        
                        <div class="space-y-6 pl-4">
                            <!-- Course Name Field -->
                            <div class="group">
                                <div class="flex items-center justify-between mb-3">
                                    <label for="nama" class="block text-sm font-medium text-gray-700">
                                        Course Name *
                                    </label>
                                    <span class="text-xs text-gray-400">Required</span>
                                </div>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                        <span class="material-icons text-gray-400 group-focus-within:text-cyan-500 transition-colors">
                                            title
                                        </span>
                                    </div>
                                    <input 
                                        type="text"
                                        name="nama"
                                        id="nama"
                                        value="<?= htmlspecialchars($data['nama_materi'] ?? '') ?>"
                                        required
                                        class="form-input w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200/50 transition-all duration-300 bg-white/50"
                                        autofocus
                                        placeholder="Enter course title"
                                    >
                                </div>
                                <p class="mt-2 text-sm text-gray-500 flex items-center gap-2">
                                    <span class="material-icons text-xs text-cyan-500">
                                        info
                                    </span>
                                    Update the course title as needed
                                </p>
                            </div>

                            <!-- Course Description Field -->
                            <div class="group">
                                <div class="flex items-center justify-between mb-3">
                                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                                        Course Description *
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <span id="charCount" class="text-sm text-gray-400">
                                            <?= strlen($data['deskripsi_materi'] ?? '') ?>/5000
                                        </span>
                                        <span class="text-xs text-gray-400">Required</span>
                                    </div>
                                </div>
                                <div class="relative">
                                    <div class="absolute left-4 top-4">
                                        <span class="material-icons text-gray-400 group-focus-within:text-cyan-500 transition-colors">
                                            description
                                        </span>
                                    </div>
                                    <textarea 
                                        name="deskripsi"
                                        id="deskripsi"
                                        required
                                        rows="6"
                                        class="form-input w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200/50 transition-all duration-300 bg-white/50 resize-none"
                                        placeholder="Describe what students will learn in this course"
                                        maxlength="5000"
                                    ><?= htmlspecialchars($data['deskripsi_materi'] ?? '') ?></textarea>
                                </div>
                                <div class="mt-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <p class="text-gray-500 flex items-center gap-2">
                                            <span class="material-icons text-xs text-cyan-500">
                                                lightbulb
                                            </span>
                                            Update the learning objectives and description
                                        </p>
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                                <div id="progressBar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-400 transition-all duration-300" 
                                                     style="width: <?= min((strlen($data['deskripsi_materi'] ?? '') / 5000) * 100, 100) ?>%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200/60"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-4 bg-gradient-to-br from-white to-gray-50 text-sm text-gray-400">
                                Review Changes
                            </span>
                        </div>
                    </div>

                    <!-- Summary of Changes -->
                    <div class="bg-gradient-to-r from-blue-50/30 to-cyan-50/30 border border-blue-200/50 rounded-xl p-5">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 p-2 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-lg">
                                <span class="material-icons text-blue-600 text-sm">
                                    insights
                                </span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-700 mb-2">Update Summary</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500 mb-1">Current Course:</p>
                                        <p class="font-medium text-gray-800"><?= htmlspecialchars($data['nama_materi'] ?? 'Untitled') ?></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 mb-1">Character Count:</p>
                                        <p class="font-medium text-gray-800"><?= strlen($data['deskripsi_materi'] ?? '') ?> characters</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200/60">
                        
                        
                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            <a href="lihatMateri.php" 
                               class="group flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-white to-gray-50 hover:from-gray-100 hover:to-gray-200 border border-gray-300 hover:border-gray-400 text-gray-700 font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                                <span class="material-icons text-base">
                                    close
                                </span>
                                <span>Cancel</span>
                            </a>
                            <button 
                                type="submit"
                                name="editMateri"
                                class="group flex-1 sm:flex-none inline-flex items-center justify-center gap-3 px-8 py-3.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0"
                            >
                                <span class="material-icons text-lg">
                                    save
                                </span>
                                <span>Save Changes</span>
                                <span class="material-icons text-sm group-hover:translate-x-1 transition-transform">
                                    arrow_forward
                                </span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <!-- Quick Tips -->
        
            
            
            
            
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
        charCount.classList.remove('text-gray-400', 'text-cyan-500');
        charCount.classList.add('text-red-500');
        progressBar.classList.remove('bg-gradient-to-r', 'from-cyan-400', 'to-blue-400');
        progressBar.classList.add('bg-gradient-to-r', 'from-red-400', 'to-red-500');
    } else if (length > 0) {
        charCount.classList.remove('text-gray-400', 'text-red-500');
        charCount.classList.add('text-cyan-500');
        progressBar.classList.remove('bg-gradient-to-r', 'from-red-400', 'to-red-500');
        progressBar.classList.add('bg-gradient-to-r', 'from-cyan-400', 'to-blue-400');
    } else {
        charCount.classList.remove('text-cyan-500', 'text-red-500');
        charCount.classList.add('text-gray-400');
    }
}

textarea.addEventListener('input', updateCharacterCounter);

// Auto-adjust textarea height
textarea.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
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
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <span class="material-icons animate-spin">refresh</span>
        <span>Saving Changes...</span>
    `;
});

// Input focus effects
const inputs = document.querySelectorAll('input, textarea');
inputs.forEach(input => {
    input.addEventListener('focus', function() {
        const isYouTube = this.id === 'ytURL';
        this.parentElement.classList.add('ring-2', isYouTube ? 'ring-purple-200/50' : 'ring-cyan-200/50');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('ring-2', 'ring-cyan-200/50', 'ring-purple-200/50');
    });
});

// Notification system
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `
        fixed top-4 right-4 z-50 px-5 py-4 rounded-xl shadow-lg 
        transform transition-all duration-300 animate-slideIn
        ${type === 'error' ? 
            'bg-gradient-to-r from-red-50 to-red-100 border border-red-200 text-red-700' : 
            'bg-gradient-to-r from-green-50 to-emerald-100 border border-green-200 text-green-700'
        }
    `;
    
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 flex items-center justify-center rounded-full 
                ${type === 'error' ? 'bg-red-100' : 'bg-green-100'}">
                <span class="material-icons ${type === 'error' ? 'text-red-500' : 'text-green-500'}">
                    ${type === 'error' ? 'error' : 'check_circle'}
                </span>
            </div>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.classList.add('opacity-0', 'translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Initialize character counter
    updateCharacterCounter();
    
    // Auto-adjust textarea height
    textarea.style.height = 'auto';
    textarea.style.height = (textarea.scrollHeight) + 'px';
    
    // Check for success message in URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('msg')) {
        showNotification(decodeURIComponent(urlParams.get('msg')), 'success');
    }
});

</script>

<style>
/* Custom animations */
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

.animate-slideIn {
    animation: slideIn 0.3s ease-out;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Form input focus styles */
.form-input:focus {
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
}

/* Smooth transitions */
* {
    transition-property: color, background-color, border-color, transform, box-shadow;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .lg\:ml-64 {
        margin-left: 0;
    }
    
    .text-2xl {
        font-size: 1.5rem;
    }
    
    .text-3xl {
        font-size: 1.75rem;
    }
    
    .px-8 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}

/* Material icons sizing */
.material-icons {
    font-size: inherit;
}

/* Textarea auto-resize */
textarea {
    min-height: 150px;
    max-height: 400px;
}

/* Focus styles for accessibility */
button:focus, a:focus, input:focus, textarea:focus {
    outline: 2px solid #06b6d4;
    outline-offset: 2px;
}

/* Glass effect */
.bg-white\/50 {
    background-color: rgba(255, 255, 255, 0.5);
}
</style>
</body>
</html>