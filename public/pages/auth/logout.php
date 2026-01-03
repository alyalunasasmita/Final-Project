<?php
require_once __DIR__ .'/../../config.php';
require_once ROOT_PATH .'/backend/AuthMiddleware.php';

use App\AuthMiddleware;

$logout_success = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_logout']) && $_POST['confirm_logout'] === 'true') {
        try {
            AuthMiddleware::logout();     
            $logout_success = true;
        } catch (Exception $e) {
            $error_message = "Gagal melakukan logout: " . $e->getMessage();
        }
    } else {
        $error_message = "Konfirmasi logout tidak valid";
    }
} elseif (isset($_GET['action']) && $_GET['action'] === 'do_logout') {
    AuthMiddleware::logout();     
    http_response_code(200);
    echo "OK";
    exit;
}
require_once PUBLIC_PATH . '/partials/header.php';
?>
    <title>Logout - StudyYou</title>
<body class="bg-gray-50 min-h-screen flex flex-col">
 
    <div class="lg:hidden bg-white shadow-sm border-b border-gray-200 p-4">
        <div class="flex items-center justify-center">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-600 to-emerald-500 flex items-center justify-center mr-3">
                <i class="material-icons text-white">school</i>
            </div>
            <h1 class="text-xl font-bold text-gray-900">StudyYou</h1>
        </div>
    </div>


    <div class="flex-1 flex items-center justify-center p-4">
        <div class="w-full max-w-md text-center">
            <div class="hidden lg:block mb-8">
                <div class="w-20 h-20 mx-auto rounded-xl bg-gradient-to-br from-blue-600 to-emerald-500 flex items-center justify-center mb-4">
                    <i class="material-icons-sharp text-white text-3xl">logout</i>
                </div>
            </div>

            <?php if (!empty($error_message)): ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center justify-center">
                    <i class="material-icons text-red-500 mr-2">error</i>
                    <span class="text-red-700 font-medium"><?php echo htmlspecialchars($error_message); ?></span>
                </div>
                <div class="mt-4">
                    <a href="dashboard.php" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                        <i class="material-icons mr-1">arrow_back</i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($logout_success): ?>
               
                <div class="bg-white rounded-xl shadow-md p-8 border border-gray-200">
                   
                    <div class="mb-6">
                        <div class="relative inline-block">
                            <div class="w-16 h-16 border-4 border-blue-600/20 rounded-full"></div>
                            <div class="absolute top-0 left-0 w-16 h-16 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <i class="material-icons-sharp text-blue-600 text-xl">logout</i>
                            </div>
                        </div>
                    </div>

                   
                    <div class="space-y-4">
                        <h2 class="text-2xl font-bold text-gray-900">Sedang Logout...</h2>
                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center justify-center">
                                <i class="material-icons text-green-500 mr-2">check_circle</i>
                                <span class="text-green-700 font-medium">Logout berhasil! Mengarahkan ke halaman login...</span>
                            </div>
                        </div>
                        <p class="text-gray-600">
                            Anda sedang keluar dari akun StudyYou
                        </p>
                        <div class="pt-2">
                            <p class="text-sm text-gray-500">
                                <i class="material-icons animate-spin text-sm mr-2">refresh</i>
                                Membersihkan sesi dan mengarahkan ulang...
                            </p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div id="progressBar" class="h-full bg-gradient-to-r from-blue-600 to-emerald-500 w-0 transition-all duration-1200"></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
            
                <div class="bg-white rounded-xl shadow-md p-8 border border-gray-200">
                    <div class="mb-6">
                        <div class="w-16 h-16 mx-auto rounded-full bg-red-50 flex items-center justify-center">
                            <i class="material-icons-sharp text-red-500 text-3xl">logout</i>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Konfirmasi Logout</h2>
                            <p class="text-gray-600">
                                Apakah Anda yakin ingin keluar dari akun StudyYou?
                            </p>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="material-icons text-yellow-500 mr-2 mt-0.5">warning</i>
                                <div class="text-left">
                                    <p class="text-yellow-800 text-sm">
                                        Setelah logout, Anda perlu login kembali untuk mengakses dashboard.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form id="logoutForm" method="POST" action="logout.php" class="space-y-4">
                            <input type="hidden" name="confirm_logout" value="true">
                            
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <button type="button" onclick="window.history.back()" 
                                        class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center">
                                    <i class="material-icons mr-2">arrow_back</i> Batalkan
                                </button>
                                
                                <button type="submit" 
                                        class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-lg hover:from-red-600 hover:to-red-700 transition-all flex items-center justify-center shadow-sm">
                                    <i class="material-icons mr-2">logout</i> Ya, Logout Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="mt-6">
                <p class="text-gray-500 text-sm">
                    <i class="material-icons mr-2 text-gray-400">wave</i>
                    Sampai jumpa kembali!
                </p>
            </div>
        </div>
    </div>

    <?php if ($logout_success): ?>
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const progressBar = document.getElementById('progressBar');


            setTimeout(() => {
                progressBar.style.width = '100%';
            }, 10);


            setTimeout(() => {
                window.location.href = "login.php";
            }, 1200);
        });
    </script>
    <?php endif; ?>
</body>
</html>