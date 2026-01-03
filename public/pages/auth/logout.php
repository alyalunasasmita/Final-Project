<?php
require_once __DIR__ .'/../../config.php';
require_once ROOT_PATH .'/backend/AuthMiddleware.php';

use App\AuthMiddleware;

/**
 * Jika request AJAX (dari JS) -> jalankan logout saja, tanpa render HTML
 */
if (isset($_GET['action']) && $_GET['action'] === 'do_logout') {
    AuthMiddleware::logout();       // PENTING: logout() jangan redirect/exit
    http_response_code(200);
    echo "OK";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563EB',
                        secondary: '#93C5FD',
                        accent: '#10B981',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Logout - StudyYou</title>
</head>
    <body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header Mobile -->
    <div class="lg:hidden bg-white shadow-sm border-b border-gray-200 p-4">
        <div class="flex items-center justify-center">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-accent flex items-center justify-center mr-3">
                <i class="fas fa-graduation-cap text-white"></i>
            </div>
            <h1 class="text-xl font-bold text-gray-900">StudyYou</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex items-center justify-center p-4">
        <div class="w-full max-w-sm text-center">
            <!-- Logo Desktop -->
            <div class="hidden lg:block mb-8">
                <div class="w-20 h-20 mx-auto rounded-xl bg-gradient-to-br from-primary to-accent flex items-center justify-center mb-4">
                    <i class="fas fa-sign-out-alt text-white text-3xl"></i>
                </div>
            </div>

            <!-- Logout Card -->
            <div class="bg-white rounded-xl shadow-md p-8 border border-gray-200">
                <!-- Spinner -->
                <div class="mb-6">
                    <div class="relative inline-block">
                        <div class="w-16 h-16 border-4 border-primary/20 rounded-full"></div>
                        <div class="absolute top-0 left-0 w-16 h-16 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <i class="fas fa-sign-out-alt text-primary text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-bold text-gray-900">Sedang Logout...</h2>
                    <p class="text-gray-600">
                        Anda sedang keluar dari akun StudyYou
                    </p>
                    <div class="pt-2">
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            Membersihkan sesi dan mengarahkan ulang...
                        </p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-8">
                    <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div id="progressBar" class="h-full bg-gradient-to-r from-primary to-accent w-0 transition-all duration-1200"></div>
                    </div>
                </div>
            </div>

            <!-- Footer Message -->
            <div class="mt-6">
                <p class="text-gray-500 text-sm">
                    <i class="fas fa-hand-wave mr-2"></i>
                    Sampai jumpa kembali!
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const progressBar = document.getElementById('progressBar');

            // animasi bar
            setTimeout(() => {
                progressBar.style.width = '100%';
            }, 10);

            // panggil endpoint logout di file yang sama (AJAX)
            try {
                await fetch('logout.php?action=do_logout', { method: 'POST' });
            } catch (e) {
                console.log('Gagal logout:', e);
            }

            // redirect setelah 1.2 detik
            setTimeout(() => {
                window.location.href = "login.php";
            }, 1200);
        });
    </script>
</body>
</html>
