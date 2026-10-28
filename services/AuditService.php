<?php
/**
 * SOS-Estudante - Service: AuditService
 * Centraliza e padroniza os registros de auditoria do sistema
 */

require_once __DIR__ . '/../models/AuditLog.php';

class AuditService {
    private AuditLog $auditModel;

    public function __construct() {
        $this->auditModel = new AuditLog();
    }

    /**
     * Registra evento no log de auditoria
     */
    public function log(
        ?int $userId,
        string $operacao,
        string $recurso,
        ?int $recursoId,
        string $descricao,
        ?array $valoresAnteriores = null,
        ?array $valoresPosteriores = null,
        string $status = 'SUCESSO'
    ): bool {
        return $this->auditModel->log(
            $userId,
            $operacao,
            $recurso,
            $recursoId,
            $descricao,
            $valoresAnteriores,
            $valoresPosteriores,
            $status
        );
    }

    /**
     * Busca histórico do usuário com filtros e paginação
     */
    public function getHistory(int $userId, array $filters = [], int $page = 1, int $perPage = 20): array {
        $offset = ($page - 1) * $perPage;
        $items = $this->auditModel->allByUserId($userId, $filters, $perPage, $offset);
        $total = $this->auditModel->countByUserId($userId, $filters);
        $totalPages = (int) ceil($total / $perPage);

        return [
            'items'       => $items,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => max(1, $totalPages)
        ];
    }
}
