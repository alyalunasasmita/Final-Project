<?php
//informasi dan RUD acc user 
namespace App;

use App\Database\Database;

class User {
    private $db;
    private $table = "users";

    public function __construct() {
        $database = new Database();
        $this->db = $database->db;
    }

    ///Lihat Informasi Akun
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT id, nama, email, username, create_time
             FROM {$this->table}
             WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public function updateById(array $data): bool {

    //validasi format email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    $check = $this->db->prepare(
        "SELECT id FROM {$this->table}
         WHERE (email = ? OR username = ?)
         AND id != ?
         LIMIT 1"
    );

    $check->bind_param(
        "ssi",
        $data['email'],
        $data['username'],
        $data['id']
    );

    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        return false;
    }

    $stmt = $this->db->prepare(
        "UPDATE {$this->table}
         SET nama = ?, email = ?, username = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "sssi",
        $data['nama'],
        $data['email'],
        $data['username'],
        $data['id']
    );

    return $stmt->execute();
}



    ///hapus akun
    public function deleteById(int $id): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    //update password
    public function updatePassword(int $userId, string $oldPass, string $newPass): bool {

    // ambil password lama
    $stmt = $this->db->prepare(
        "SELECT password FROM users WHERE id = ? LIMIT 1"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();

    if (!$user) return false;

    // cek password lama
    if (!password_verify($oldPass, $user['password'])) {
        return false; // password lama salah
    }

    // hash password baru
    $hash = password_hash($newPass, PASSWORD_BCRYPT);

    // update password
    $stmt = $this->db->prepare(
        "UPDATE users SET password = ? WHERE id = ?"
    );
    $stmt->bind_param("si", $hash, $userId);
    $ok = $stmt->execute();
    $stmt->close();

    return $ok;
}

}
