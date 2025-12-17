<?php
namespace App\Materi;

require_once __DIR__ . '/../config/nyambung.php';
require_once __DIR__ . '/../api/apiyoutube.php';

use App\Database\Database;
use App\YouTube\ApiYouTube;

class Materi
{
    private $db;

    public function __construct()
    {
        $conn = new Database();
        $this->db = $conn->db; // kamu memang pakai $conn->db di Database
    }

    /**
     * Normalize input YouTube:
     * - kalau kosong -> null
     * - kalau URL -> extract jadi ID + type (video/playlist)
     * - kalau sudah ID -> biarkan
     */
    private function normalizeYouTube(?string $input, string $playlist_type_default = 'video'): array
    {
        $input = trim((string)$input);

        if ($input === '') {
            return [
                'playlist_id' => null,
                'playlist_type' => $playlist_type_default,
            ];
        }

        // sudah berupa ID (bukan URL)
        if (!str_contains($input, 'http') && !str_contains($input, 'youtu')) {
            return [
                'playlist_id' => $input,
                'playlist_type' => $playlist_type_default,
            ];
        }

        // URL -> extract
        $yt = new ApiYouTube();
        $info = $yt->extractYouTubeData($input);

        if (!$info || !isset($info['id'], $info['type'])) {
            throw new \Exception("Link YouTube tidak valid");
        }

        return [
            'playlist_id' => $info['id'],     // ✅ ID doang
            'playlist_type' => $info['type'], // ✅ video / playlist
        ];
    }

    /**
     * CREATE - Tambah materi baru
     * NOTE: parameter ke-3 bisa URL atau ID. Akan dinormalize otomatis.
     */
    public function tambahMateri(
        string $nama,
        ?string $deskripsi = null,
        ?string $playlist_id = null,
        string $playlist_type = 'video'
    ): array {
        try {
            // ✅ normalize youtube URL/ID
            $ytNorm = $this->normalizeYouTube($playlist_id, $playlist_type);
            $playlist_id = $ytNorm['playlist_id'];
            $playlist_type = $ytNorm['playlist_type'];

            $stmt = $this->db->prepare("
                INSERT INTO materi (nama_materi, deskripsi_materi, playlist_id, playlist_type)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->bind_param("ssss", $nama, $deskripsi, $playlist_id, $playlist_type);

            if ($stmt->execute()) {
                $id = $this->db->insert_id;
                return [
                    'success' => true,
                    'id' => $id,
                    'message' => 'Materi berhasil ditambahkan',
                    'data' => [
                        'id_materi' => $id,
                        'nama_materi' => $nama,
                        'deskripsi_materi' => $deskripsi,
                        'playlist_id' => $playlist_id,
                        'playlist_type' => $playlist_type
                    ]
                ];
            }

            throw new \Exception("Gagal menambahkan materi: " . $stmt->error);

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * READ - Lihat semua materi
     */
    public function lihatMateri(): array
    {
        try {
            $query = "
                SELECT 
                    id_materi, 
                    nama_materi, 
                    deskripsi_materi, 
                    playlist_id,
                    playlist_type
                FROM materi
                ORDER BY id_materi DESC
            ";

            $result = $this->db->query($query);

            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Query error: ' . $this->db->error,
                    'data' => []
                ];
            }

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            return [
                'success' => true,
                'data' => $data,
                'total' => count($data)
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * READ - Dapatkan materi berdasarkan ID
     */
    public function getMateriById(int $id): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    id_materi, 
                    nama_materi, 
                    deskripsi_materi, 
                    playlist_id,
                    playlist_type
                FROM materi 
                WHERE id_materi = ?
            ");

            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                return [
                    'success' => false,
                    'message' => 'Materi tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => $result->fetch_assoc()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * UPDATE - Update materi
     * NOTE: parameter playlist_id bisa URL atau ID. Akan dinormalize otomatis.
     */
    public function updateMateri(
        int $id,
        string $nama,
        ?string $deskripsi = null,
        ?string $playlist_id = null,
        string $playlist_type = 'video'
    ): array {
        try {
            $check = $this->getMateriById($id);
            if (!$check['success']) return $check;

            // ✅ normalize youtube URL/ID
            $ytNorm = $this->normalizeYouTube($playlist_id, $playlist_type);
            $playlist_id = $ytNorm['playlist_id'];
            $playlist_type = $ytNorm['playlist_type'];

            $stmt = $this->db->prepare("
                UPDATE materi 
                SET 
                    nama_materi = ?, 
                    deskripsi_materi = ?, 
                    playlist_id = ?,
                    playlist_type = ?
                WHERE id_materi = ?
            ");

            $stmt->bind_param("ssssi", $nama, $deskripsi, $playlist_id, $playlist_type, $id);

            if ($stmt->execute()) {
                $affectedRows = $stmt->affected_rows;

                return [
                    'success' => true,
                    'affected_rows' => $affectedRows,
                    'message' => $affectedRows > 0 ? 'Materi berhasil diupdate' : 'Tidak ada perubahan',
                    'data' => [
                        'id_materi' => $id,
                        'nama_materi' => $nama,
                        'deskripsi_materi' => $deskripsi,
                        'playlist_id' => $playlist_id,
                        'playlist_type' => $playlist_type
                    ]
                ];
            }

            throw new \Exception("Gagal mengupdate materi: " . $stmt->error);

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * DELETE - Hapus materi
     */
    public function deleteMateri(int $id): array
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM materi WHERE id_materi = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $affectedRows = $stmt->affected_rows;

                return [
                    'success' => true,
                    'affected_rows' => $affectedRows,
                    'message' => $affectedRows > 0 ? 'Materi berhasil dihapus' : 'Materi tidak ditemukan'
                ];
            }

            throw new \Exception("Gagal menghapus materi: " . $stmt->error);

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getLastInsertId(): int
    {
        return $this->db->insert_id;
    }
}
