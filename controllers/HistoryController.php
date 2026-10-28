<?php
/**
 * SOS-Estudante - Controller: HistoryController
 * Gerencia a consulta ao histórico de auditoria do estudante
 * Atende à seção 3.3 dos requisitos
 */

require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
require_once __DIR__ . '/../services/AuditService.php';

class HistoryController {
    private AuditService $auditService;

    public function __construct() {
        $this->auditService = new AuditService();
    }

    public function index(): void {
        AuthMiddleware::requireAuth();
        $userId = AuthMiddleware::id();

        $page = !empty($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $filters = [
            'operacao'    => $_GET['operacao'] ?? '',
            'recurso'     => $_GET['recurso'] ?? '',
            'data_inicio' => $_GET['data_inicio'] ?? '',
            'data_fim'    => $_GET['data_fim'] ?? ''
        ];

        $historyData = $this->auditService->getHistory($userId, $filters, $page, 15);

        $flash = AuthMiddleware::getFlash();
        $user = AuthMiddleware::user();

        require __DIR__ . '/../views/history/index.php';
    }
}
