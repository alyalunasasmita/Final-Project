<?php
require_once __DIR__."/../../../../backend/AuthMiddleware.php";
require_once __DIR__. '/../../../../backend/userAcc.php';

use App\AuthMiddleware;
use App\User;

$userAuth = AuthMiddleware::authUser();
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['new_password'] !== $_POST['confirm_password']) {
        header("Location: gantipass.php?error=confirm");
        exit;
    }

    if (strlen($_POST['new_password']) < 6) {
        header("Location: gantipass.php?error=length");
        exit;
    }

    if ($user->updatePassword(
        $userAuth['id'],
        $_POST['old_password'],
        $_POST['new_password']
    )) {

        AuthMiddleware::logCRUD('update', 'password', $userAuth['id']);
        AuthMiddleware::logout(); // auto logout

        header("Location: login.php?msg=password_changed");
        exit;
    } else {
        header("Location: gantipass.php?error=wrong_old");
        exit;
    }
}
require_once __DIR__ . '/../../../assets/layout/header.php';
?>

<body class="min-h-screen bg-gradient-to-br from-teal-50 to-cyan-50">
    
    <!-- CONTAINER UTAMA -->
    <div class="container mx-auto px-4 py-8">
        
        <!-- ARROW BACK - DI ATAS FORM -->
        <div class="mb-6">
            <a href="lihatakun.php" class="inline-flex items-center text-teal-600 hover:text-teal-800 pixel-text">
                <span class="material-icons">arrow_back</span>
                <span class="ml-2">Kembali lihat informasi akun</span>
            </a>
        </div>

        <!-- FORM CARD - DI BAWAH ARROW -->
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <div class="p-6 bg-gradient-to-r from-teal-50 to-white border-2 border-teal-100 rounded-xl shadow-lg hover:border-teal-300 transition-all duration-300">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pixel-text text-center">Ganti Password</h2>

                    <!-- ALERT -->
                    <?php if (isset($_GET['error'])): ?>
                        <div class="mb-4 p-3 rounded pixel-text text-sm
                            <?php 
                                if ($_GET['error'] === 'confirm' || $_GET['error'] === 'length' || $_GET['error'] === 'wrong_old') {
                                    echo 'bg-red-100 text-red-700 border border-red-300';
                                }
                            ?>">
                            <?php 
                                if ($_GET['error'] === 'confirm') {
                                    echo "Konfirmasi password tidak sesuai.";
                                } elseif ($_GET['error'] === 'length') {
                                    echo "Password baru harus terdiri dari minimal 8 karakter.";
                                } elseif ($_GET['error'] === 'wrong_old') {
                                    echo "Password lama salah.";
                                }
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- FORM -->
                    <form method="POST" action="">
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2 pixel-text" for="old_password">Password Lama</label>
                            <input type="password" id="old_password" name="old_password" required
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-300 pixel-text">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2 pixel-text" for="new_password">Password Baru</label>
                            <input type="password" id="new_password" name="new_password" required
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-300 pixel-text">
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2 pixel-text" for="confirm_password">Konfirmasi Password Baru</label>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-300 pixel-text">
                        </div>
                        <button type="submit"
                            class="w-full bg-teal-600 text-white py-2 rounded hover:bg-teal-700 transition-all duration-300 pixel-text font-semibold">
                            Ganti Password
                        </button>
                    </form>
                </div>
                           



