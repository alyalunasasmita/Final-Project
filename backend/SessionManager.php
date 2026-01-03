<?php
namespace App;

class SessionManager {

    private int $timeout  = 1800; // 30 menit
    private int $lifetime = 7200; // 2 jam

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set("session.gc_maxlifetime", (string)$this->lifetime);

            session_set_cookie_params([
                "lifetime" => $this->lifetime,
                "path"     => "/",
                "secure"   => false,
                "httponly" => true,
                "samesite" => "Lax"
            ]);

            session_start();
        }

        // ⚠️ HANYA cek timeout jika sudah login
        if (isset($_SESSION["username"])) {
            $this->checkIdleTimeout();
        }
    }

    public function login(string $username, string $role, int $user_id): void {
        $_SESSION["username"]      = $username;
        $_SESSION["role"]          = $role;
        $_SESSION["user_id"]       = $user_id;
        $_SESSION["last_activity"] = time();
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION = array();
        
        if (session_id()) {
            session_destroy();
        }
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }
        
        
    }

    public function isUser(): bool {
        return isset($_SESSION["role"]) && $_SESSION["role"] === "user";
    }

    public function isAdmin(): bool {
        return isset($_SESSION["role"]) && $_SESSION["role"] === "admin";
    }

    public function getUsername(): ?string {
        return $_SESSION["username"] ?? null;
    }

    public function getRole(): ?string {
        return $_SESSION["role"] ?? null;
    }

    public function getUserId(): ?int {
        return $_SESSION["user_id"] ?? null;
    }

    public function getLastActivity(): ?int {
        return $_SESSION["last_activity"] ?? null;
    }

    private function checkIdleTimeout(): void {
        if (isset($_SESSION["last_activity"])) {
            if (time() - $_SESSION["last_activity"] > $this->timeout) {
                $this->logout();
            }
        }
        $_SESSION["last_activity"] = time();
    }

    public function regenerateSession(): void {
        session_regenerate_id(true);
        $_SESSION["last_activity"] = time();
    }

    public function isValid(): bool {
        return isset($_SESSION["username"]) && isset($_SESSION["role"]);
    }
}