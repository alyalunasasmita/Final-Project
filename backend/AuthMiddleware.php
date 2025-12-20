<?php
namespace App;
// backend/AuthMiddleware.php
require_once __DIR__ . "/SessionManager.php";
require_once __DIR__ . "/activity.php";

use App\activity\Activity; 
use App\SessionManager;

class AuthMiddleware {
    private static ?SessionManager $session = null;
    
    private static function getSession(): SessionManager {
        if (self::$session === null) {
            self::$session = new SessionManager();
        }
        return self::$session;
    }
    
    public static function authUser(): array {
        $session = self::getSession();
        
        if (!$session->isUser()) {
            self::redirectToLogin();
        }
        
        // PERBAIKAN: Gunakan method yang benar dari SessionManager
        return [
            'id' => $session->getUserId(),    // getUserId() bukan get('user_id')
            'nama' => $session->getUsername(), // getUsername() bukan get('nama')
            'email' => null,                  // Jika ada field email di session, tambahkan
            'role' => $session->getRole()     // getRole() bukan get('role')
        ];
    }
    
    private static function redirectToLogin(): void {
        // Clear any output buffers
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        // Gunakan path absolut - sesuaikan dengan struktur project
        $loginPath = '' . BASE_URL . 'pages/auth/login.php';
        
        if (!headers_sent()) {
            header("Location: " . $loginPath);
        } else {
            echo '<script>window.location.href = "' . htmlspecialchars($loginPath) . '";</script>';
        }
        exit;
    }

    public static function authAdmin(): void {
        $session = self::getSession();
        
        if (!$session->isAdmin()) {
            self::redirectToLogin();
        }
    }
    
    public static function getSessionInstance(): SessionManager {
        return self::getSession();
    }

    public static function logout(): void {
        $session = self::getSession();
        $session->logout(); // SessionManager sudah handle semua
    }
    
    public static function requireNoAuth(): void {
        $session = self::getSession();
        
        // Cek apakah sudah login menggunakan method yang benar
        if ($session->isValid()) {
            if ($session->isAdmin()) {
                header("Location: " . BASE_URL . "pages/admin/dashboardAdmin.php");
            } else {
                header("Location: " . BASE_URL . "pages/user/dashboardUser.php");
            }
            exit;
        }
    }

    public static function logPageView($page_name) {
        $session = self::getSession();
        
        if ($session->getUserId()) {
            $activity = new Activity();
            $activity->autoLog("View Page: " . $page_name, $session->getUserId());
        }
    }
    
    public static function logCRUD($action, $entity, $entity_id = null) {
        $session = self::getSession();
        
        if ($session->getUserId()) {
            $description = ucfirst($action) . " " . $entity;
            if ($entity_id) {
                $description .= " (ID: " . $entity_id . ")";
            }
            
            $activity = new Activity();
            $activity->autoLog($description, $session->getUserId());
        }
    }
    
    // Helper method untuk mendapatkan data user yang aman
    public static function getUser(): ?array {
        try {
            return self::authUser();
        } catch (\Exception $e) {
            return null;
        }
    }
    
    // Method tambahan untuk mendapatkan data user tanpa auth check
    public static function getUserData(): ?array {
        $session = self::getSession();
        
        if (!$session->isValid()) {
            return null;
        }
        
        return [
            'id' => $session->getUserId(),
            'nama' => $session->getUsername(),
            'role' => $session->getRole(),
            'last_activity' => $session->getLastActivity()
        ];
    }

    public static function authUserJson(): array {
        $session = self::getSession();

        if (!$session->isUser()) {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        return [
            'id' => $session->getUserId(),
            'nama' => $session->getUsername(),
            'role' => $session->getRole()
        ];
    }
}