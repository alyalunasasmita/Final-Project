<?php
namespace App\submateri;

require_once __DIR__.'/../config/nyambung.php'; 
use App\Database\Database;

class Submateri {

    private $db;

    public function __construct() {

        $conn = new Database();
        $this->db = $conn->db;
    }

    public function tambahSubmateri($urutan,$nama, $isi, $id_materi) {
        $stmt = $this->db->prepare(
            "INSERT INTO submateri (urutan, nama_subMateri, isi_materi, materi_id_materi)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("issi", $urutan, $nama, $isi, $id_materi);
        return $stmt->execute();
    }

    public function lihatSubmateriByMateri($id_materi) {
        $stmt = $this->db->prepare(
            "SELECT * FROM submateri WHERE materi_id_materi = ? ORDER BY id_subMateri ASC"
        );
        $stmt->bind_param("i", $id_materi);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function lihatSubmateriById($id) {
    $stmt = $this->db->prepare(
        "SELECT * FROM submateri WHERE id_subMateri = ? LIMIT 1"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}


    public function updateSubmateri($id, $nama, $isi, $materi) {
        $stmt = $this->db->prepare(
            "UPDATE submateri 
            SET nama_subMateri=?, isi_materi=?, materi_id_materi=?
            WHERE id_subMateri=?"
        );
        $stmt->bind_param("ssii", $nama, $isi, $materi, $id);
        return $stmt->execute();
    }

    public function deleteSubmateri($id) {
        $stmt = $this->db->prepare("DELETE FROM submateri WHERE id_subMateri=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // navigasi bagian sumbateri_detail

    public function getNextSubmateri($current_id, $materi_id) {
        $stmt = $this->db->prepare("
            SELECT * FROM submateri 
            WHERE materi_id_materi = ? 
            AND (urutan > (SELECT urutan FROM submateri WHERE id_subMateri = ?) 
                 OR (urutan = (SELECT urutan FROM submateri WHERE id_subMateri = ?) 
                     AND id_subMateri > ?))
            ORDER BY urutan ASC, id_subMateri ASC 
            LIMIT 1
        ");
        
        $stmt->bind_param("iiii", $materi_id, $current_id, $current_id, $current_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getPreviousSubmateri($current_id, $materi_id) {
        $stmt = $this->db->prepare("
            SELECT * FROM submateri 
            WHERE materi_id_materi = ? 
            AND (urutan < (SELECT urutan FROM submateri WHERE id_subMateri = ?) 
                 OR (urutan = (SELECT urutan FROM submateri WHERE id_subMateri = ?) 
                     AND id_subMateri < ?))
            ORDER BY urutan DESC, id_subMateri DESC 
            LIMIT 1
        ");
        
        $stmt->bind_param("iiii", $materi_id, $current_id, $current_id, $current_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getFirstSubmateri($materi_id) {
        $stmt = $this->db->prepare("
            SELECT * FROM submateri 
            WHERE materi_id_materi = ? 
            ORDER BY urutan ASC, id_subMateri ASC 
            LIMIT 1
        ");
        
        $stmt->bind_param("i", $materi_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getSubmateriWithNavigation($current_id, $materi_id) {
        $result = [
            'current' => null,
            'previous' => null,
            'next' => null,
            'total' => 0,
            'current_position' => 0
        ];

        $result['current'] = $this->lihatSubmateriById($current_id);
        $result['previous'] = $this->getPreviousSubmateri($current_id, $materi_id);
        $result['next'] = $this->getNextSubmateri($current_id, $materi_id);
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total,
                (SELECT COUNT(*) FROM submateri 
                 WHERE materi_id_materi = ? 
                 AND (urutan < (SELECT urutan FROM submateri WHERE id_subMateri = ?)
                      OR (urutan = (SELECT urutan FROM submateri WHERE id_subMateri = ?)
                          AND id_subMateri < ?))) + 1 as position
            FROM submateri 
            WHERE materi_id_materi = ?
        ");
        
        $stmt->bind_param("iiiii", $materi_id, $current_id, $current_id, $current_id, $materi_id);
        $stmt->execute();
        $count_result = $stmt->get_result()->fetch_assoc();
        
        $result['total'] = $count_result['total'] ?? 0;
        $result['current_position'] = $count_result['position'] ?? 0;

        return $result;
    }
}
?>
