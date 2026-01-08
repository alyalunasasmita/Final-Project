<?php
//CRUD materi pembelajaran oleh admin
namespace App\Materi;

require_once __DIR__ . '/../config/nyambung.php';

use App\Database\Database;

class Materi
{
    private $db;

    public function __construct()
    {
        $conn = new Database();
        $this->db = $conn->db;
    }

    /**
     * CREATE - Tambah materi
     */
    public function tambahMateri(string $nama, ?string $deskripsi = null): array
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO materi (nama_materi, deskripsi_materi)
                VALUES (?, ?)
            ");
            if (!$stmt) {
                throw new \Exception($this->db->error);
            }

            $stmt->bind_param("ss", $nama, $deskripsi);
            $stmt->execute();

            return [
                'success' => true,
                'id' => $this->db->insert_id,
                'message' => 'Materi berhasil ditambahkan'
            ];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * READ - Materi aktif (untuk user)
     */
    public function lihatMateri(): array
    {
        $sql = "
            SELECT id_materi, nama_materi, deskripsi_materi
            FROM materi
            WHERE deleted_at IS NULL
            ORDER BY id_materi DESC
        ";

        $res = $this->db->query($sql);
        $data = [];

        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }

        return ['success' => true, 'data' => $data];
    }

    /**
     * READ - Materi by ID (aktif saja)
     */
    public function getMateriById(int $id): array
    {
        $stmt = $this->db->prepare("
            SELECT id_materi, nama_materi, deskripsi_materi
            FROM materi
            WHERE id_materi = ?
              AND deleted_at IS NULL
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            return ['success' => false, 'message' => 'Materi tidak ditemukan'];
        }

        return ['success' => true, 'data' => $res->fetch_assoc()];
    }

    /**
     * UPDATE - Update materi (aktif saja)
     */
    public function updateMateri(int $id, string $nama, ?string $deskripsi = null): array
    {
        $stmt = $this->db->prepare("
            UPDATE materi
            SET nama_materi = ?, deskripsi_materi = ?
            WHERE id_materi = ?
              AND deleted_at IS NULL
        ");
        $stmt->bind_param("ssi", $nama, $deskripsi, $id);
        $stmt->execute();

        return [
            'success' => true,
            'affected_rows' => $stmt->affected_rows
        ];
    }

    /**
     * DELETE - SOFT DELETE (materi)
     * Submateri otomatis ikut via TRIGGER
     */
    public function archiveMateri(int $id): array
    {
        $stmt = $this->db->prepare("
            UPDATE materi
            SET deleted_at = NOW()
            WHERE id_materi = ?
              AND deleted_at IS NULL
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return [
            'success' => true,
            'affected_rows' => $stmt->affected_rows,
            'message' => $stmt->affected_rows > 0
                ? 'Materi & submateri berhasil diarsipkan'
                : 'Materi tidak ditemukan / sudah diarsipkan'
        ];
    }

    /**
     * RESTORE - Balikin materi + submateri
     */
    public function restoreMateri(int $id): array
    {
        $stmt = $this->db->prepare("
            UPDATE materi
            SET deleted_at = NULL
            WHERE id_materi = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return [
            'success' => true,
            'message' => 'Materi berhasil direstore'
        ];
    }

    public function lihatMateriArsip(): array
    {
        $sql = "
            SELECT id_materi, nama_materi, deskripsi_materi, deleted_at
            FROM materi
            WHERE deleted_at IS NOT NULL
            ORDER BY deleted_at DESC
        ";
        $res = $this->db->query($sql);
        $data = [];
        while ($row = $res->fetch_assoc()) $data[] = $row;

        return ['success' => true, 'data' => $data];
    }

    public function deleteHardMateri(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM materi
            WHERE id_materi = ?
            AND deleted_at IS NOT NULL
        ");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

}
