<?php

require_once __DIR__ . "/../../../backend/AuthMiddleware.php";
use App\AuthMiddleware;
AuthMiddleware::authAdmin();

require_once __DIR__ . "/../../../backend/materi.php";
use App\Materi\Materi;

$tambah_materi = new Materi(); 

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: /finalProject/frontend/login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tambah_materi->tambahMateri($_POST['nama'], $_POST['deskripsi'], $_POST['ytURL']);
    header ('Location: lihatMateri.php');
}

require_once __DIR__ . '/../../assets/layout/header.php';
require_once __DIR__ . '/../../assets/layout/sbAdmin.php';
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-slate-100 lg:ml-64 transition-all duration-300">
    
    <!-- Main Container -->
    <div class="p-4 md:p-6 lg:p-8">
        
        <!-- Form Header -->
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
                        Buat Materi Pembelajaran Baru
                    </h1>
                    <p class="text-gray-600 mt-2 font-light">Isi Form dibawah untuk membuat Materi baru</p>
                </div>
            </div>
            
            <!-- Progress Steps -->
            
        </div>

        <!-- Form Card -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-gradient-to-br from-white to-gray-50/50 rounded-2xl shadow-xl border border-gray-200/60 overflow-hidden">
                
                <!-- Form Header -->
            

                <!-- Form Content -->
                <div class="p-6 md:p-8">
                    <form action="tambahMateri.php" method="POST" class="space-y-8">
                        
                        <!-- Course Information Section -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-6 bg-gradient-to-b from-cyan-400 to-blue-400 rounded-full"></div>
                                <h3 class="text-lg font-semibold text-gray-800">Informasi Materi</h3>
                            </div>
                            
                            <div class="space-y-6 pl-4">
                                <!-- Course Name Field -->
                                <div class="group">
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
                                            required
                                            class="form-input w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200/50 transition-all duration-300 bg-white/50"
                                            autofocus
                                            placeholder="Masukan Judul Materi Pembelajaran...">
                                    </div>
                                </div>

                                <!-- Course Description Field -->
                                <div class="group">
                                    <div class="flex items-center justify-between mb-3">
                                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                                            Deskripsi Materi
                                        </label>
                                        <div class="flex items-center gap-2">
                                            <span id="charCount" class="text-sm text-gray-400">0/5000</span>
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
                                            placeholder="deskripsi materi pembelajaran..."
                                            maxlength="5000"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        

                        <!-- Form Notes -->
                        

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200/60">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-gradient-to-br from-green-100 to-emerald-100 border border-green-200">
                                    <span class="material-icons text-green-500 text-sm">
                                        check_circle
                                    </span>
                                </div>
                                <span class="font-medium">Periksa kembali informasi Anda sebelum mengirimkan</span>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                <a href="lihatMateri.php" 
                                   class="group flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-white to-gray-50 hover:from-gray-100 hover:to-gray-200 border border-gray-300 hover:border-gray-400 text-gray-700 font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                                    <span class="material-icons text-base">
                                        close
                                    </span>
                                    <span>Batal</span>
                                </a>
                                <button 
                                    type="submit" 
                                    name="tambahMateri"
                                    class="group flex-1 sm:flex-none inline-flex items-center justify-center gap-3 px-8 py-3.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0"
                                >
                                    <span class="material-icons text-lg">
                                        add_circle
                                    </span>
                                    <span>Tambah Materi</span>
                                    <span class="material-icons text-sm group-hover:translate-x-1 transition-transform">
                                        arrow_forward
                                    </span>
                                </button>
                            </div>
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

textarea.addEventListener('input', function() {
    const length = this.value.length;
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
    
    // Auto-adjust height
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
        <span>Creating Course...</span>
    `;
});

// Input focus effects
const inputs = document.querySelectorAll('input, textarea');
inputs.forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('ring-2', 'ring-cyan-200/50');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('ring-2', 'ring-cyan-200/50');
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

// Form persistence (optional - for better UX)
window.addEventListener('beforeunload', function(e) {
    const nama = document.getElementById('nama').value;
    const deskripsi = document.getElementById('deskripsi').value;
    
    if (nama || deskripsi) {
        localStorage.setItem('draft_course_name', nama);
        localStorage.setItem('draft_course_desc', deskripsi);
    }
});

// Load draft on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedName = localStorage.getItem('draft_course_name');
    const savedDesc = localStorage.getItem('draft_course_desc');
    
    if (savedName) {
        document.getElementById('nama').value = savedName;
    }
    if (savedDesc) {
        document.getElementById('deskripsi').value = savedDesc;
        textarea.dispatchEvent(new Event('input'));
    }
    
    // Clear draft on successful submission
    if (window.location.search.includes('success')) {
        localStorage.removeItem('draft_course_name');
        localStorage.removeItem('draft_course_desc');
        showNotification('Course created successfully!', 'success');
    }
});

// Initialize auto-height for textarea
textarea.dispatchEvent(new Event('input'));
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

/* Custom scrollbar */
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

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #94a3b8, #64748b);
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

/* Hover effects */
.group:hover .group-hover\:border-cyan-200 {
    transition: border-color 200ms ease;
}

/* Glass effect */
.bg-white\/50 {
    background-color: rgba(255, 255, 255, 0.5);
}
</style>
</body>
</html>