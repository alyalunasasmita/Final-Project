<?php
namespace App\Services;

use Dompdf\Dompdf;

final class LaporanBelajarPdfExporter
{
    private \mysqli $db;

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
        $hari = $this->hari((int)$dt->format('w') + 1);
        return $hari . ', ' . $dt->format('d m Y');
    }

    public function download(int $userId, ?int $month = null, ?int $year = null): void
    {
        // ===== Range waktu sama persis dengan CSV =====
        $tz = new \DateTimeZone('Asia/Jakarta');

        if ($month && $year) {
            $start = new \DateTimeImmutable(sprintf('%04d-%02d-01 00:00:00', $year, $month), $tz);
            $end   = $start->modify('first day of next month'); // exclusive
            $label = $start->format('F Y');
        } else {
            $end   = new \DateTimeImmutable('now', $tz);
            $start = $end->modify('-30 days');
            $label = $start->format('d M Y') . ' - ' . $end->format('d M Y');
        }

        $startStr = $start->format('Y-m-d H:i:s');
        $endStr   = $end->format('Y-m-d H:i:s');

        // ===== Nama user =====
        $stmt = $this->db->prepare("SELECT nama FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $u = $stmt->get_result()->fetch_assoc();
        $namaUser = htmlspecialchars($u['nama'] ?? 'User', ENT_QUOTES, 'UTF-8');

        // ===== TOTAL SESI & JAM =====
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total_sesi, SUM(durasi) AS total_jam
            FROM log_belajar
            WHERE users_id = ?
              AND waktu_selesai IS NOT NULL
              AND waktu_mulai >= ?
              AND waktu_mulai < ?
        ");
        $stmt->bind_param("iss", $userId, $startStr, $endStr);
        $stmt->execute();
        $total = $stmt->get_result()->fetch_assoc();
        $totalSesi = (int)($total['total_sesi'] ?? 0);
        $totalJam  = (float)($total['total_jam'] ?? 0);

        // ===== HARI TERPRODUKTIF =====
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
        $hariProduktif = !empty($hari['hari']) ? $this->hari((int)$hari['hari']) : '-';

        // ===== JAM FAVORIT =====
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
        $jamFavorit = isset($jam['jam']) ? sprintf('%02d:00', (int)$jam['jam']) : '-';

        // ===== SESI TERLAMA =====
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
        $durasiMax  = (float)($lama['durasi'] ?? 0);

        // ===== SUMMARY HARIAN (PAKAI RANGE!) =====
        $summaryHarian = [];
        $stmt = $this->db->prepare("
            SELECT DATE(waktu_mulai) AS tanggal, COUNT(*) AS sesi, SUM(durasi) AS total_jam
            FROM log_belajar
            WHERE users_id = ?
              AND waktu_selesai IS NOT NULL
              AND waktu_mulai >= ?
              AND waktu_mulai < ?
            GROUP BY DATE(waktu_mulai)
            ORDER BY tanggal ASC
        ");
        $stmt->bind_param("iss", $userId, $startStr, $endStr);
        $stmt->execute();
        $rows = $stmt->get_result();
        while ($r = $rows->fetch_assoc()) {
            if ((int)$r['sesi'] <= 0) {
                continue;
            }
            $r['tanggal'] = $this->formatTanggalhari($r['tanggal']);

            $summaryHarian[] = $r;
        }


        // ===== DETAIL SESI (PAKAI RANGE!) =====
        $detailSesi = [];
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
              AND lb.waktu_mulai >= ?
              AND lb.waktu_mulai < ?
            ORDER BY lb.waktu_mulai ASC
        ");
        $stmt->bind_param("iss", $userId, $startStr, $endStr);
        $stmt->execute();

        $rows = $stmt->get_result();
        while ($r = $rows->fetch_assoc()) {

            // skip kalau durasi 0
            if ((float)$r['durasi'] <= 0) {
                continue;
            }

            // tanggal jadi: "Kamis, 01 01 2026"
            $r['tanggal'] = $this->formatTanggalhari($r['tanggal']);

            // format jam biar rapi
            $r['mulai'] = date('H:i', strtotime($r['waktu_mulai']));
            $r['selesai'] = date('H:i', strtotime($r['waktu_selesai']));

            // optional: rapihin angka durasi
            $r['durasi'] = number_format((float)$r['durasi'], 2);

            // ✅ masuk ke detailSesi (bukan summaryHarian)
            $detailSesi[] = $r;
        }


        // ===== Bangun HTML laporan =====
        $css = "
        <style>
          @page { margin: 18px 22px; }
          body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color:#111; }
          .title { text-align:center; font-size:16px; font-weight:700; letter-spacing:.5px; margin:0; }
          .meta { margin: 8px 0 12px; }
          .meta div { margin: 2px 0; }

          .section { margin-top: 14px; }
          .section h3 { font-size:12px; margin:0 0 8px; padding:6px 8px; background:#f2f2f2; border:1px solid #ddd; }

          .insights { border:1px solid #ddd; padding:10px; border-radius:4px; }
          .insights ul { margin:0; padding-left:16px; }
          .insights li { margin: 4px 0; }

          table { width:100%; border-collapse:collapse; table-layout:fixed; }
          thead { display: table-header-group; }
          th, td { border:1px solid #ddd; padding:6px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
          th { background:#f7f7f7; text-align:center; font-weight:700; }
          tbody tr:nth-child(even) td { background:#fcfcfc; }

          .right { text-align:right; }
          .center { text-align:center; }
          .wrap { white-space:normal; }
          .small { font-size:10px; color:#555; }

          .w-date { width: 14%; }
          .w-time { width: 11%; }
          .w-dur  { width: 10%; }
          .w-mat  { width: 28%; }
          .w-sub  { width: 37%; }
        </style>
        ";

        $html = "<html><head><meta charset='utf-8'>{$css}</head><body>";

        $html .= "<p class='title'>LAPORAN AKTIVITAS BELAJAR</p>";
        $html .= "<div class='meta'>
                    <div><b>Nama:</b> {$namaUser}</div>
                    <div><b>Periode:</b> " . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . "</div>
                  </div>";

        $html .= "<div class='section'><h3>Keterangan</h3><div class='insights'><ul>";
        $html .= "<li>Kamu telah belajar <b>" . number_format($totalJam, 2) . "</b> jam dari <b>{$totalSesi}</b> sesi belajar.</li>";
        if ($hariProduktif !== '-') $html .= "<li>Hari paling sering belajar adalah <b>{$hariProduktif}</b>.</li>";
        if ($jamFavorit !== '-') $html .= "<li>Kamu paling sering memulai belajar pada pukul <b>{$jamFavorit}</b>.</li>";
        if ($tglTerlama) $html .= "<li>Sesi belajar terlama terjadi pada <b>{$tanggalIndo}</b> dengan durasi <b>" . number_format($durasiMax, 2) . "</b> jam.</li>";
        $html .= "</ul><div class='small'>Catatan: hanya sesi yang sudah selesai.</div></div></div>";

        // Summary Harian
        $html .= "<div class='section'><h3>SUMMARY HARIAN</h3>
                  <table>
                    <thead><tr>
                      <th class='w-date'>Tanggal</th>
                      <th class='center'>Jumlah Sesi</th>
                      <th class='right'>Total Jam</th>
                    </tr></thead><tbody>";

        foreach ($summaryHarian as $s) {
            $tgl = htmlspecialchars($s['tanggal'] ?? '-', ENT_QUOTES, 'UTF-8');
            $sesi = (int)($s['sesi'] ?? 0);
            $tj = number_format((float)($s['total_jam'] ?? 0), 2);
            $html .= "<tr>
                        <td>{$tgl}</td>
                        <td class='center'>{$sesi}</td>
                        <td class='right'>{$tj}</td>
                      </tr>";
        }

        $html .= "</tbody></table></div>";

        // Detail Sesi
        $html .= "<div class='section'><h3>DETAIL SESI</h3>
                  <table>
                    <thead><tr>
                      <th class='w-date'>Tanggal</th>
                      <th class='w-time'>Mulai</th>
                      <th class='w-time'>Selesai</th>
                      <th class='w-dur'>Durasi (Jam)</th>
                      <th class='w-mat'>Materi</th>
                      <th class='w-sub'>Sub Materi</th>
                    </tr></thead><tbody>";

        foreach ($detailSesi as $d) {
            $tgl = htmlspecialchars($d['tanggal'] ?? '-', ENT_QUOTES, 'UTF-8');
            $mulai = htmlspecialchars(date('H:i', strtotime($d['waktu_mulai'] ?? '')), ENT_QUOTES, 'UTF-8');
            $selesai = htmlspecialchars(date('H:i', strtotime($d['waktu_selesai'] ?? '')), ENT_QUOTES, 'UTF-8');
            $dur = number_format((float)($d['durasi'] ?? 0), 2);

            $materi = htmlspecialchars($d['nama_materi'] ?? '-', ENT_QUOTES, 'UTF-8');
            $sub = htmlspecialchars($d['nama_subMateri'] ?? '-', ENT_QUOTES, 'UTF-8');

            $html .= "<tr>
                        <td>{$tgl}</td>
                        <td class='center'>{$mulai}</td>
                        <td class='center'>{$selesai}</td>
                        <td class='right'>{$dur}</td>
                        <td class='wrap'>{$materi}</td>
                        <td class='wrap'>{$sub}</td>
                      </tr>";
        }

        $html .= "</tbody></table></div>";

        $html .= "</body></html>";

        // Render PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // karena kolom banyak
        $dompdf->render();

        header('Content-Type: application/pdf');
        $dompdf->stream('laporan_belajar.pdf', ['Attachment' => true]);
        exit;
    }
}
