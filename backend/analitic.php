<?php
namespace App\Repositories;
require_once __DIR__.'/../config/nyambung.php'; 
use App\database\Database;


final class LaporanBelajarRepository
{
    private \mysqli $db;

    public function __construct(Database $database)
    {
        $this->db = $database->db;
    }

    private function addDateFilter(string &$sql, string &$types, array &$params, ?string $start, ?string $end): void
    {
        if ($start && $end) {
            $sql .= " AND tanggal BETWEEN ? AND ? ";
            $types .= "ss";
            $params[] = $start;
            $params[] = $end;
        }
    }

    private function fetchAll(string $sql, string $types, array $params): array
    {
        $stmt = $this->db->prepare($sql);
        if (!$stmt) throw new \RuntimeException("Prepare failed: " . $this->db->error);

        if ($types !== '') $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    private function fetchOne(string $sql, string $types, array $params): array
    {
        $rows = $this->fetchAll($sql, $types, $params);
        return $rows[0] ?? [];
    }

    public function getInsight(int $userId, ?string $start, ?string $end): array
    {
        $types = "i"; $params = [$userId];
        $sql = "SELECT COUNT(*) total_sesi,
                       ROUND(SUM(durasi),2) total_jam,
                       ROUND(AVG(durasi),2) rata_sesi,
                       COUNT(DISTINCT tanggal) hari_aktif
                FROM log_belajar
                WHERE users_id=? AND waktu_selesai IS NOT NULL";
        $this->addDateFilter($sql, $types, $params, $start, $end);
        $ins = $this->fetchOne($sql, $types, $params);

        $types = "i"; $params = [$userId];
        $sql = "SELECT tanggal, ROUND(SUM(durasi),2) jam
                FROM log_belajar
                WHERE users_id=? AND waktu_selesai IS NOT NULL";
        $this->addDateFilter($sql, $types, $params, $start, $end);
        $sql .= " GROUP BY tanggal ORDER BY jam DESC LIMIT 1";
        $topDay = $this->fetchOne($sql, $types, $params);

        $types = "i"; $params = [$userId];
        $sql = "SELECT HOUR(waktu_mulai) jam, COUNT(*) total
                FROM log_belajar
                WHERE users_id=? AND waktu_selesai IS NOT NULL";
        $this->addDateFilter($sql, $types, $params, $start, $end);
        $sql .= " GROUP BY jam ORDER BY total DESC LIMIT 1";
        $peak = $this->fetchOne($sql, $types, $params);

        $types = "i"; $params = [$userId];
        $sql = "SELECT ROUND(MAX(durasi),2) max_durasi,
                       ROUND(MIN(durasi),2) min_durasi
                FROM log_belajar
                WHERE users_id=? AND waktu_selesai IS NOT NULL";
        $this->addDateFilter($sql, $types, $params, $start, $end);
        $mm = $this->fetchOne($sql, $types, $params);

        return [
            'Total Sesi' => $ins['total_sesi'] ?? 0,
            'Total Jam' => $ins['total_jam'] ?? 0,
            'Hari Aktif' => $ins['hari_aktif'] ?? 0,
            'Hari Terproduktif' => $topDay['tanggal'] ?? '-',
            'Jam Favorit Mulai' => isset($peak['jam']) ? sprintf('%02d:00', (int)$peak['jam']) : '-',
            'Sesi Terlama (jam)' => $mm['max_durasi'] ?? 0,
            'Sesi Tersingkat (jam)' => $mm['min_durasi'] ?? 0,
        ];
    }

    public function getSummaryHarian(int $userId, ?string $start, ?string $end): array
    {
        $types = "i"; $params = [$userId];
        $sql = "SELECT tanggal,
                       COUNT(*) sesi,
                       ROUND(SUM(durasi),2) total_jam,
                       ROUND(AVG(durasi),2) rata_jam
                FROM log_belajar
                WHERE users_id=? AND waktu_selesai IS NOT NULL";
        $this->addDateFilter($sql, $types, $params, $start, $end);
        $sql .= " GROUP BY tanggal ORDER BY tanggal";
        return $this->fetchAll($sql, $types, $params);
    }

    public function getStatistikMateri(int $userId, ?string $start, ?string $end): array
    {
        $types = "i"; $params = [$userId];
        $sql = "SELECT COALESCE(m.nama_materi,'-') nama_materi,
                       COUNT(lb.id_logbelajar) sesi,
                       ROUND(SUM(lb.durasi),2) total_jam,
                       ROUND(AVG(lb.durasi),2) rata_jam
                FROM log_belajar lb
                LEFT JOIN materi m ON lb.materi_id = m.id_materi
                WHERE lb.users_id=? AND lb.waktu_selesai IS NOT NULL";
        $this->addDateFilter($sql, $types, $params, $start, $end);
        $sql .= " GROUP BY m.id_materi, m.nama_materi ORDER BY total_jam DESC";
        return $this->fetchAll($sql, $types, $params);
    }

    public function getDetailSesi(int $userId, ?string $start, ?string $end): array
    {
        $types = "i"; $params = [$userId];
        $sql = "SELECT lb.tanggal, lb.waktu_mulai, lb.waktu_selesai,
                       ROUND(lb.durasi,2) durasi,
                       COALESCE(m.nama_materi,'-') nama_materi,
                       COALESCE(sm.nama_subMateri,'-') nama_subMateri
                FROM log_belajar lb
                LEFT JOIN materi m ON lb.materi_id = m.id_materi
                LEFT JOIN submateri sm ON lb.submateri_id = sm.id_subMateri
                WHERE lb.users_id=? AND lb.waktu_selesai IS NOT NULL";
        $this->addDateFilter($sql, $types, $params, $start, $end);
        $sql .= " ORDER BY lb.waktu_mulai";
        return $this->fetchAll($sql, $types, $params);
    }
}
