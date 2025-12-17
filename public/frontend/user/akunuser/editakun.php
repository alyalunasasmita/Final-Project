<?php
use App\AuthMiddleware;
use App\User;

require_once __DIR__."/../../../../backend/AuthMiddleware.php";
require_once __DIR__. '/../../../../backend/userAcc.php';


$userAuth = AuthMiddleware::authUser();

$userModel = new User();
$message = null;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'id'       => $userAuth['id'], // ⛔ dari session
        'nama'     => trim($_POST['nama']),
        'email'    => strtolower(trim($_POST['email'])),
        'username' => trim($_POST['username'])
    ];

    $result = $userModel->updateById($data);

    if ($result) {
    AuthMiddleware::logCRUD('update', 'akun', $userAuth['id']);

        header("Location: lihatakun.php");
        exit;
    } else {
        $message = "Email tidak valid atau sudah digunakan";
        $error = true;
    }

}
$userData = $userModel->getById($userAuth['id']);
require_once __DIR__ . '/../../../assets/layout/header.php'
?>

<body class="min-h-screen bg-gradient-to-br from-teal-50 to-cyan-50">
    
    <!-- CONTAINER UTAMA -->
    <div class="container mx-auto px-4 py-8">
        
        <!-- ARROW BACK - DI ATAS FORM -->
        <div class="mb-6">
            <a href="lihatakun.php" class="inline-flex items-center text-teal-600 hover:text-teal-800 pixel-text">
                <span class="material-icons">arrow_back</span>
                <span class="ml-2">Kembali ke Profil</span>
            </a>
        </div>

        <!-- FORM CARD - DI BAWAH ARROW -->
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <div class="p-6 bg-gradient-to-r from-teal-50 to-white border-2 border-teal-100 rounded-xl shadow-lg hover:border-teal-300 transition-all duration-300">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pixel-text text-center">Edit Profil</h2>

                    <!-- ALERT -->
                    <?php if ($message): ?>
                        <div class="mb-5 p-4 rounded-lg border-2 <?= $error ? 'border-red-200 bg-red-50' : 'border-emerald-200 bg-emerald-50' ?>">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br <?= $error ? 'from-red-100 to-red-50 border-red-300' : 'from-emerald-100 to-emerald-50 border-emerald-300' ?> border-2 flex items-center justify-center rounded-md">
                                    <span class="material-icons text-sm <?= $error ? 'text-red-500' : 'text-emerald-500' ?>">
                                        <?= $error ? 'error' : 'check_circle' ?>
                                    </span>
                                </div>
                                <p class="font-medium pixel-text <?= $error ? 'text-red-700' : 'text-emerald-700' ?>">
                                    <?= htmlspecialchars($message) ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- FORM -->
                    <form method="POST" class="space-y-5">
                        <!-- Nama Field -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-teal-100 to-teal-50 border-2 border-teal-300 flex items-center justify-center rounded-md">
                                    <span class="material-icons text-teal-500 text-sm">person</span>
                                </div>
                                <label class="text-sm text-gray-500 pixel-text">Nama Lengkap</label>
                            </div>
                            <input type="text" name="nama" required
                                   value="<?= htmlspecialchars($userData['nama']) ?>"
                                   class="w-full px-4 py-3 border-2 border-teal-100 rounded-lg focus:border-teal-300 focus:ring-2 focus:ring-teal-100 focus:outline-none transition-all duration-200 pixel-text bg-white">
                        </div>

                        <!-- Email Field -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-teal-100 to-teal-50 border-2 border-teal-300 flex items-center justify-center rounded-md">
                                    <span class="material-icons text-teal-500 text-sm">email</span>
                                </div>
                                <label class="text-sm text-gray-500 pixel-text">Email Address</label>
                            </div>
                            <input type="email" name="email" required
                                   value="<?= htmlspecialchars($userData['email']) ?>"
                                   class="w-full px-4 py-3 border-2 border-teal-100 rounded-lg focus:border-teal-300 focus:ring-2 focus:ring-teal-100 focus:outline-none transition-all duration-200 pixel-text bg-white">
                        </div>

                        <!-- Username Field -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-teal-100 to-teal-50 border-2 border-teal-300 flex items-center justify-center rounded-md">
                                    <span class="material-icons text-teal-500 text-sm">alternate_email</span>
                                </div>
                                <label class="text-sm text-gray-500 pixel-text">Username</label>
                            </div>
                            <input type="text" name="username" required
                                   value="<?= htmlspecialchars($userData['username']) ?>"
                                   class="w-full px-4 py-3 border-2 border-teal-100 rounded-lg focus:border-teal-300 focus:ring-2 focus:ring-teal-100 focus:outline-none transition-all duration-200 pixel-text bg-white">
                        </div>

                        <!-- Button Group -->
                        <div class="pt-4 space-y-3">
                            <!-- Simpan Button -->
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-teal-500 to-cyan-500 text-white py-3 rounded-lg border-2 border-teal-600 hover:from-teal-600 hover:to-cyan-600 transition-all duration-200 font-medium pixel-text shadow-md hover:shadow-lg">
                                💾 Simpan Perubahan
                            </button>

                            <!-- Hapus Akun Button -->
                            <button type="button"
                                    onclick="confirmDelete()"
                                    class="w-full bg-gradient-to-r from-red-400 to-pink-500 text-white py-3 rounded-lg border-2 border-red-500 hover:from-red-500 hover:to-pink-600 transition-all duration-200 font-medium pixel-text shadow-md hover:shadow-lg">
                                🗑️ Hapus Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="deleteForm" action="/backend/user/delete.php" method="POST" class="hidden">
    </form>

    <script>
    function confirmDelete() {
        if (confirm('Yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan!')) {
            document.getElementById('deleteForm').submit();
        }
    }
    </script>
</body>
</html>