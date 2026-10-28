<?php
/**
 * SOS-Estudante - Controller: DashboardController
 * Centraliza estatísticas, métricas de desempenho e visão geral da rotina
 */

require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
require_once __DIR__ . '/../services/TaskService.php';
require_once __DIR__ . '/../services/SubjectService.php';
require_once __DIR__ . '/../services/EventService.php';
require_once __DIR__ . '/../services/GradeService.php';
require_once __DIR__ . '/../services/AuditService.php';

class DashboardController {
    private TaskService $taskService;
    private SubjectService $subjectService;
    private EventService $eventService;
    private GradeService $gradeService;
    private AuditService $auditService;

    public function __construct() {
        $this->taskService = new TaskService();
        $this->subjectService = new SubjectService();
        $this->eventService = new EventService();
        $this->gradeService = new GradeService();
        $this->auditService = new AuditService();
    }

    public function index(): void {
        AuthMiddleware::requireAuth();
        $userId = AuthMiddleware::id();

        $stats = $this->taskService->getStats($userId);
        $upcomingTasks = $this->taskService->getUpcoming($userId, 4);
        $upcomingEvents = $this->eventService->getUpcoming($userId, 4);
        $subjects = $this->subjectService->getAll($userId);
        $overallAverage = $this->gradeService->getOverallAverage($userId);
        $recentHistory = $this->auditService->getHistory($userId, [], 1, 5);

        $flash = AuthMiddleware::getFlash();
        $user = AuthMiddleware::user();

        require __DIR__ . '/../views/dashboard/index.php';
    }
}
