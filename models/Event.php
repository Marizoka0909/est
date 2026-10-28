<?php
/**
 * SOS-Estudante - Model: Event (Eventos, Provas e Agenda Acadêmica)
 */

require_once __DIR__ . '/../config/bd.php';

class Event {
    private ?PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function allByUserId(int $userId, ?string $tipo = null): array {
        if (!$this->db) return [];
        $sql = "SELECT e.*, d.nome AS disciplina_nome, d.cor AS disciplina_cor 
                FROM eventos e 
                LEFT JOIN disciplinas d ON e.disciplina_id = d.id 
                WHERE e.usuario_id = :usuario_id";
        $params = [':usuario_id' => $userId];

        if ($tipo && $tipo !== 'todos') {
            $sql .= " AND e.tipo = :tipo";
            $params[':tipo'] = $tipo;
        }

        $sql .= " ORDER BY e.data_inicio ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getUpcoming(int $userId, int $limit = 5): array {
        if (!$this->db) return [];
        $sql = "SELECT e.*, d.nome AS disciplina_nome, d.cor AS disciplina_cor 
                FROM eventos e 
                LEFT JOIN disciplinas d ON e.disciplina_id = d.id 
                WHERE e.usuario_id = :usuario_id AND e.data_inicio >= NOW() 
                ORDER BY e.data_inicio ASC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':usuario_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id, int $userId): ?array {
        if (!$this->db) return null;
        $sql = "SELECT e.*, d.nome AS disciplina_nome 
                FROM eventos e 
                LEFT JOIN disciplinas d ON e.disciplina_id = d.id 
                WHERE e.id = :id AND e.usuario_id = :usuario_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':usuario_id' => $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): ?int {
        if (!$this->db) return null;
        $sql = "INSERT INTO eventos (usuario_id, disciplina_id, titulo, tipo, data_inicio, data_fim, local, descricao, lembrete_ativo) 
                VALUES (:usuario_id, :disciplina_id, :titulo, :tipo, :data_inicio, :data_fim, :local, :descricao, :lembrete_ativo)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':usuario_id'     => $data['usuario_id'],
            ':disciplina_id'  => !empty($data['disciplina_id']) ? $data['disciplina_id'] : null,
            ':titulo'         => $data['titulo'],
            ':tipo'           => $data['tipo'] ?? 'prova',
            ':data_inicio'    => $data['data_inicio'],
            ':data_fim'       => !empty($data['data_fim']) ? $data['data_fim'] : null,
            ':local'          => $data['local'] ?? null,
            ':descricao'      => $data['descricao'] ?? null,
            ':lembrete_ativo' => isset($data['lembrete_ativo']) ? (int)$data['lembrete_ativo'] : 1
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $userId, array $data): bool {
        if (!$this->db) return false;
        $sql = "UPDATE eventos 
                SET disciplina_id = :disciplina_id, titulo = :titulo, tipo = :tipo, 
                    data_inicio = :data_inicio, data_fim = :data_fim, local = :local, 
                    descricao = :descricao, lembrete_ativo = :lembrete_ativo 
                WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'             => $id,
            ':usuario_id'     => $userId,
            ':disciplina_id'  => !empty($data['disciplina_id']) ? $data['disciplina_id'] : null,
            ':titulo'         => $data['titulo'],
            ':tipo'           => $data['tipo'] ?? 'prova',
            ':data_inicio'    => $data['data_inicio'],
            ':data_fim'       => !empty($data['data_fim']) ? $data['data_fim'] : null,
            ':local'          => $data['local'] ?? null,
            ':descricao'      => $data['descricao'] ?? null,
            ':lembrete_ativo' => isset($data['lembrete_ativo']) ? (int)$data['lembrete_ativo'] : 1
        ]);
    }

    public function delete(int $id, int $userId): bool {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("DELETE FROM eventos WHERE id = :id AND usuario_id = :usuario_id");
        return $stmt->execute([':id' => $id, ':usuario_id' => $userId]);
    }
}
