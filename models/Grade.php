<?php
/**
 * SOS-Estudante - Model: Grade (Notas e Desempenho Acadêmico)
 */

require_once __DIR__ . '/../config/bd.php';

class Grade {
    private ?PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function allByUserId(int $userId): array {
        if (!$this->db) return [];
        $sql = "SELECT n.*, d.nome AS disciplina_nome, d.cor AS disciplina_cor, d.media_minima 
                FROM notas n 
                INNER JOIN disciplinas d ON n.disciplina_id = d.id 
                WHERE n.usuario_id = :usuario_id 
                ORDER BY d.nome ASC, n.data_avaliacao DESC, n.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function allBySubject(int $subjectId, int $userId): array {
        if (!$this->db) return [];
        $sql = "SELECT n.* FROM notas n 
                WHERE n.disciplina_id = :disciplina_id AND n.usuario_id = :usuario_id 
                ORDER BY n.data_avaliacao ASC, n.id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':disciplina_id' => $subjectId, ':usuario_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id, int $userId): ?array {
        if (!$this->db) return null;
        $sql = "SELECT n.*, d.nome AS disciplina_nome 
                FROM notas n 
                INNER JOIN disciplinas d ON n.disciplina_id = d.id 
                WHERE n.id = :id AND n.usuario_id = :usuario_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':usuario_id' => $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): ?int {
        if (!$this->db) return null;
        $sql = "INSERT INTO notas (usuario_id, disciplina_id, titulo, peso, nota_obtida, nota_maxima, data_avaliacao, observacoes) 
                VALUES (:usuario_id, :disciplina_id, :titulo, :peso, :nota_obtida, :nota_maxima, :data_avaliacao, :observacoes)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':usuario_id'      => $data['usuario_id'],
            ':disciplina_id'   => $data['disciplina_id'],
            ':titulo'          => $data['titulo'],
            ':peso'            => $data['peso'] ?? 1.00,
            ':nota_obtida'     => $data['nota_obtida'],
            ':nota_maxima'     => $data['nota_maxima'] ?? 10.00,
            ':data_avaliacao'  => !empty($data['data_avaliacao']) ? $data['data_avaliacao'] : null,
            ':observacoes'     => $data['observacoes'] ?? null
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $userId, array $data): bool {
        if (!$this->db) return false;
        $sql = "UPDATE notas 
                SET disciplina_id = :disciplina_id, titulo = :titulo, peso = :peso, 
                    nota_obtida = :nota_obtida, nota_maxima = :nota_maxima, 
                    data_avaliacao = :data_avaliacao, observacoes = :observacoes 
                WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'             => $id,
            ':usuario_id'     => $userId,
            ':disciplina_id'  => $data['disciplina_id'],
            ':titulo'         => $data['titulo'],
            ':peso'           => $data['peso'] ?? 1.00,
            ':nota_obtida'    => $data['nota_obtida'],
            ':nota_maxima'    => $data['nota_maxima'] ?? 10.00,
            ':data_avaliacao' => !empty($data['data_avaliacao']) ? $data['data_avaliacao'] : null,
            ':observacoes'    => $data['observacoes'] ?? null
        ]);
    }

    public function delete(int $id, int $userId): bool {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("DELETE FROM notas WHERE id = :id AND usuario_id = :usuario_id");
        return $stmt->execute([':id' => $id, ':usuario_id' => $userId]);
    }

    public function getOverallAverage(int $userId): float {
        if (!$this->db) return 0.0;
        $sql = "SELECT ROUND(AVG(nota_normalizada), 2) AS media_geral FROM (
                    SELECT (nota_obtida / nota_maxima) * 10 AS nota_normalizada 
                    FROM notas 
                    WHERE usuario_id = :usuario_id
                ) AS sub";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $userId]);
        $avg = $stmt->fetchColumn();
        return $avg !== null ? (float) $avg : 0.0;
    }
}
