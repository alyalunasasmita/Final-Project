<?php
namespace app\service;

require_once __DIR__ . '/../../config/nyambung.php'; 
use app\database\Database; 

class QuizRepository {
    private \mysqli $db;

    public function __construct() {
        $this->db = (new Database())->db; 
    }

    public function createAttempt(int $userId, int $submateriId): int {
        $stmt = $this->db->prepare(
            "INSERT INTO percobaan_kuis (user_id, submateri_id) VALUES (?, ?)"
        );
        $stmt->bind_param("ii", $userId, $submateriId);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function getRandomSoal(int $submateriId): ?array {
        $q = $this->db->prepare("
            SELECT id, submateri_id, question, explanation
            FROM soal_kuis
            WHERE submateri_id=? AND is_active=1
            ORDER BY RAND()
            LIMIT 5
        ");
        $q->bind_param("i", $submateriId);
        $q->execute();
        $row = $q->get_result()->fetch_assoc();
        return $row ?: null;
    }


    public function getOpsi(int $soalId): array {
        $stmt = $this->db->prepare(
            "SELECT id, option_text FROM opsi_jawaban WHERE soal_id=?"
        );
        $stmt->bind_param("i", $soalId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function cekJawaban(int $opsiId, int $soalId): bool {
        $stmt = $this->db->prepare(
            "SELECT is_correct FROM opsi_jawaban WHERE id=? AND soal_id=?"
        );
        $stmt->bind_param("ii", $opsiId, $soalId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row && $row['is_correct'] == 1;
    }

    public function finishAttempt(int $attemptId, int $score): void {
        $stmt = $this->db->prepare(
            "UPDATE percobaan_kuis
             SET finished_at=NOW(), total_questions=1,
                 correct_count=?, score_percent=?
             WHERE id=?"
        );
        $stmt->bind_param("iii", $score, $score, $attemptId);
        $stmt->execute();
    }

    public function getPembahasan(int $soalId): ?string {
        $stmt = $this->db->prepare(
            "SELECT explanation FROM soal_kuis WHERE id=?"
        );
        $stmt->bind_param("i", $soalId);
        $stmt->execute();
        return $stmt->get_result()->fetch_column();
    }

    public function getMateriIdBySubmateri(int $submateriId): int {
        $q = $this->db->prepare("SELECT materi_id_materi FROM submateri WHERE id_subMateri=? LIMIT 1");
        $q->bind_param("i", $submateriId);
        $q->execute();
        $row = $q->get_result()->fetch_assoc();
        return (int)($row["materi_id_materi"] ?? 0);
    }

    public function getRandomSoalByMateri(int $materiId): ?array {
        $q = $this->db->prepare("
            SELECT s.id, s.submateri_id, s.question, s.explanation
            FROM soal_kuis s
            JOIN submateri sm ON sm.id_subMateri = s.submateri_id
            WHERE sm.materi_id_materi = ?
            AND s.is_active = 1
            AND sm.deleted_at IS NULL
            ORDER BY RAND()
            LIMIT 1
        ");
        $q->bind_param("i", $materiId);
        $q->execute();
        $row = $q->get_result()->fetch_assoc();
        return $row ?: null;
    }


    public function getRandomSoalBatch(int $submateriId, int $limit = 3): array {
    $q = $this->db->prepare("
        SELECT id, submateri_id, question, explanation
        FROM soal_kuis
        WHERE submateri_id = ? AND is_active = 1
        ORDER BY RAND()
        LIMIT ?
    ");
    $q->bind_param("ii", $submateriId, $limit);
    $q->execute();
    return $q->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
    }

    public function getRandomSoalBatchByMateri(int $materiId, int $limit = 3): array {
    $q = $this->db->prepare("
        SELECT s.id, s.submateri_id, s.question, s.explanation
        FROM soal_kuis s
        JOIN submateri sm ON sm.id_subMateri = s.submateri_id
        WHERE sm.materi_id_materi = ?
        AND s.is_active = 1
        AND sm.deleted_at IS NULL
        ORDER BY RAND()
        LIMIT ?
    ");
    $q->bind_param("ii", $materiId, $limit);
    $q->execute();
    return $q->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
    }


}
