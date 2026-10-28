<?php
/**
 * SOS-Estudante - Model: Task (Tarefas e Atividades Escolares)
 * Acesso a dados de tarefas com relacionamentos e filtros
 */

require_once __DIR__ . '/../config/bd.php';

class Task {
    private ?PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function allByUserId(int $userId, ?string $status = null, ?string $priority = null, ?int $subjectId = null): array {
        if (!$this->db) return [];
        $sql = "SELECT t.*, d.nome AS disciplina_nome, d.cor AS disciplina_cor 
                FROM tarefas t 
                LEFT JOIN disciplinas d ON t.disciplina_id = d.id 
                WHERE t.usuario_id = :usuario_id";
        $params = [':usuario_id' => $userId];

        if ($status && $status !== 'todos') {
            $sql .= " AND t.status = :status";
            $params[':status'] = $status;
        }

        if ($priority && $priority !== 'todas') {
            $sql .= " AND t.prioridade = :prioridade";
            $params[':prioridade'] = $priority;
        }

        if ($subjectId) {
            $sql .= " AND t.disciplina_id = :disciplina_id";
            $params[':disciplina_id'] = $subjectId;
        }

        $sql .= " ORDER BY 
                    CASE WHEN t.status = 'pendente' THEN 1 
                         WHEN t.status = 'em_andamento' THEN 2 
                         ELSE 3 END,
                    t.data_entrega ASC, 
                    t.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id, int $userId): ?array {
        if (!$this->db) return null;
        $sql = "SELECT t.*, d.nome AS disciplina_nome, d.cor AS disciplina_cor 
                FROM tarefas t 
                LEFT JOIN disciplinas d ON t.disciplina_id = d.id 
                WHERE t.id = :id AND t.usuario_id = :usuario_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':usuario_id' => $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): ?int {
        if (!$this->db) return null;
        $sql = "INSERT INTO tarefas (usuario_id, disciplina_id, titulo, descricao, data_entrega, prioridade, status) 
                VALUES (:usuario_id, :disciplina_id, :titulo, :descricao, :data_entrega, :prioridade, :status)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':usuario_id'    => $data['usuario_id'],
            ':disciplina_id' => !empty($data['disciplina_id']) ? $data['disciplina_id'] : null,
            ':titulo'        => $data['titulo'],
            ':descricao'     => $data['descricao'] ?? null,
            ':data_entrega'  => !empty($data['data_entrega']) ? $data['data_entrega'] : null,
            ':prioridade'    => $data['prioridade'] ?? 'media',
            ':status'        => $data['status'] ?? 'pendente'
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $userId, array $data): bool {
        if (!$this->db) return false;
        $concluidaEm = ($data['status'] === 'concluida') ? date('Y-m-d H:i:s') : null;

        $sql = "UPDATE tarefas 
                SET disciplina_id = :disciplina_id, titulo = :titulo, descricao = :descricao, 
                    data_entrega = :data_entrega, prioridade = :prioridade, status = :status, 
                    concluida_em = :concluida_em 
                WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'            => $id,
            ':usuario_id'    => $userId,
            ':disciplina_id' => !empty($data['disciplina_id']) ? $data['disciplina_id'] : null,
            ':titulo'        => $data['titulo'],
            ':descricao'     => $data['descricao'] ?? null,
            ':data_entrega'  => !empty($data['data_entrega']) ? $data['data_entrega'] : null,
            ':prioridade'    => $data['prioridade'] ?? 'media',
            ':status'        => $data['status'] ?? 'pendente',
            ':concluida_em'  => $concluidaEm
        ]);
    }

    public function updateStatus(int $id, int $userId, string $status): bool {
        if (!$this->db) return false;
        $concluidaEm = ($status === 'concluida') ? date('Y-m-d H:i:s') : null;
        $stmt = $this->db->prepare("UPDATE tarefas SET status = :status, concluida_em = :concluida_em WHERE id = :id AND usuario_id = :usuario_id");
        return $stmt->execute([
            ':id'           => $id,
            ':usuario_id'   => $userId,
            ':status'       => $status,
            ':concluida_em' => $concluidaEm
        ]);
    }

    public function delete(int $id, int $userId): bool {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id");
        return $stmt->execute([':id' => $id, ':usuario_id' => $userId]);
    }

    public function getUpcoming(int $userId, int $limit = 5): array {
        if (!$this->db) return [];
        $sql = "SELECT t.*, d.nome AS disciplina_nome, d.cor AS disciplina_cor 
                FROM tarefas t 
                LEFT JOIN disciplinas d ON t.disciplina_id = d.id 
                WHERE t.usuario_id = :usuario_id AND t.status != 'concluida' 
                  AND t.data_entrega IS NOT NULL 
                ORDER BY t.data_entrega ASC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':usuario_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByStatus(int $userId): array {
        if (!$this->db) return ['total' => 0, 'pendente' => 0, 'em_andamento' => 0, 'concluida' => 0];
        $sql = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN status = 'pendente' THEN 1 ELSE 0 END) AS pendente,
                    SUM(CASE WHEN status = 'em_andamento' THEN 1 ELSE 0 END) AS em_andamento,
                    SUM(CASE WHEN status = 'concluida' THEN 1 ELSE 0 END) AS concluida
                FROM tarefas 
                WHERE usuario_id = :usuario_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $userId]);
        $res = $stmt->fetch();
        return [
            'total'        => (int) ($res['total'] ?? 0),
            'pendente'     => (int) ($res['pendente'] ?? 0),
            'em_andamento' => (int) ($res['em_andamento'] ?? 0),
            'concluida'    => (int) ($res['concluida'] ?? 0)
        ];
    }
}
