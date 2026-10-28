<?php
/**
 * SOS-Estudante - Controller: SubjectController
 * Gerencia matérias e disciplinas acadêmicas
 */

require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
require_once __DIR__ . '/../middlewares/CsrfMiddleware.php';
require_once __DIR__ . '/../services/SubjectService.php';

class SubjectController {
    private SubjectService $subjectService;

    public function __construct() {
        $this->subjectService = new SubjectService();
    }

    public function index(): void {
        AuthMiddleware::requireAuth();
        $userId = AuthMiddleware::id();

        $subjects = $this->subjectService->getAll($userId);
        $flash = AuthMiddleware::getFlash();
        $user = AuthMiddleware::user();

        require __DIR__ . '/../views/subjects/index.php';
    }

    public function store(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=subjects');
            exit;
        }

        $userId = AuthMiddleware::id();
        $result = $this->subjectService->create($userId, $_POST);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=subjects');
        exit;
    }

    public function update(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=subjects');
            exit;
        }

        $userId = AuthMiddleware::id();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->subjectService->update($id, $userId, $_POST);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=subjects');
        exit;
    }

    public function delete(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=subjects');
            exit;
        }

        $userId = AuthMiddleware::id();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->subjectService->delete($id, $userId);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=subjects');
        exit;
    }
}
