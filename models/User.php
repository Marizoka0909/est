<?php
/**
 * SOS-Estudante - Model: User
 * Acesso a dados de usuários com PDO e Prepared Statements
 */

require_once __DIR__ . '/../config/bd.php';

class User {
    private ?PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function findByEmail(string $email): ?array {
        if (!$this->db) return null;
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array {
        if (!$this->db) return null;
        $stmt = $this->db->prepare("SELECT id, nome, email, instituicao, curso, foto, status, created_at, updated_at FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findWithPasswordById(int $id): ?array {
        if (!$this->db) return null;
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function emailExists(string $email, ?int $excludeId = null): bool {
        if (!$this->db) return false;
        $sql = "SELECT id FROM usuarios WHERE email = :email";
        $params = [':email' => $email];

        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    public function create(array $data): ?int {
        if (!$this->db) return null;
        $sql = "INSERT INTO usuarios (nome, email, senha, instituicao, curso, status) 
                VALUES (:nome, :email, :senha, :instituicao, :curso, :status)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nome'        => $data['nome'],
            ':email'       => $data['email'],
            ':senha'       => $data['senha'],
            ':instituicao' => $data['instituicao'] ?? null,
            ':curso'       => $data['curso'] ?? null,
            ':status'      => $data['status'] ?? 'ativo'
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function updateProfile(int $id, array $data): bool {
        if (!$this->db) return false;
        $sql = "UPDATE usuarios 
                SET nome = :nome, email = :email, instituicao = :instituicao, curso = :curso 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':nome'        => $data['nome'],
            ':email'       => $data['email'],
            ':instituicao' => $data['instituicao'] ?? null,
            ':curso'       => $data['curso'] ?? null
        ]);
    }

    public function updatePassword(int $id, string $hashedPassword): bool {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("UPDATE usuarios SET senha = :senha WHERE id = :id");
        return $stmt->execute([
            ':id'    => $id,
            ':senha' => $hashedPassword
        ]);
    }
}
