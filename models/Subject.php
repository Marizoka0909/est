<?php
/**
 * SOS-Estudante - Model: Subject (Disciplinas)
 * Acesso a dados de matérias e disciplinas acadêmicas
 */

require_once __DIR__ . '/../config/bd.php';

class Subject {
    private ?PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function allByUserId(int $userId): array {
        if (!$this->db) return [];
        $sql = "SELECT d.*, 
                (SELECT COUNT(*) FROM tarefas t WHERE t.disciplina_id = d.id AND t.status != 'concluida') AS tarefas_pendentes,
                (SELECT COUNT(*) FROM notas n WHERE n.disciplina_id = d.id) AS total_avaliacoes,
                (SELECT ROUND(SUM(n.nota_obtida * n.peso) / NULLIF(SUM(n.peso), 0), 2) FROM notas n WHERE n.disciplina_id = d.id) AS media_atual
                FROM disciplinas d 
                WHERE d.usuario_id = :usuario_id 
                ORDER BY d.nome ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id, int $userId): ?array {
        if (!$this->db) return null;
        $sql = "SELECT * FROM disciplinas WHERE id = :id AND usuario_id = :usuario_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':usuario_id' => $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): ?int {
        if (!$this->db) return null;
        $sql = "INSERT INTO disciplinas (usuario_id, nome, codigo, professor, sala, cor, semestre, carga_horaria, media_minima, status) 
                VALUES (:usuario_id, :nome, :codigo, :professor, :sala, :cor, :semestre, :carga_horaria, :media_minima, :status)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':usuario_id'    => $data['usuario_id'],
            ':nome'          => $data['nome'],
            ':codigo'        => $data['codigo'] ?? null,
            ':professor'     => $data['professor'] ?? null,
            ':sala'          => $data['sala'] ?? null,
            ':cor'           => $data['cor'] ?? '#4F46E5',
            ':semestre'      => $data['semestre'] ?? null,
            ':carga_horaria' => $data['carga_horaria'] ?? null,
            ':media_minima'  => $data['media_minima'] ?? 7.00,
            ':status'        => $data['status'] ?? 'em_andamento'
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $userId, array $data): bool {
        if (!$this->db) return false;
        $sql = "UPDATE disciplinas 
                SET nome = :nome, codigo = :codigo, professor = :professor, sala = :sala, 
                    cor = :cor, semestre = :semestre, carga_horaria = :carga_horaria, 
                    media_minima = :media_minima, status = :status 
                WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'            => $id,
            ':usuario_id'    => $userId,
            ':nome'          => $data['nome'],
            ':codigo'        => $data['codigo'] ?? null,
            ':professor'     => $data['professor'] ?? null,
            ':sala'          => $data['sala'] ?? null,
            ':cor'           => $data['cor'] ?? '#4F46E5',
            ':semestre'      => $data['semestre'] ?? null,
            ':carga_horaria' => $data['carga_horaria'] ?? null,
            ':media_minima'  => $data['media_minima'] ?? 7.00,
            ':status'        => $data['status'] ?? 'em_andamento'
        ]);
    }

    public function delete(int $id, int $userId): bool {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("DELETE FROM disciplinas WHERE id = :id AND usuario_id = :usuario_id");
        return $stmt->execute([':id' => $id, ':usuario_id' => $userId]);
    }

    public function countByUserId(int $userId): int {
        if (!$this->db) return 0;
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM disciplinas WHERE usuario_id = :usuario_id");
        $stmt->execute([':usuario_id' => $userId]);
        return (int) $stmt->fetchColumn();
    }
}
