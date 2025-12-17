<?php
namespace App\auth;

require_once __DIR__.'/../config/nyambung.php'; 
require_once __DIR__ . '/SessionManager.php';

use App\Database\Database;
use App\SessionManager;

class Autentikasi {

    private $db;
    private $session;

    public function __construct() {
        $this->session = new SessionManager();
        $conn = new Database();
        $this->db = $conn->db;
    }

    private function cek_username($username) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    private function cek_email($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function register($nama, $username, $email, $password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "email tidak valid";
        if ($this->cek_email($email)) return "email sudah digunakan";
        if ($this->cek_username($username)) return "username sudah digunakan";

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "INSERT INTO users (username, nama, email, password, role) VALUES (?, ?, ?, ?, 'user')"
        );
        $stmt->bind_param("ssss", $username, $nama, $email, $hashed);

        if ($stmt->execute()) {
            return true; 
        } else {
            return "gagal register: " . $stmt->error;
        }
    }

    

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) return "username tidak ditemukan";

        $user = $result->fetch_assoc();

        if (!password_verify($password, $user["password"])) return "password salah!";

        $this->session->login($user["username"], $user["role"], $user["id"]);

        return $user["role"];
    }

    public function registerAndRedirect ($nama, $username, $email, $password){
        $result = $this-> register($nama, $username, $email, $password); 

        if ($result === "registrasi berhasil"){
            header ("location: login.php"); 
        }else {
           header("Location: register.php?error=" . urlencode($result)); 
        }
    }

    public function loginAndRedirect($username, $password) {
        $result = $this->login($username, $password);
        
        if ($result === "username tidak ditemukan" || $result === "password salah!") {
            header("Location: login.php?error=" . urlencode($result));
            exit;
        }

        // SESUAIKAN PATH dengan struktur
        if ($result === "admin") {
            header("Location: admin/dashboardAdmin.php"); // ke frontend/admin/dashboard.php
            exit;
        }

        if ($result === "user") {
            header("Location: user/dashboardUser.php"); // ke frontend/user/dashboardUser.php
            exit;
        }

        header("Location: login.php?error=Role tidak dikenali");
        exit;
    }
    
    public function verifikasiUser($username, $email) {
        $stmt = $this->db->prepare(
            "SELECT id FROM users WHERE username = ? AND email = ?"
        );
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows === 1;
    }

    public function updatePassword($username, $passwordBaru) {
        $hashed = password_hash($passwordBaru, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "UPDATE users SET password = ? WHERE username = ?"
        );
        $stmt->bind_param("ss", $hashed, $username);
        return $stmt->execute();
    }

    public function forgotPassword($username, $email, $passwordBaru) {
        if (!$this->verifikasiUser($username, $email)) return "Username dan email tidak cocok!";
        return $this->updatePassword($username, $passwordBaru) ? "Password berhasil direset!" : "Gagal reset password!";
    }

    public function logout() {
        $this->session->logout();
    }
}
