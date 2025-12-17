<?php
session_start();
use App\AuthMiddleware;
use App\User;

require_once __DIR__."/../../../../backend/AuthMiddleware.php";
require_once __DIR__. '/../../../../backend/userAcc.php';

$userAuth = AuthMiddleware::authUser();
$userModel = new User();
$userData = $userModel->getById($userAuth['id']);

require_once __DIR__ . '/../../../assets/layout/header.php';
?>
    <style>
        .pixel-grid-bg {
            background-image: 
                linear-gradient(rgba(163, 189, 237, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(163, 189, 237, 0.05) 1px, transparent 1px);
            background-size: 10px 10px;
        }
        
        .pixel-button {
            position: relative;
            border-bottom-width: 4px !important;
            border-radius: 0;
        }
        
        .pixel-button:hover {
            transform: translateY(-1px);
            border-bottom-width: 6px !important;
        }
        
        .pixel-button:active {
            transform: translateY(1px);
            border-bottom-width: 2px !important;
        }
        
        .pixel-text {
            font-family: 'Courier New', monospace;
            letter-spacing: 0.01em;
        }
        
        .pixel-card {
            border-radius: 0;
            position: relative;
            overflow: hidden;
        }
        
        .pixel-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #A3BDED, #D5B8FF, #8AC6D1);
        }
        
        .pixel-avatar {
            border-style: solid;
            border-width: 3px;
            border-image: linear-gradient(45deg, #A3BDED, #D5B8FF) 1;
        }
        
        @keyframes pixel-glow {
            0%, 100% { box-shadow: 0 0 5px rgba(163, 189, 237, 0.3); }
            50% { box-shadow: 0 0 15px rgba(163, 189, 237, 0.5); }
        }
        
        .animate-pixel-glow {
            animation: pixel-glow 2s ease-in-out infinite;
        }
        
        .soft-gradient-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
    </style>
</head>
<body class="soft-gradient-bg min-h-screen pixel-grid-bg">
    
    <!-- Main Content Container -->
    <div class="max-w-6xl mx-auto px-4 py-8 md:py-12">
        
        <!-- Header Section -->
        <div class="mb-8 md:mb-12">
            <div class="flex items-center gap-4 mb-4">
                <a href="/frontend/user/dashboardUser.php" 
                   class="p-2 bg-gradient-to-r from-white to-blue-50 border-2 border-blue-100 shadow-[2px_2px_0_rgba(163,189,237,0.2)] hover:shadow-[3px_3px_0_rgba(163,189,237,0.3)] transition-all duration-200 pixel-button">
                    <span class="material-icons text-blue-400">arrow_back</span>
                </a>
                <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent pixel-text">
                    MY PROFILE
                </h1>
            </div>
            <div class="flex gap-2">
                <div class="w-3 h-1 bg-blue-300"></div>
                <div class="w-3 h-1 bg-purple-300"></div>
                <div class="w-3 h-1 bg-teal-300"></div>
                <div class="w-3 h-1 bg-pink-300"></div>
            </div>
        </div>

        <!-- Profile Container -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column - Profile Card -->
            <div class="lg:col-span-1">
                <!-- Profile Card -->
                <div class="bg-white pixel-card border-3 border-blue-100 shadow-[4px_4px_0_rgba(163,189,237,0.2)] hover:shadow-[6px_6px_0_rgba(163,189,237,0.3)] transition-all duration-300">
                    
                    <!-- Profile Header -->
                    <div class="p-6 md:p-8 text-center border-b-2 border-blue-50">
                        <!-- Avatar Container -->
                        <div class="relative inline-block mb-4">
                            <!-- Pixel Avatar Frame -->
                        
                            
                            <!-- Edit Avatar Button -->
                            
                        </div>
                        
                        <!-- User Name -->
                        <h2 class="text-2xl font-bold text-gray-800 mb-1 pixel-text"><?= htmlspecialchars($userData['nama'] ?? 'User Name') ?></h2>
                        
                        <!-- Username -->
                        <p class="text-gray-600 mb-4 pixel-text">@<?= htmlspecialchars($userData['username'] ?? 'username') ?></p>
                        
                        <!-- Role Badge -->
                        <div class="inline-block px-4 py-1 bg-gradient-to-r from-blue-50 to-purple-50 border-2 border-blue-200">
                            <span class="text-sm font-semibold text-blue-600 pixel-text">STUDENT</span>
                        </div>
                    </div>
                    
                    <!-- Profile Stats -->
                    <div class="p-6 md:p-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 pixel-text border-b-2 border-blue-50 pb-2">PROFILE STATS</h3>
                        
                        <div class="space-y-4">
                            <!-- Joined Date -->
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-white border-2 border-blue-100 hover:border-blue-300 transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-50 border-2 border-blue-300 flex items-center justify-center">
                                        <span class="material-icons text-blue-500 text-sm">calendar_today</span>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 pixel-text">Joined</p>
                                        <p class="font-medium text-gray-800 pixel-text">
                                            <?= date('d M Y', strtotime($userData['create_time'] ?? 'now')) ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="material-icons text-blue-300">chevron_right</span>
                            </div>
                            
                            <!-- Account Status -->
                            
                            
                            <!-- Member ID -->
                            
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="mt-6 bg-white pixel-card border-3 border-blue-100 shadow-[4px_4px_0_rgba(163,189,237,0.2)]">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 pixel-text border-b-2 border-blue-50 pb-2">QUICK ACTIONS</h3>
                        
                        <div class="space-y-3">
                            <a href="/frontend/user/akunuser/editakun.php" 
                               class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-white border-2 border-blue-100 hover:border-blue-400 hover:shadow-[2px_2px_0_rgba(66,153,225,0.2)] transition-all duration-200 group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-300 to-blue-200 border-2 border-blue-400 flex items-center justify-center group-hover:border-blue-500 transition-colors">
                                        <span class="material-icons text-white text-sm">edit</span>
                                    </div>
                                    <span class="font-medium text-gray-700 pixel-text">Edit Profile</span>
                                </div>
                                <span class="material-icons text-gray-400 group-hover:text-blue-400 transition-colors">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Profile Details -->
            <div class="lg:col-span-2">
                <!-- Personal Information Card -->
                <div class="bg-white pixel-card border-3 border-blue-100 shadow-[4px_4px_0_rgba(163,189,237,0.2)] mb-6">
                    <div class="p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-800 pixel-text">PERSONAL INFORMATION</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="p-4 bg-gradient-to-r from-blue-50 to-white border-2 border-blue-100 hover:border-blue-300 transition-all duration-200">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-blue-50 border-2 border-blue-300 flex items-center justify-center">
                                        <span class="material-icons text-blue-500 text-xs">person</span>
                                    </div>
                                    <label class="text-sm text-gray-500 pixel-text">Full Name</label>
                                </div>
                                <p class="font-medium text-gray-800 text-lg pixel-text pl-11  break-words"><?= htmlspecialchars($userData['nama'] ?? 'N/A') ?></p>
                            </div>
                            
                            <!-- Username -->
                            <div class="p-4 bg-gradient-to-r from-purple-50 to-white border-2 border-purple-100 hover:border-purple-300 transition-all duration-200">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-50 border-2 border-purple-300 flex items-center justify-center">
                                        <span class="material-icons text-purple-500 text-xs">alternate_email</span>
                                    </div>
                                    <label class="text-sm text-gray-500 pixel-text">Username</label>
                                </div>
                                <p class="font-medium text-gray-800 text-lg pixel-text pl-11  break-words">@<?= htmlspecialchars($userData['username'] ?? 'N/A') ?></p>
                            </div>
                            
                            <!-- Email -->
                            <div class="p-4 bg-gradient-to-r from-teal-50 to-white border-2 border-teal-100 hover:border-teal-300 transition-all duration-200">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-teal-100 to-teal-50 border-2 border-teal-300 flex items-center justify-center">
                                    <span class="material-icons text-teal-500 text-xs">email</span>
                                </div>
                                <label class="text-sm text-gray-500 pixel-text">Email Address</label>
                            </div>
                            <!-- Tambahkan truncate di sini -->
                            <p class="font-medium text-gray-800 text-lg pixel-text pl-11  break-words">
                                <?= htmlspecialchars($userData['email'] ?? 'N/A') ?>
                            </p>
                        </div>
                            
                            <!-- Account Type -->
                            <div class="p-4 bg-gradient-to-r from-green-50 to-white border-2 border-green-100 hover:border-green-300 transition-all duration-200">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-green-100 to-green-50 border-2 border-green-300 flex items-center justify-center">
                                        <span class="material-icons text-green-500 text-xs">school</span>
                                    </div>
                                    <label class="text-sm text-gray-500 pixel-text">Account Type</label>
                                </div>
                                <div class="flex items-center gap-2 pl-11">
                                    <p class="font-medium text-gray-800 text-lg pixel-text">Student</p>
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                </div>
                            </div>
                            
                            <!-- Member Since -->
                            <div class="md:col-span-2 p-4 bg-gradient-to-r from-indigo-50 to-white border-2 border-indigo-100 hover:border-indigo-300 transition-all duration-200">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-100 to-indigo-50 border-2 border-indigo-300 flex items-center justify-center">
                                        <span class="material-icons text-indigo-500 text-xs">calendar_month</span>
                                    </div>
                                    <label class="text-sm text-gray-500 pixel-text">Member Since</label>
                                </div>
                                <div class="pl-11">
                                    <p class="font-medium text-gray-800 text-lg pixel-text">
                                        <?= date('d F Y', strtotime($userData['create_time'] ?? 'now')) ?>
                                    </p>
                                    <p class="text-sm text-gray-500 pixel-text mt-1">
                                        <?= date('H:i', strtotime($userData['create_time'] ?? 'now')) ?> WIB
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Account Security Card -->
                <div class="bg-white pixel-card border-3 border-blue-100 shadow-[4px_4px_0_rgba(163,189,237,0.2)] mb-6">
                    <div class="p-6 md:p-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 pixel-text">ACCOUNT SECURITY</h2>
                        
                        <div class="space-y-4">
                            <!-- Password Security -->
                            <div class="p-4 bg-gradient-to-r from-blue-50 to-white border-2 border-blue-100 hover:border-blue-400 transition-all duration-200 group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-300 to-blue-200 border-2 border-blue-400 flex items-center justify-center">
                                            <span class="material-icons text-white">lock</span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-800 pixel-text">Password</h3>
                                            <p class="text-sm text-gray-500 pixel-text">Last changed: Never</p>
                                        </div>
                                    </div>
                                    <a href="/frontend/user/akunuser/gantipass.php" 
                                       class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-400 border-2 border-blue-600 shadow-[2px_2px_0_rgba(37,99,235,0.3)] hover:shadow-[3px_3px_0_rgba(37,99,235,0.5)] text-white text-sm font-semibold pixel-text transition-all duration-200">
                                        CHANGE
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Account Actions Card -->
                <div class="bg-white pixel-card border-3 border-blue-100 shadow-[4px_4px_0_rgba(163,189,237,0.2)]">
                    <div class="p-6 md:p-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 pixel-text">ACCOUNT ACTIONS</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Delete Account -->
                            <button onclick="confirmDelete()" 
                                    class="p-4 bg-gradient-to-r from-red-50 to-white border-2 border-red-100 hover:border-red-400 hover:shadow-[2px_2px_0_rgba(239,68,68,0.2)] transition-all duration-200 group text-left">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-red-300 to-red-200 border-2 border-red-400 flex items-center justify-center">
                                        <span class="material-icons text-white text-sm">delete</span>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800 pixel-text">Delete Account</h3>
                                        <p class="text-xs text-gray-500 pixel-text">Permanently remove account</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 opacity-0 invisible transition-all duration-300">
        <div class="bg-white p-8 border-3 border-green-300 shadow-[4px_4px_0_rgba(74,222,128,0.3)] max-w-md mx-4">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-green-300 to-green-200 border-3 border-green-400">
                <span class="material-icons text-white text-2xl">check</span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 text-center mb-2 pixel-text">SUCCESS!</h3>
            <p class="text-gray-600 text-center mb-6 pixel-text">Profile updated successfully</p>
            <button onclick="closeModal()" 
                    class="w-full py-3 bg-gradient-to-r from-green-400 to-green-500 border-2 border-green-600 text-white font-semibold pixel-text hover:shadow-[2px_2px_0_rgba(34,197,94,0.3)] transition-all duration-200">
                OKAY
            </button>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if(confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
                // Handle account deletion
                window.location.href = '/frontend/user/akunuser/hapusakun.php';
            }
        }
        
        function closeModal() {
            const modal = document.getElementById('successModal');
            modal.classList.add('opacity-0', 'invisible');
        }
        
        // Show success message if URL has success parameter
        if(window.location.search.includes('success=true')) {
            setTimeout(() => {
                const modal = document.getElementById('successModal');
                modal.classList.remove('opacity-0', 'invisible');
            }, 500);
        }
        
        // Copy member ID to clipboard
        document.querySelectorAll('button').forEach(button => {
            if(button.innerHTML.includes('content_copy')) {
                button.addEventListener('click', function() {
                    const memberId = 'STU<?= str_pad($userData['id'] ?? '000', 6, '0', STR_PAD_LEFT) ?>';
                    navigator.clipboard.writeText(memberId);
                    
                    // Show temporary feedback
                    const original = this.innerHTML;
                    this.innerHTML = '<span class="material-icons text-sm text-green-500">check</span>';
                    setTimeout(() => {
                        this.innerHTML = original;
                    }, 2000);
                });
            }
        });
    </script>
</body>
</html>