<?php
namespace App\Submateri;

require_once __DIR__ . '/../config/nyambung.php';
use App\Database\Database;

class Submateri
{
    private $db;

    public function __construct()
    {
        $conn = new Database();
        $this->db = $conn->db;
    }

    public function tambahSubmateri(int $urutan, string $nama, string $isi, int $id_materi): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO submateri (urutan, nama_subMateri, isi_materi, materi_id_materi)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("issi", $urutan, $nama, $isi, $id_materi);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Lihat submateri by materi (aktif saja + materi aktif)
     */
    public function lihatSubmateriByMateri(int $id_materi): array
    {
        $stmt = $this->db->prepare(
            "SELECT s.*
             FROM submateri s
             JOIN materi m ON m.id_materi = s.materi_id_materi
             WHERE s.materi_id_materi = ?
               AND s.deleted_at IS NULL
               AND m.deleted_at IS NULL
             ORDER BY s.urutan ASC, s.id_subMateri ASC"
        );
        $stmt->bind_param("i", $id_materi);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    /**
     * Lihat submateri by id (aktif saja + materi aktif)
     */
    public function lihatSubmateriById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT s.*
             FROM submateri s
             JOIN materi m ON m.id_materi = s.materi_id_materi
             WHERE s.id_subMateri = ?
               AND s.deleted_at IS NULL
               AND m.deleted_at IS NULL
             LIMIT 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function updateSubmateri(int $id, string $nama, string $isi, int $materi): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE submateri
             SET nama_subMateri = ?, isi_materi = ?, materi_id_materi = ?
             WHERE id_subMateri = ?
               AND deleted_at IS NULL"
        );
        $stmt->bind_param("ssii", $nama, $isi, $materi, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function deleteSubmateri(int $id): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE submateri
             SET deleted_at = NOW()
             WHERE id_subMateri = ?
               AND deleted_at IS NULL"
        );
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }


    private function getUrutanById(int $id): ?int
    {
        $stmt = $this->db->prepare(
            "SELECT urutan
             FROM submateri
             WHERE id_subMateri = ?
               AND deleted_at IS NULL
             LIMIT 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ? (int)$row['urutan'] : null;
    }

    public function getNextSubmateri(int $current_id, int $materi_id): ?array
    {
        $currentUrutan = $this->getUrutanById($current_id);
        if ($currentUrutan === null) return null;

        $stmt = $this->db->prepare("
            SELECT s.*
            FROM submateri s
            JOIN materi m ON m.id_materi = s.materi_id_materi
            WHERE s.materi_id_materi = ?
              AND s.deleted_at IS NULL
              AND m.deleted_at IS NULL
              AND (
                    s.urutan > ?
                 OR (s.urutan = ? AND s.id_subMateri > ?)
              )
            ORDER BY s.urutan ASC, s.id_subMateri ASC
            LIMIT 1
        ");

        $stmt->bind_param("iiii", $materi_id, $currentUrutan, $currentUrutan, $current_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function getPreviousSubmateri(int $current_id, int $materi_id): ?array
    {
        $currentUrutan = $this->getUrutanById($current_id);
        if ($currentUrutan === null) return null;

        $stmt = $this->db->prepare("
            SELECT s.*
            FROM submateri s
            JOIN materi m ON m.id_materi = s.materi_id_materi
            WHERE s.materi_id_materi = ?
              AND s.deleted_at IS NULL
              AND m.deleted_at IS NULL
              AND (
                    s.urutan < ?
                 OR (s.urutan = ? AND s.id_subMateri < ?)
              )
            ORDER BY s.urutan DESC, s.id_subMateri DESC
            LIMIT 1
        ");

        $stmt->bind_param("iiii", $materi_id, $currentUrutan, $currentUrutan, $current_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function getFirstSubmateri(int $materi_id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT s.*
            FROM submateri s
            JOIN materi m ON m.id_materi = s.materi_id_materi
            WHERE s.materi_id_materi = ?
              AND s.deleted_at IS NULL
              AND m.deleted_at IS NULL
            ORDER BY s.urutan ASC, s.id_subMateri ASC
            LIMIT 1
        ");

        $stmt->bind_param("i", $materi_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function getSubmateriWithNavigation(int $current_id, int $materi_id): array
    {
        $result = [
            'current' => null,
            'previous' => null,
            'next' => null,
            'total' => 0,
            'current_position' => 0
        ];

        $result['current'] = $this->lihatSubmateriById($current_id);
        if (!$result['current']) return $result;

        $result['previous'] = $this->getPreviousSubmateri($current_id, $materi_id);
        $result['next'] = $this->getNextSubmateri($current_id, $materi_id);

        $currentUrutan = (int)$result['current']['urutan'];

        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(
                    CASE
                        WHEN (urutan < ?)
                          OR (urutan = ? AND id_subMateri < ?)
                        THEN 1 ELSE 0
                    END
                ) + 1 AS position
            FROM submateri s
            JOIN materi m ON m.id_materi = s.materi_id_materi
            WHERE s.materi_id_materi = ?
              AND s.deleted_at IS NULL
              AND m.deleted_at IS NULL
        ");

        $stmt->bind_param("iiii", $currentUrutan, $currentUrutan, $current_id, $materi_id);
        $stmt->execute();
        $count = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $result['total'] = (int)($count['total'] ?? 0);
        $result['current_position'] = (int)($count['position'] ?? 0);

        return $result;
    }
}
