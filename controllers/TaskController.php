<?php
/**
 * SOS-Estudante - Controller: TaskController
 * Gerencia tarefas escolares, prioridades, status e filtros
 */

require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
require_once __DIR__ . '/../middlewares/CsrfMiddleware.php';
require_once __DIR__ . '/../services/TaskService.php';
require_once __DIR__ . '/../services/SubjectService.php';

class TaskController {
    private TaskService $taskService;
    private SubjectService $subjectService;

    public function __construct() {
        $this->taskService = new TaskService();
        $this->subjectService = new SubjectService();
    }

    public function index(): void {
        AuthMiddleware::requireAuth();
        $userId = AuthMiddleware::id();

        $statusFilter = $_GET['status'] ?? 'todos';
        $priorityFilter = $_GET['priority'] ?? 'todas';
        $subjectFilter = !empty($_GET['subject_id']) ? (int) $_GET['subject_id'] : null;

        $tasks = $this->taskService->getAll($userId, $statusFilter, $priorityFilter, $subjectFilter);
        $subjects = $this->subjectService->getAll($userId);
        $stats = $this->taskService->getStats($userId);

        $flash = AuthMiddleware::getFlash();
        $user = AuthMiddleware::user();

        require __DIR__ . '/../views/tasks/index.php';
    }

    public function store(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=tasks');
            exit;
        }

        $userId = AuthMiddleware::id();
        $result = $this->taskService->create($userId, $_POST);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=tasks');
        exit;
    }

    public function update(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=tasks');
            exit;
        }

        $userId = AuthMiddleware::id();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->taskService->update($id, $userId, $_POST);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=tasks');
        exit;
    }

    public function toggle(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança inválido.');
            header('Location: index.php?route=tasks');
            exit;
        }

        $userId = AuthMiddleware::id();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->taskService->toggleStatus($id, $userId);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=tasks');
        exit;
    }

    public function delete(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=tasks');
            exit;
        }

        $userId = AuthMiddleware::id();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->taskService->delete($id, $userId);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=tasks');
        exit;
    }
}
