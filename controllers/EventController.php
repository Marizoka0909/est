<?php
/**
 * SOS-Estudante - Controller: EventController
 * Gerencia agenda acadêmica, provas e compromissos
 */

require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
require_once __DIR__ . '/../middlewares/CsrfMiddleware.php';
require_once __DIR__ . '/../services/EventService.php';
require_once __DIR__ . '/../services/SubjectService.php';

class EventController {
    private EventService $eventService;
    private SubjectService $subjectService;

    public function __construct() {
        $this->eventService = new EventService();
        $this->subjectService = new SubjectService();
    }

    public function index(): void {
        AuthMiddleware::requireAuth();
        $userId = AuthMiddleware::id();

        $tipoFilter = $_GET['tipo'] ?? 'todos';

        $events = $this->eventService->getAll($userId, $tipoFilter);
        $subjects = $this->subjectService->getAll($userId);

        $flash = AuthMiddleware::getFlash();
        $user = AuthMiddleware::user();

        require __DIR__ . '/../views/events/index.php';
    }

    public function store(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=events');
            exit;
        }

        $userId = AuthMiddleware::id();
        $result = $this->eventService->create($userId, $_POST);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=events');
        exit;
    }

    public function update(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=events');
            exit;
        }

        $userId = AuthMiddleware::id();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->eventService->update($id, $userId, $_POST);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=events');
        exit;
    }

    public function delete(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=events');
            exit;
        }

        $userId = AuthMiddleware::id();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->eventService->delete($id, $userId);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=events');
        exit;
    }
}
