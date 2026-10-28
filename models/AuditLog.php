<?php
/**
 * SOS-Estudante - Model: AuditLog (Histórico e Auditoria de Operações)
 * Atende estritamente à seção 3.3 dos requisitos:
 * - Registro de operações (CRIAR, ATUALIZAR, EXCLUIR, LOGIN, LOGOUT)
 * - Valores anteriores e posteriores higienizados (sem senhas ou tokens)
 * - Rastreamento por usuário, IP e data/hora
 * - Imutabilidade (somente inserção e leitura)
 */

require_once __DIR__ . '/../config/bd.php';

class AuditLog {
    private ?PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Remove campos sensíveis antes de salvar no log
     */
    public static function sanitizeData(?array $data): ?array {
        if ($data === null) return null;
        $sensitiveKeys = ['senha', 'password', 'token', 'csrf_token', '_csrf_token', 'confirma_senha', 'senha_atual', 'nova_senha'];
        foreach ($sensitiveKeys as $key) {
            if (array_key_exists($key, $data)) {
                $data[$key] = '[PROTEGIDO]';
            }
        }
        return $data;
    }

    /**
     * Registra uma operação de auditoria
     */
    public function log(
        ?int $userId,
        string $operacao,
        string $recurso,
        ?int $recursoId,
        string $descricao,
        ?array $valoresAnteriores = null,
        ?array $valoresPosteriores = null,
        string $statusResultado = 'SUCESSO'
    ): bool {
        if (!$this->db) return false;

        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido', 0, 255);

            $valAntJson = $valoresAnteriores ? json_encode(self::sanitizeData($valoresAnteriores), JSON_UNESCAPED_UNICODE) : null;
            $valPostJson = $valoresPosteriores ? json_encode(self::sanitizeData($valoresPosteriores), JSON_UNESCAPED_UNICODE) : null;

            $sql = "INSERT INTO auditoria (usuario_id, operacao, recurso, recurso_id, descricao, valores_anteriores, valores_posteriores, status_resultado, ip_address, user_agent) 
                    VALUES (:usuario_id, :operacao, :recurso, :recurso_id, :descricao, :valores_anteriores, :valores_posteriores, :status_resultado, :ip_address, :user_agent)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':usuario_id'          => $userId,
                ':operacao'            => strtoupper($operacao),
                ':recurso'             => strtolower($recurso),
                ':recurso_id'          => $recursoId,
                ':descricao'           => $descricao,
                ':valores_anteriores'  => $valAntJson,
                ':valores_posteriores' => $valPostJson,
                ':status_resultado'    => $statusResultado,
                ':ip_address'          => $ip,
                ':user_agent'          => $userAgent
            ]);
        } catch (Exception $e) {
            // Em caso de falha no log, salvar em arquivo para não interromper a aplicação
            $logFile = APP_ROOT . '/logs/app.log';
            $msg = "[" . date('Y-m-d H:i:s') . "] ERRO DE AUDITORIA: " . $e->getMessage() . PHP_EOL;
            @file_put_contents($logFile, $msg, FILE_APPEND);
            return false;
        }
    }

    /**
     * Consulta o histórico do usuário com filtros
     */
    public function allByUserId(int $userId, array $filters = [], int $limit = 50, int $offset = 0): array {
        if (!$this->db) return [];

        $sql = "SELECT a.*, u.nome AS usuario_nome, u.email AS usuario_email 
                FROM auditoria a 
                LEFT JOIN usuarios u ON a.usuario_id = u.id 
                WHERE a.usuario_id = :usuario_id";
        $params = [':usuario_id' => $userId];

        if (!empty($filters['operacao']) && $filters['operacao'] !== 'todas') {
            $sql .= " AND a.operacao = :operacao";
            $params[':operacao'] = strtoupper($filters['operacao']);
        }

        if (!empty($filters['recurso']) && $filters['recurso'] !== 'todos') {
            $sql .= " AND a.recurso = :recurso";
            $params[':recurso'] = strtolower($filters['recurso']);
        }

        if (!empty($filters['data_inicio'])) {
            $sql .= " AND a.created_at >= :data_inicio";
            $params[':data_inicio'] = $filters['data_inicio'] . " 00:00:00";
        }

        if (!empty($filters['data_fim'])) {
            $sql .= " AND a.created_at <= :data_fim";
            $params[':data_fim'] = $filters['data_fim'] . " 23:59:59";
        }

        $sql .= " ORDER BY a.created_at DESC, a.id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Total de registros para paginação
     */
    public function countByUserId(int $userId, array $filters = []): int {
        if (!$this->db) return 0;

        $sql = "SELECT COUNT(*) FROM auditoria WHERE usuario_id = :usuario_id";
        $params = [':usuario_id' => $userId];

        if (!empty($filters['operacao']) && $filters['operacao'] !== 'todas') {
            $sql .= " AND operacao = :operacao";
            $params[':operacao'] = strtoupper($filters['operacao']);
        }

        if (!empty($filters['recurso']) && $filters['recurso'] !== 'todos') {
            $sql .= " AND recurso = :recurso";
            $params[':recurso'] = strtolower($filters['recurso']);
        }

        if (!empty($filters['data_inicio'])) {
            $sql .= " AND created_at >= :data_inicio";
            $params[':data_inicio'] = $filters['data_inicio'] . " 00:00:00";
        }

        if (!empty($filters['data_fim'])) {
            $sql .= " AND created_at <= :data_fim";
            $params[':data_fim'] = $filters['data_fim'] . " 23:59:59";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
}
