<?php
require_once __DIR__ . "/../../config.php";
require_once ROOT_PATH . "/backend/subMateri.php";
require_once ROOT_PATH . "/backend/materi.php";
require_once ROOT_PATH . "/backend/AuthMiddleware.php";

use App\AuthMiddleware;
AuthMiddleware::authAdmin();
use App\submateri\Submateri;
use App\materi\Materi;

$materiModel = new Materi();
$id = $_GET['id']; // id materi
$materiId = $id;

$listSubmateri = new Submateri(); 
$subMateri = $listSubmateri->lihatSubmateriByMateri($id);

$materi = $materiModel->getMateriById($id); 
$namaMateri = $materi['data']['nama_materi'];


require_once PUBLIC_PATH . '/partials/header.php';
require_once PUBLIC_PATH . '/partials/sbAdmin.php';

?>

<!-- Main Content Area -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-slate-100 lg:ml-64 transition-all duration-300">

    <!-- Header -->
    <div class="sticky top-0 z-20 bg-white/80 backdrop-blur-sm border-b border-gray-200/60 px-6 py-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-cyan-100 to-blue-100 border border-cyan-200 shadow-sm">
                            <span class="material-icons text-xl text-cyan-600">
                                layers
                            </span>
                        </div>
                        <div>
                            <h2 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-cyan-700 to-blue-700 bg-clip-text text-transparent">
                                Materi: <?= htmlspecialchars($namaMateri) ?>
                            </h2>
                            <p class="text-sm text-gray-600 mt-1 font-light">
                                Kelola Submateri
                            </p>

                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="tambahSubmateri.php?id=<?= $id ?>" 
                       class="group inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <span class="material-icons text-lg mr-2">
                            add_circle
                        </span>
                        <span>Tambah Submateri Baru</span>
                        <span class="material-icons text-sm ml-2 group-hover:translate-x-1 transition-transform">
                            arrow_forward
                        </span>
                    </a>
                    <a href="lihatMateri.php" 
                       class="group inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-50 hover:from-gray-200 hover:to-gray-100 border border-gray-300 hover:border-gray-400 text-gray-700 font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                        <span class="material-icons text-lg mr-2">
                            arrow_back
                        </span>
                        <span>Kembali ke materi</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4 py-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Container Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 xl:gap-8">

                <!-- Modules List (Left Panel) -->
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-white to-gray-50/50 rounded-2xl shadow-lg border border-gray-200/60 p-5 h-full">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Modules List</h3>
                            <div class="inline-flex items-center px-3 py-1.5 bg-cyan-50 text-cyan-700 rounded-full text-sm font-medium">
                                <span class="w-2 h-2 bg-cyan-400 rounded-full mr-2 animate-pulse"></span>
                                <?= count($subMateri) ?> submateri
                            </div>
                        </div>
                        
                        <?php if (!empty($subMateri)): ?>
                            <div class="space-y-3 max-h-[calc(100vh-280px)] overflow-y-auto pr-2 custom-scrollbar">
                                <?php foreach ($subMateri as $index => $s): ?>
                                    <div 
                                        onclick="showDetail(
                                            '<?= htmlspecialchars($s['nama_subMateri']) ?>',
                                            `<?= htmlspecialchars($s['isi_materi']) ?>`,
                                            '<?= $s['id_subMateri'] ?>',
                                            <?= $index ?>
                                        )"
                                        class="group relative bg-gradient-to-r from-white to-gray-50/80 border border-gray-200/80 rounded-xl p-4 pl-6 hover:border-cyan-300/60 hover:shadow-lg hover:bg-gradient-to-r hover:from-cyan-50/30 hover:to-blue-50/30 cursor-pointer transition-all duration-300 transform hover:-translate-y-0.5"
                                        id="card-<?= $index ?>"
                                    >
                                        <!-- Module Indicator -->
                                        <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-1 h-10 bg-gradient-to-b from-cyan-400 to-blue-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-cyan-100 to-blue-100">
                                                        <span class="material-icons text-sm text-cyan-600">
                                                            menu_book
                                                        </span>
                                                    </div>
                                                    <h4 class="font-semibold text-gray-800 text-base mb-1 line-clamp-1 group-hover:text-cyan-700">
                                                        <?= htmlspecialchars($s['nama_subMateri']) ?>
                                                    </h4>
                                                </div>
                                                <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                                    <?= htmlspecialchars(substr($s['isi_materi'], 0, 100)) ?>
                                                    <?= strlen($s['isi_materi']) > 100 ? '...' : '' ?>
                                                </p>
                                                
                                                <!-- Module Meta -->
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <div class="flex items-center gap-1 mr-3">
                                                        <span class="material-icons text-xs">
                                                            schedule
                                                        </span>
                                                        <span>Modul <?= $index + 1 ?></span>
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <span class="material-icons text-xs">
                                                            format_size
                                                        </span>
                                                        <span><?= ceil(strlen($s['isi_materi']) / 1000) ?>k chars</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="material-icons text-gray-400 group-hover:text-cyan-500 transition-colors">
                                                chevron_right
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <!-- Empty State -->
                            <div class="text-center py-8">
                                <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                    <span class="material-icons text-3xl text-gray-400">
                                        playlist_add
                                    </span>
                                </div>
                                <h4 class="text-lg font-medium text-gray-700 mb-2">Belum Ada Submateri</h4>
                                <p class="text-gray-500 text-sm mb-6 max-w-xs mx-auto">
                                    Mulai Buat Submateri
                                </p>
                                <a href="tambahSubmateri.php?id=<?= $id ?>" 
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-medium rounded-lg transition-all duration-300">
                                    <span class="material-icons">add</span>
                                    Buat Submateri Pertama
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Detail Panel (Right Panel - 2/3 width) -->
                <div class="lg:col-span-2">
                    <div id="detailPanel" class="bg-gradient-to-br from-white to-gray-50/50 rounded-2xl shadow-lg border border-gray-200/60 p-6 h-full min-h-[500px] transition-all duration-500 opacity-0 translate-x-8">
                        
                        <!-- Empty State -->
                        <div id="emptyState" class="flex flex-col items-center justify-center h-full py-12">
                            <div class="relative mb-6">
                                <div class="w-24 h-24 bg-gradient-to-br from-cyan-50 to-blue-50 rounded-full flex items-center justify-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-full flex items-center justify-center">
                                        <span class="material-icons text-3xl text-cyan-400">
                                            find_in_page
                                        </span>
                                    </div>
                                </div>
                                <div class="absolute -top-2 -right-2 w-10 h-10 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center shadow-md">
                                    <span class="material-icons text-lg text-purple-400">
                                        search
                                    </span>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Pilih Modul</h3>
                            <p class="text-gray-500 text-center max-w-md">
                                Pilih modul, lihat detail dan edit.
                            </p>
                            <div class="mt-6 flex items-center gap-2 text-sm text-gray-400">
                                <span class="material-icons text-base">
                                    info
                                </span>
                                <span>klil modul apapun untuk memulai</span>
                            </div>
                        </div>

                        <!-- Detail Content -->
                        <div id="detailContent" class="hidden">
                            <!-- Header -->
                            <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 mb-8 pb-6 border-b border-gray-200/60">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-cyan-100 to-blue-100 border border-cyan-200">
                                            <span class="material-icons text-lg text-cyan-600">
                                                description
                                            </span>
                                        </div>
                                        <div>
                                            <h2 id="detailTitle" class="text-2xl font-bold text-gray-800 break-words"></h2>
                                            <div id="detailMeta" class="flex items-center gap-3 mt-2 text-sm text-gray-500">
                                                <!-- Meta info will be added dynamically -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2">
                                    <a id="editLink" 
                                       class="group/edit inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-cyan-50 to-blue-50 hover:from-cyan-100 hover:to-blue-100 border border-cyan-300 hover:border-cyan-400 text-cyan-700 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                                        <span class="material-icons text-base">edit</span>
                                        <span class="font-medium">Edit</span>
                                    </a>
                                    <a id="hapusLink" 
                                       class="group/delete inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-red-50 to-pink-50 hover:from-red-100 hover:to-pink-100 border border-red-300 hover:border-red-400 text-red-700 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
                                       onclick="return confirm('Are you sure you want to delete this module? This action cannot be undone.')">
                                        <span class="material-icons text-base">delete</span>
                                        <span class="font-medium">Hapus</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Body Content -->
                            <div class="prose max-w-none">
                                <div class="bg-gradient-to-br from-gray-50 to-white/50 rounded-xl border border-gray-200 p-6">
                                    <div id="detailBody" class="text-gray-700 leading-relaxed whitespace-pre-line"></div>
                                </div>
                                
                                <!-- Action Footer -->
                                <div class="mt-8 pt-6 border-t border-gray-200/60">
                                    <div class="flex flex-wrap gap-3">
                                        <a id="editLink2" 
                                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg transition-all duration-300">
                                            <span class="material-icons">edit_note</span>
                                            Edit Submateri
                                        </a>
                                        <a id="hapusLink2" 
                                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-300"
                                           onclick="return confirm('yakin untuk menghapus submateri ini? aksi ini tidak bisa di undo')">
                                            <span class="material-icons">delete_forever</span>
                                            hapus submateri
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
let activeCardIndex = null;

function showDetail(title, body, idSub, index) {
    const panel = document.getElementById('detailPanel');
    const empty = document.getElementById('emptyState');
    const content = document.getElementById('detailContent');
    
    // Remove active class from all cards
    document.querySelectorAll('[id^="card-"]').forEach(card => {
        card.classList.remove('border-cyan-300/60', 'bg-gradient-to-r', 'from-cyan-50/50', 'to-blue-50/50', 'shadow-lg');
    });
    
    // Add active class to clicked card
    const activeCard = document.getElementById(`card-${index}`);
    if (activeCard) {
        activeCard.classList.add('border-cyan-300/60', 'bg-gradient-to-r', 'from-cyan-50/50', 'to-blue-50/50', 'shadow-lg');
    }
    
    // Show content and hide empty state
    empty.classList.add('hidden');
    content.classList.remove('hidden');
    
    // Set content
    document.getElementById('detailTitle').textContent = title;
    document.getElementById('detailBody').textContent = body;
    
    // Update meta information
    const charCount = body.length;
    const wordCount = body.split(/\s+/).filter(word => word.length > 0).length;
    document.getElementById('detailMeta').innerHTML = `
        <div class="flex items-center gap-1">
            <span class="material-icons text-xs">schedule</span>
            <span>Module ${index + 1}</span>
        </div>
        <div class="flex items-center gap-1">
            <span class="material-icons text-xs">format_size</span>
            <span>${wordCount} words</span>
        </div>
        <div class="flex items-center gap-1">
            <span class="material-icons text-xs">text_fields</span>
            <span>${charCount.toLocaleString()} chars</span>
        </div>
    `;
    
    // Set links
    document.getElementById('editLink').href = 
        "updateSubmateri.php?id=" + idSub + "&materi=<?= $materiId ?>";
    document.getElementById('hapusLink').href = 
        "hapusSubmateri.php?id=" + idSub + "&materi=<?= $materiId ?>";
    document.getElementById('editLink2').href = 
        "updateSubmateri.php?id=" + idSub + "&materi=<?= $materiId ?>";
    document.getElementById('hapusLink2').href = 
        "hapusSubmateri.php?id=" + idSub + "&materi=<?= $materiId ?>";
    
    // Animate panel
    panel.classList.remove('opacity-0', 'translate-x-8');
    panel.classList.add('opacity-100', 'translate-x-0');
    
    // Update active index
    activeCardIndex = index;
    
    // Scroll to top of detail panel on mobile
    if (window.innerWidth < 1024) {
        panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-select first card if exists
    const cards = document.querySelectorAll('[id^="card-"]');
    if (cards.length > 0) {
        setTimeout(() => {
            cards[0].click();
        }, 300);
    }
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!activeCardIndex) return;
        
        const cards = document.querySelectorAll('[id^="card-"]');
        if (e.key === 'ArrowDown' && activeCardIndex < cards.length - 1) {
            cards[activeCardIndex + 1].click();
            e.preventDefault();
        } else if (e.key === 'ArrowUp' && activeCardIndex > 0) {
            cards[activeCardIndex - 1].click();
            e.preventDefault();
        }
    });
});
</script>

<style>
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

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

.animate-fadeOut {
    animation: fadeOut 0.3s ease-out;
}

/* Card animations */
[id^="card-"] {
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
    animation-delay: calc(var(--card-index, 0) * 50ms);
}

/* Hover effects */
.group:hover .group-hover\:text-cyan-700 {
    transition: color 200ms ease;
}

/* Material icons sizing */
.material-icons {
    font-size: inherit;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .lg\:ml-64 {
        margin-left: 0;
    }
    
    .sticky {
        position: static;
    }
}

@media (max-width: 768px) {
    .text-2xl {
        font-size: 1.5rem;
    }
    
    .text-3xl {
        font-size: 1.75rem;
    }
}

/* Focus styles for accessibility */
a:focus, button:focus {
    outline: 2px solid #06b6d4;
    outline-offset: 2px;
}

/* Glass effect */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
}

/* Smooth transitions */
* {
    transition-property: color, background-color, border-color, transform, box-shadow, opacity;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Prose styling */
.prose {
    line-height: 1.75;
}

.prose p {
    margin-bottom: 1em;
}

.prose p:last-child {
    margin-bottom: 0;
}
</style>

</body>
</html>