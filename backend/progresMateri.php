<?php
namespace App\progres;

require_once __DIR__.'/../config/nyambung.php'; 
use App\Database\Database;

class ProgressMateri {

    private $db;
    private $user_id;

    public function __construct() {

        if (!isset($_SESSION['user_id'])) {
            die("User belum login.");
        }

        $this->user_id = $_SESSION['user_id'];

        $conn = new Database();
        $this->db = $conn->db;
    }

    /**
     * Tandai submateri sebagai selesai (jika belum)
     */
    public function markSubmateriOpened(int $id_sub) {

        // cek apakah submateri sudah ada di progress table
        $cek = $this->db->prepare(
            "SELECT id_progresSub FROM progres_submateri
             WHERE users_id = ? AND submateri_id_subMateri = ?"
        );
        $cek->bind_param("ii", $this->user_id, $id_sub);
        $cek->execute();
        $res = $cek->get_result();
        $now = date("Y-m-d H:i:s");

        if ($res->num_rows === 0) {
            // insert baru
            $stmt = $this->db->prepare(
                "INSERT INTO progres_submateri 
                (users_id, submateri_id_subMateri, status, waktu_selesai)
                VALUES (?, ?, 'selesai', ?)"
            );
            $stmt->bind_param("iis", $this->user_id, $id_sub, $now);
            $stmt->execute();
        } else {
            // update existing progress
            $row = $res->fetch_assoc();
            $id = $row['id_progresSub'];

            $stmt = $this->db->prepare(
                "UPDATE progres_submateri 
                 SET status = 'selesai', waktu_selesai = ?
                 WHERE id_progresSub = ?"
            );
            $stmt->bind_param("si", $now, $id);
            $stmt->execute();
        }
    }

    /**
     * Hitung progress satu materi
     */
    public function getProgressMateri(int $id_materi): array {

        // total submateri dalam materi
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total 
             FROM submateri 
             WHERE materi_id_materi=?"
        );
        $stmt->bind_param("i", $id_materi);
        $stmt->execute();
        $total = $stmt->get_result()->fetch_assoc()['total'];

        if ($total == 0) {
            return ['total'=>0,'selesai'=>0,'persen'=>0];
        }

        // jumlah submateri yang sudah selesai oleh user
        $stmt2 = $this->db->prepare(
            "SELECT COUNT(*) AS selesai
             FROM progres_submateri p
             JOIN submateri s 
               ON p.submateri_id_subMateri = s.id_subMateri
             WHERE s.materi_id_materi = ?
               AND p.users_id = ?
               AND p.status = 'selesai'"
        );
        $stmt2->bind_param("ii", $id_materi, $this->user_id);
        $stmt2->execute();
        $done = $stmt2->get_result()->fetch_assoc()['selesai'];

        $persen = floor(($done / $total) * 100);

        return [
            'total'   => $total,
            'selesai' => $done,
            'persen'  => $persen
        ];
    }
}
