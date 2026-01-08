<?php
namespace App\Services;

final class LaporanBelajarCsvExporter
{
    private $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    private function hari(int $day): string
    {
        $hariMap = [
            1 => 'Minggu',
            2 => 'Senin',
            3 => 'Selasa',
            4 => 'Rabu',
            5 => 'Kamis',
            6 => 'Jumat',
            7 => 'Sabtu',
        ];
    
        return $hariMap[$day] ?? '-';
    }

    private function formatTanggalhari(string $date): string
    {
        $dt = new \DateTime($date);
    
        $hari = $this->hari(
            (int)$dt->format('w') + 1
        );
    
        return $hari . ', ' . $dt->format('d m Y');
    }



    public function download(int $userId, ?int $month = null, ?int $year = null): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=laporan_belajar.csv');

        $fh = fopen('php://output', 'w');
        
      
        /* ================= Range Waktu ================= */
        $tz = new \DateTimeZone('Asia/Jakarta');
        
        if ($month && $year) {
            $start = new \DateTimeImmutable(sprintf('%04d-%02d-01 00:00:00', $year, $month), $tz);
            $end   = $start->modify('first day of next month'); // exclusive
            $label = $start->format('F Y'); // "January 2026"
        } else {
            $end   = new \DateTimeImmutable('now', $tz);
            $start = $end->modify('-30 days');
            $label = $start->format('d M Y') . ' - ' . $end->format('d M Y');
        }
        
        // untuk bind param ke SQL (string)
        $startStr = $start->format('Y-m-d H:i:s');
        $endStr   = $end->format('Y-m-d H:i:s');

        /* ================= nama user ================= */
        $stmt = $this->db->prepare("SELECT nama FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $u = $stmt->get_result()->fetch_assoc();
        $namaUser = $u['nama'] ?? 'User';


        /* ================= TOTAL SESI & JAM ================= */

        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) AS total_sesi,
                SUM(durasi) AS total_jam
            FROM log_belajar
            WHERE users_id = ?
              AND waktu_selesai IS NOT NULL
              AND waktu_mulai >= ?
              AND waktu_mulai < ?
        ");
        $stmt->bind_param("iss", $userId, $startStr, $endStr);
        $stmt->execute();
        $total = $stmt->get_result()->fetch_assoc();


        $totalSesi = $total['total_sesi'] ?? 0;
        $totalJam  = $total['total_jam'] ?? 0;

        /* ================= HARI TERPRODUKTIF ================= */

       $stmt = $this->db->prepare("
            SELECT DAYOFWEEK(DATE(waktu_mulai)) AS hari, COUNT(*) AS sesi
            FROM log_belajar
            WHERE users_id = ?
              AND waktu_selesai IS NOT NULL
              AND waktu_mulai >= ?
              AND waktu_mulai < ?
            GROUP BY DAYOFWEEK(DATE(waktu_mulai))
            ORDER BY sesi DESC
            LIMIT 1
        ");
        $stmt->bind_param("iss", $userId, $startStr, $endStr);
        $stmt->execute();
        $hari = $stmt->get_result()->fetch_assoc();
        
        $hariProduktif = !empty($hari['hari'])
        ? $this->hari((int)$hari['hari'])
        : '-';




        /* ================= JAM FAVORIT ================= */

        $stmt = $this->db->prepare("
            SELECT HOUR(waktu_mulai) AS jam, COUNT(*) AS total
            FROM log_belajar
            WHERE users_id = ?
              AND waktu_selesai IS NOT NULL
              AND waktu_mulai >= ?
              AND waktu_mulai < ?
            GROUP BY HOUR(waktu_mulai)
            ORDER BY total DESC
            LIMIT 1
        ");
        $stmt->bind_param("iss", $userId, $startStr, $endStr);
        $stmt->execute();
        $jam = $stmt->get_result()->fetch_assoc();


        $jamFavorit = isset($jam['jam']) ? $jam['jam'] . ':00' : '-';

        /* ================= SESI TERLAMA ================= */

       $stmt = $this->db->prepare("
            SELECT DATE(waktu_mulai) AS tanggal, durasi
            FROM log_belajar
            WHERE users_id = ?
              AND waktu_selesai IS NOT NULL
              AND waktu_mulai >= ?
              AND waktu_mulai < ?
            ORDER BY durasi DESC
            LIMIT 1
        ");
        $stmt->bind_param("iss", $userId, $startStr, $endStr);
        $stmt->execute();
        $lama = $stmt->get_result()->fetch_assoc();

        $tglTerlama = $lama['tanggal'] ?? null;
        $tanggalIndo = $tglTerlama ? $this->formatTanggalhari($tglTerlama) : '-';
        $durasiMax  = $lama['durasi'] ?? 0;

        /* ================= INSIGHT KE CSV ================= */

        fputcsv($fh, ["LAPORAN AKTIVITAS BELAJAR"]);
        fputcsv($fh, ["Nama: {$namaUser}"]);
        fputcsv($fh, ["Periode: {$label}"]);
        fputcsv($fh, []);



        fputcsv($fh, ['Keterangan']);
        fputcsv(
            $fh,
            ["Kamu telah belajar {$totalJam} jam dari {$totalSesi} sesi belajar."]
        );

        if ($hariProduktif !== '-') {
        fputcsv($fh, ["Hari paling sering belajar adalah {$hariProduktif}."]);
         }


        if ($jamFavorit !== '-') {
            fputcsv($fh, ["Kamu paling sering memulai belajar pada pukul {$jamFavorit}."]);
        }

        if ($tglTerlama !== '-') {
            fputcsv(
                $fh,
                ["Sesi belajar terlama terjadi pada {$tanggalIndo} dengan durasi {$durasiMax} jam."]
            );
        }

        fputcsv($fh, []);

        /* ================= SUMMARY HARIAN ================= */

        fputcsv($fh, ['SUMMARY_HARIAN']);
        fputcsv($fh, ['Tanggal', 'Jumlah Sesi', 'Total Jam']);

        $stmt = $this->db->prepare("
            SELECT tanggal, COUNT(*) AS sesi, SUM(durasi) AS total_jam
            FROM log_belajar
            WHERE users_id = ?
              AND waktu_selesai IS NOT NULL
            GROUP BY tanggal
            ORDER BY tanggal ASC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $rows = $stmt->get_result();

        while ($r = $rows->fetch_assoc()) {
            fputcsv($fh, [
                $r['tanggal'],
                $r['sesi'],
                $r['total_jam']
            ]);
        }

        fputcsv($fh, []);

        /* ================= DETAIL SESI ================= */

        fputcsv($fh, ['DETAIL_SESI']);
        fputcsv($fh, ['Tanggal','Mulai','Selesai','Durasi (Jam)','Materi']);

        $stmt = $this->db->prepare("
            SELECT 
                DATE(lb.waktu_mulai) AS tanggal,
                lb.waktu_mulai,
                lb.waktu_selesai,
                lb.durasi,
                m.nama_materi, sm.nama_subMateri
            FROM log_belajar lb
            LEFT JOIN materi m ON lb.materi_id = m.id_materi
            LEFT JOIN submateri sm ON lb.submateri_id = sm.id_subMateri
            WHERE lb.users_id = ?
              AND lb.waktu_selesai IS NOT NULL
            ORDER BY lb.waktu_mulai ASC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $rows = $stmt->get_result();

        while ($r = $rows->fetch_assoc()) {
            fputcsv($fh, [
                $r['tanggal'],
                date('H:i', strtotime($r['waktu_mulai'])),
                date('H:i', strtotime($r['waktu_selesai'])),
                $r['durasi'],
                $r['nama_materi'], 
                $r['nama_subMateri']
            ]);
        }

        fclose($fh);
        exit;
    }
}
