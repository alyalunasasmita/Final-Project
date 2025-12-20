<?php
namespace App;

require_once ROOT_PATH . '/config/nyambung.php';

use App\Database\Database;
use mysqli;

class Tugas
{
    private mysqli $db;
    private int $userId;

    private array $allowedStatus = ['belum progres', 'dalam progres', 'selesai'];

    public function __construct(int $userId)
    {
        $this->userId = ($userId > 0) ? $userId : 0;

        $database = new Database();
        $this->db = $database->connect(); 
    }

    private function isUserValid(): bool
    {
        return $this->userId > 0;
    }

    private function validasiDasar(string $title, string $status, ?string $deadline): array
    {
        $errors = [];

        if (trim($title) === '') {
            $errors[] = "Title wajib diisi";
        } elseif (mb_strlen($title) > 180) {
            $errors[] = "Title maksimal 180 karakter";
        }

        if (!in_array($status, $this->allowedStatus, true)) {
            $errors[] = "Status tidak valid. Pilih: belum progres / dalam progres / selesai";
        }

        if ($deadline !== null && trim($deadline) !== '') {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $deadline)) {
                $errors[] = "Format deadline tidak valid (YYYY-MM-DD)";
            }
        }

        return $errors;
    }

    private function validasiId(int $idCatatan): array
    {
        $errors = [];
        if ($idCatatan <= 0) {
            $errors[] = "ID catatan tidak valid";
        }
        return $errors;
    }

    /**
     * CREATE
     */
    public function tambahTugas(
        string $title,
        ?string $description,
        string $status = 'belum progres',
        ?string $deadline = null
    ): array {
        if (!$this->isUserValid()) {
            return ["success" => false, "message" => "User ID tidak valid"];
        }

        $errors = $this->validasiDasar($title, $status, $deadline);
        if (!empty($errors)) {
            return ["success" => false, "message" => "Validasi gagal", "errors" => $errors];
        }

        $stmt = $this->db->prepare("
            INSERT INTO tasks (id_user, title, description, status, deadline)
            VALUES (?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            return ["success" => false, "message" => "Prepare gagal", "errors" => [$this->db->error]];
        }

        $stmt->bind_param("issss", $this->userId, $title, $description, $status, $deadline);

        if (!$stmt->execute()) {
            return ["success" => false, "message" => "Gagal tambah tugas", "errors" => [$stmt->error]];
        }

        $newId = (int)$this->db->insert_id;
        $data = $this->getTugasById($newId);

        return [
            "success" => true,
            "message" => "Tugas berhasil dibuat",
            "data" => $data["success"] ? $data["data"] : ["id_catatan" => $newId]
        ];
    }

    /**
     * READ (list semua tugas user), optional filter status
     */
    public function listTugas(?string $status = null): array
    {
        if (!$this->isUserValid()) {
            return ["success" => false, "message" => "User ID tidak valid"];
        }

        if ($status !== null && !in_array($status, $this->allowedStatus, true)) {
            return ["success" => false, "message" => "Status filter tidak valid"];
        }

        if ($status !== null) {
            $stmt = $this->db->prepare("
                SELECT *
                FROM tasks
                WHERE id_user = ? AND status = ?
                ORDER BY created_at DESC
            ");
            if (!$stmt) {
                return ["success" => false, "message" => "Prepare gagal", "errors" => [$this->db->error]];
            }
            $stmt->bind_param("is", $this->userId, $status);
        } else {
            $stmt = $this->db->prepare("
                SELECT *
                FROM tasks
                WHERE id_user = ?
                ORDER BY created_at DESC
            ");
            if (!$stmt) {
                return ["success" => false, "message" => "Prepare gagal", "errors" => [$this->db->error]];
            }
            $stmt->bind_param("i", $this->userId);
        }

        if (!$stmt->execute()) {
            return ["success" => false, "message" => "Gagal ambil data", "errors" => [$stmt->error]];
        }

        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return ["success" => true, "message" => "OK", "data" => $rows];
    }

    /**
     * READ (get by id)
     */
    public function getTugasById(int $idCatatan): array
    {
        if (!$this->isUserValid()) {
            return ["success" => false, "message" => "User ID tidak valid"];
        }

        $errors = $this->validasiId($idCatatan);
        if (!empty($errors)) {
            return ["success" => false, "message" => "Validasi gagal", "errors" => $errors];
        }

        $stmt = $this->db->prepare("
            SELECT *
            FROM tasks
            WHERE id_catatan = ? AND id_user = ?
            LIMIT 1
        ");

        if (!$stmt) {
            return ["success" => false, "message" => "Prepare gagal", "errors" => [$this->db->error]];
        }

        $stmt->bind_param("ii", $idCatatan, $this->userId);

        if (!$stmt->execute()) {
            return ["success" => false, "message" => "Gagal ambil tugas", "errors" => [$stmt->error]];
        }

        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return ["success" => false, "message" => "Tugas tidak ditemukan"];
        }

        return ["success" => true, "message" => "OK", "data" => $row];
    }

    /**
     * UPDATE
     */
    public function updateTugas(
        int $idCatatan,
        string $title,
        ?string $description,
        string $status,
        ?string $deadline
    ): array {
        if (!$this->isUserValid()) {
            return ["success" => false, "message" => "User ID tidak valid"];
        }

        $errors = array_merge(
            $this->validasiId($idCatatan),
            $this->validasiDasar($title, $status, $deadline)
        );

        if (!empty($errors)) {
            return ["success" => false, "message" => "Validasi gagal", "errors" => $errors];
        }

        $stmt = $this->db->prepare("
            UPDATE tasks
            SET title = ?,
                description = ?,
                status = ?,
                deadline = ?
            WHERE id_catatan = ? AND id_user = ?
        ");

        if (!$stmt) {
            return ["success" => false, "message" => "Prepare gagal", "errors" => [$this->db->error]];
        }

        $stmt->bind_param("ssssii", $title, $description, $status, $deadline, $idCatatan, $this->userId);

        if (!$stmt->execute()) {
            return ["success" => false, "message" => "Gagal update tugas", "errors" => [$stmt->error]];
        }

        if ($stmt->affected_rows === 0) {
            return ["success" => false, "message" => "Tugas tidak ditemukan / tidak ada perubahan"];
        }

        return ["success" => true, "message" => "Tugas berhasil diupdate"];
    }

    /**
     * DELETE
     */
    public function deleteTugas(int $idCatatan): array
    {
        if (!$this->isUserValid()) {
            return ["success" => false, "message" => "User ID tidak valid"];
        }

        $errors = $this->validasiId($idCatatan);
        if (!empty($errors)) {
            return ["success" => false, "message" => "Validasi gagal", "errors" => $errors];
        }

        $stmt = $this->db->prepare("
            DELETE FROM tasks
            WHERE id_catatan = ? AND id_user = ?
        ");

        if (!$stmt) {
            return ["success" => false, "message" => "Prepare gagal", "errors" => [$this->db->error]];
        }

        $stmt->bind_param("ii", $idCatatan, $this->userId);

        if (!$stmt->execute()) {
            return ["success" => false, "message" => "Gagal hapus tugas", "errors" => [$stmt->error]];
        }

        if ($stmt->affected_rows === 0) {
            return ["success" => false, "message" => "Tugas tidak ditemukan"];
        }

        return ["success" => true, "message" => "Tugas berhasil dihapus"];
    }
}
