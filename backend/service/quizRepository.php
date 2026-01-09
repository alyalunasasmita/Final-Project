<?php
namespace app\service;

require_once __DIR__ . '/../../config/nyambung.php';  
use app\database\Database;  

class QuizRepository {
    private \mysqli $db;

    public function __construct() {
        $this->db = (new Database())->db;  
    }

    public function createAttempt(int $userId, int $submateriId, int $totalQuestions): int {
        $stmt = $this->db->prepare(
            "INSERT INTO percobaan_kuis (user_id, submateri_id, total_questions) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("iii", $userId, $submateriId, $totalQuestions);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function getAttempt(int $attemptId): ?array {
        $stmt = $this->db->prepare("SELECT total_questions, correct_count FROM percobaan_kuis WHERE id=?");
        $stmt->bind_param("i", $attemptId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public function getRandomSoal(int $submateriId, int $limit = 5): array {
        $q = $this->db->prepare("
            SELECT id, submateri_id, question, explanation
            FROM soal_kuis
            WHERE submateri_id=? AND is_active=1
            ORDER BY RAND()
            LIMIT ?
        ");
        $q->bind_param("ii", $submateriId, $limit);
        $q->execute();
        return $q->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
    }

    public function getRandomSoalByMateri(int $materiId, int $limit = 5): array {
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

    public function getOpsi(int $soalId): array {
        $stmt = $this->db->prepare(
            "SELECT id, option_text FROM opsi_jawaban WHERE soal_id=?"
        );
        $stmt->bind_param("i", $soalId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
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

    public function finishAttempt(int $attemptId, int $correctCount, int $totalQuestions): void {
        $scorePercent = ($totalQuestions > 0)
            ? ($correctCount / $totalQuestions) * 100
            : 0;

        $stmt = $this->db->prepare("
            UPDATE percobaan_kuis
            SET finished_at = NOW(),
                total_questions = ?,
                correct_count = ?,
                score_percent = ?
            WHERE id = ?
        ");
        $stmt->bind_param("iidi", $totalQuestions, $correctCount, $scorePercent, $attemptId);
        $stmt->execute();
    }

    public function getPembahasan(int $soalId): ?string {
        $stmt = $this->db->prepare("SELECT explanation FROM soal_kuis WHERE id=?");
        $stmt->bind_param("i", $soalId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['explanation'] ?? null;
    }

    public function getMateriIdBySubmateri(int $submateriId): int {
        $q = $this->db->prepare("SELECT materi_id_materi FROM submateri WHERE id_subMateri=? LIMIT 1");
        $q->bind_param("i", $submateriId);
        $q->execute();
        $row = $q->get_result()->fetch_assoc();
        return (int)($row["materi_id_materi"] ?? 0);
    }
    
    public function getLatestQuizScoreByMateri(int $userId, int $materiId): ?float
    {
        $stmt = $this->db->prepare("
            SELECT pk.score_percent
            FROM percobaan_kuis pk
            JOIN submateri sm ON sm.id_subMateri = pk.submateri_id
            WHERE pk.user_id = ?
              AND sm.materi_id_materi = ?
              AND sm.deleted_at IS NULL
              AND pk.finished_at IS NOT NULL
            ORDER BY pk.created_at DESC
            LIMIT 1
        ");
        $stmt->bind_param("ii", $userId, $materiId);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        return $row ? (float)$row['score_percent'] : null;
    }
    
    public function getLatestAttemptByMateri(int $userId, int $materiId): ?array
{
    $stmt = $this->db->prepare("
        SELECT 
            pk.score_percent,
            pk.correct_count,
            pk.total_questions,
            pk.created_at,
            pk.finished_at
        FROM percobaan_kuis pk
        JOIN submateri sm ON sm.id_subMateri = pk.submateri_id
        WHERE pk.user_id = ?
          AND sm.materi_id_materi = ?
          AND sm.deleted_at IS NULL
          AND pk.finished_at IS NOT NULL
        ORDER BY pk.finished_at DESC
        LIMIT 1
    ");
    $stmt->bind_param("ii", $userId, $materiId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row) return null;

    return [
        'score_percent'   => (float)($row['score_percent'] ?? 0),
        'correct_count'   => (int)($row['correct_count'] ?? 0),
        'total_questions' => (int)($row['total_questions'] ?? 0),
        'finished_at'     => $row['finished_at'] ?? null,
        'created_at'      => $row['created_at'] ?? null,
    ];
}

}