<?php
session_start();
require_once __DIR__ . '/../../../config.php';
// Auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
require_once ROOT_PATH . '/backend/progresMateri.php';
require_once ROOT_PATH . '/backend/submateri.php';
require_once ROOT_PATH . '/backend/activityBelajar.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';
require_once PUBLIC_PATH . '/partials/header.php';

use App\submateri\Submateri;
use App\progres\ProgressMateri;
use App\LogBelajar;

// get ids
$id_submateri = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$id_materi = isset($_GET['materi_id']) ? (int)$_GET['materi_id'] : 0;
$user_id = $_SESSION['user_id'];

if ($id_submateri <= 0 || $id_materi <= 0) {
    header("Location: listMateri.php?error=invalid_id");
    exit;
}

// init objects
$subObj = new Submateri();
$progressObj = new ProgressMateri();
$logObj = new LogBelajar($conn ?? null);

// fetch submateri
$sub = $subObj->lihatSubmateriById($id_submateri);
if (!$sub || ($sub['materi_id_materi'] ?? 0) != $id_materi) {
    header("Location: detail_materi.php?id=" . $id_materi . "&error=not_found");
    exit;
}

// navigation list (simple)
$list = $subObj->lihatSubmateriByMateri($id_materi) ?: [];
$ids = array_column($list, 'id_subMateri');
$total = count($ids);
$current_pos = array_search($id_submateri, $ids);
$current_position = ($current_pos === false) ? 1 : ($current_pos + 1);
$previous = ($current_pos > 0) ? $ids[$current_pos - 1] : null;
$next = ($current_pos !== false && $current_pos < $total - 1) ? $ids[$current_pos + 1] : null;

// mark opened (optional)
if (method_exists($progressObj, 'markSubmateriOpened')) {
    $progressObj->markSubmateriOpened($id_submateri);
}
?>

<style>
    * {
        font-family: 'Inter', sans-serif;
    }
    .gradient-bg {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .progress-bar {
        height: 8px;
        background: #E8EAF6;
        border-radius: 8px;
        overflow: hidden;
    }
    .progress-bar-inner {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #3B82F6, #60A5FA);
        transition: width 0.5s ease;
    }
    .content-container {
        min-height: 300px;
        line-height: 1.8;
        font-size: 16px;
        color: #374151;
    }
    .content-container p {
        margin-bottom: 1.5rem;
    }
    .content-container h1, 
    .content-container h2, 
    .content-container h3 {
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #1F2937;
    }
    .content-container ul, 
    .content-container ol {
        margin-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .content-container li {
        margin-bottom: 0.5rem;
    }
</style>
</head>
<body class="gradient-bg min-h-screen">
<div class="container mx-auto px-4 py-8 max-w-4xl">
    
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-6">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                            <?= htmlspecialchars($sub['nama_subMateri']) ?>
                        </h1>
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                Modul <?= $current_position ?> dari <?= $total ?>
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="materi_detail.php?id=<?= $id_materi ?>" 
                           class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200 flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
                
                <!-- Progress Section -->
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Progres Pembelajaran</span>
                        </div>
                        <span class="text-sm font-bold text-blue-600"><?= round(($current_position/$total)*100) ?>%</span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="progress-bar mb-4">
                        <div class="progress-bar-inner" style="width: <?= ($current_position/$total)*100 ?>%"></div>
                    </div>
                    
                    <!-- Progress Indicators -->
                    <div class="flex justify-between mt-2">
                        <?php for ($i = 1; $i <= $total; $i++): ?>
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 flex items-center justify-center mb-1 rounded-full <?= $i <= $current_position ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' : 'bg-gray-200 text-gray-600' ?>">
                                    <span class="text-xs font-bold">
                                        <?= $i ?>
                                    </span>
                                </div>
                                <?php if ($i == $current_position): ?>
                                    <div class="w-1 h-3 bg-blue-500 mt-1 rounded-full"></div>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-2xl shadow-lg mb-6 animate-fade-in" style="animation-delay: 100ms">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Isi Materi</h2>
            </div>
        </div>
        <div class="p-8 content-container">
            <?= nl2br(htmlspecialchars($sub['isi_materi'] ?? '')) ?>
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex flex-col sm:flex-row gap-4 animate-fade-in" style="animation-delay: 200ms">
        <?php if ($previous): ?>
            <a id="linkPrev" href="submateri_detail.php?id=<?= $previous ?>&materi_id=<?= $id_materi ?>" 
               class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 font-medium rounded-xl hover:shadow-md transition-all duration-200 border border-gray-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Sebelumnya
            </a>
        <?php else: ?>
            <button disabled class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 text-gray-400 font-medium rounded-xl cursor-not-allowed border border-gray-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Sebelumnya
            </button>
        <?php endif; ?>

        <?php if ($next): ?>
            <a id="linkNext" href="submateri_detail.php?id=<?= $next ?>&materi_id=<?= $id_materi ?>" 
               class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl hover:shadow-md transition-all duration-200">
                Selanjutnya
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </a>
        <?php else: ?>
            <a href="materi_detail.php?id=<?= $id_materi ?>" id="btnFinishMateri" 
               class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium rounded-xl hover:shadow-md transition-all duration-200">
                Selesaikan Materi
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
        <?php endif; ?>
    </div>

    <!-- QUIZ MODAL -->
<div id="quizModal" class="hidden fixed inset-0 bg-black/50 z-[9999] items-center justify-center">
  <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl p-6 mx-4">
    <div class="flex items-start justify-between gap-4">
      <div>
        <h3 class="text-xl font-bold text-gray-800">Quick Quiz</h3>
        <p class="text-sm text-gray-500">Cek pemahaman kamu dulu (±30 detik).</p>
      </div>
      <button id="quizClose" class="px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700">Tutup</button>
    </div>

    <div id="quizBody" class="mt-5"></div>

    <div class="mt-6 flex justify-end gap-2">
      <button id="quizSubmit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium">
        Submit
      </button>
      
    </div>
  </div>
</div>

    
</div>

<script>
/* =======================
   QUIZ 3 soal step-by-step
   ======================= */
const QUIZ = {
  attemptId: null,
  questions: [],
  index: 0,
  openedOnce: false
};

const redirectUrl = `materi_detail.php?id=<?= (int)$id_materi ?>`;
const SUB_ID = <?= (int)$id_submateri ?>;

function showQuizModal() {
  const m = document.getElementById('quizModal');
  if (!m) return;
  m.classList.remove('hidden');
  m.classList.add('flex');
}

function hideQuizModal() {
  const m = document.getElementById('quizModal');
  if (!m) return;
  m.classList.add('hidden');
  m.classList.remove('flex');
}

function finishQuiz() {
  // hide submit biar ga bisa dipencet pas selesai
  const btn = document.getElementById('quizSubmit');
  if (btn) btn.style.display = 'none';

  // reset supaya kalau balik lagi bisa buka quiz lagi
  QUIZ.openedOnce = false;

  hideQuizModal();
  window.location.href = redirectUrl;
}

document.getElementById('quizClose')?.addEventListener('click', () => {
  finishQuiz(); // close = langsung balik ke materi detail
});

function escapeHtml(str){
  return String(str ?? '').replace(/[&<>"']/g, s => ({
    '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'
  }[s]));
}

function renderQuestion() {
  const q = QUIZ.questions[QUIZ.index];

  // kalau sudah habis (setelah soal ke-3)
  if (!q) {
    finishQuiz();
    return;
  }

  const body = document.getElementById('quizBody');
  if (!body) return;

  body.innerHTML = `
    <div class="mb-2 text-sm text-gray-500">Soal ${QUIZ.index + 1} dari ${QUIZ.questions.length}</div>
    <div class="mb-4">
      <div class="font-semibold text-gray-800">${escapeHtml(q.question)}</div>
    </div>
    <div class="space-y-2">
      ${(q.options || []).map(o => `
        <label class="flex items-center gap-2 p-2 rounded-lg border hover:bg-gray-50 cursor-pointer">
          <input type="radio" name="quizOpt" value="${o.id}">
          <span class="text-gray-700">${escapeHtml(o.option_text)}</span>
        </label>
      `).join('')}
    </div>
    <div id="quizResult" class="mt-4"></div>
  `;

  // pastiin submit muncul kalau memang ada soal
  const btn = document.getElementById('quizSubmit');
  if (btn) btn.style.display = 'inline-flex';
}

async function openQuiz() {
  try {
    if (QUIZ.openedOnce) return;
    QUIZ.openedOnce = true;

    const res = await fetch(`/api/quiz/quizStart.php?submateri_id=${SUB_ID}`);
    const data = await res.json();

    QUIZ.attemptId = data.attempt_id ?? null;
    QUIZ.questions = data.questions || [];
    QUIZ.index = 0;

    // kalau ga ada soal sama sekali
    if (QUIZ.questions.length === 0) {
      const body = document.getElementById('quizBody');
      if (body) {
        body.innerHTML = `
          <div class="p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800">
            <div class="font-bold">Quiz belum tersedia</div>
            <div class="text-sm mt-1">Belum ada soal aktif untuk materi ini.</div>
          </div>
        `;
      }
      const btn = document.getElementById('quizSubmit');
      if (btn) btn.style.display = 'none';

      showQuizModal();
      return;
    }

    renderQuestion();
    showQuizModal();

  } catch (e) {
    console.error('openQuiz error', e);
    QUIZ.openedOnce = false;
  }
}

document.getElementById('quizSubmit')?.addEventListener('click', async () => {
  try {
    const picked = document.querySelector('input[name="quizOpt"]:checked');
    if (!picked) {
      alert('Pilih jawaban dulu ya');
      return;
    }

    const currentQ = QUIZ.questions[QUIZ.index];
    if (!currentQ) {
      finishQuiz();
      return;
    }

    // disable submit biar ga double click
    const btn = document.getElementById('quizSubmit');
    if (btn) {
      btn.disabled = true;
      btn.classList.add('opacity-60','cursor-not-allowed');
    }

    const res = await fetch('/api/quiz/quizSubmit.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        attempt_id: QUIZ.attemptId,
        question_id: parseInt(currentQ.id),
        selected_option_id: parseInt(picked.value)
      })
    });

    const result = await res.json();

    const out = document.getElementById('quizResult');
    if (out) {
      out.innerHTML = `
        <div class="p-3 rounded-xl ${result.is_correct ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'}">
          <div class="font-bold">${result.is_correct ? '✅ Benar!' : '❌ Salah'}</div>
          <div class="text-sm mt-1">${escapeHtml(result.explanation || '')}</div>
        </div>
      `;
    }

    // next question (delay biar user lihat hasil)
    setTimeout(() => {
      QUIZ.index++;
      renderQuestion();

      // enable submit lagi kalau masih ada soal
      if (btn) {
        btn.disabled = false;
        btn.classList.remove('opacity-60','cursor-not-allowed');
      }
    }, 900);

  } catch (e) {
    console.error('submitQuiz error', e);
    const btn = document.getElementById('quizSubmit');
    if (btn) {
      btn.disabled = false;
      btn.classList.remove('opacity-60','cursor-not-allowed');
    }
  }
});

/* =======================
   AUTO LOG BELAJAR (punyamu)
   ======================= */
const auto = (function(){
  let currentLogId = window.currentLogId || null;
  const MATERI_ID = <?= (int)$id_materi ?>;
  const SUB_ID_LOCAL = <?= (int)$id_submateri ?>;

  async function postJson(url, data) {
    const body = new URLSearchParams(data).toString();
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body
    });
    const text = await res.text();
    try { return JSON.parse(text); }
    catch (e) { throw new Error('Non-JSON response from ' + url + ': ' + text.slice(0,200)); }
  }

  async function startLearningLocal(subId = SUB_ID_LOCAL) {
    try {
      const j = await postJson('/api/log/start_log.php', { materi_id: MATERI_ID, submateri_id: subId });
      if (j.success) {
        currentLogId = j.log_id;
        window.currentLogId = currentLogId;
        return currentLogId;
      }
      return null;
    } catch (err) {
      console.error('startLearningLocal error', err);
      return null;
    }
  }

  async function endLearningLocal() {
    if (!currentLogId) return false;
    try {
      const j = await postJson('/api/log/end_log.php', { log_id: currentLogId });
      if (j.success) {
        currentLogId = null;
        window.currentLogId = null;
        return true;
      }
      return false;
    } catch (err) {
      console.error('endLearningLocal error', err);
      return false;
    }
  }

  async function startLearningWrapper(subId) {
    if (typeof window.startLearning === 'function') {
      try {
        await window.startLearning(subId);
        currentLogId = window.currentLogId || currentLogId;
        return currentLogId;
      } catch (e) {}
    }
    return startLearningLocal(subId);
  }

  async function endLearningWrapper() {
    if (typeof window.endLearning === 'function') {
      try {
        await window.endLearning();
        currentLogId = window.currentLogId || null;
        return true;
      } catch (e) {}
    }
    return endLearningLocal();
  }

  // tombol selesai materi => end log => buka quiz
  document.getElementById('btnFinishMateri')?.addEventListener('click', async (e) => {
    e.preventDefault();
    await endLearningWrapper();
    QUIZ.openedOnce = false;
    openQuiz();
  });

  document.addEventListener('DOMContentLoaded', function() {
    if (window.currentLogId) {
      currentLogId = window.currentLogId;
      return;
    }
    startLearningWrapper(SUB_ID_LOCAL).catch(()=>{});
  });

  function interceptLink(idSelector) {
    const el = document.getElementById(idSelector);
    if (!el) return;
    el.addEventListener('click', function(e){
      if (!currentLogId) return;
      e.preventDefault();
      const href = this.href;
      endLearningWrapper().finally(() => { window.location.href = href; });
    });
  }
  interceptLink('linkPrev');
  interceptLink('linkNext');

  window.addEventListener('beforeunload', function() {
    if (!currentLogId) return;
    try {
      const data = new URLSearchParams({ log_id: currentLogId });
      navigator.sendBeacon('/api/log/end_log.php', data);
    } catch (e) {}
  });

  return {
    getCurrentLogId: () => currentLogId,
    start: () => startLearningWrapper(SUB_ID_LOCAL),
    end: () => endLearningWrapper()
  };
})();
</script>

</body>
</html>