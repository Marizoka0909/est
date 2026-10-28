<?php
/**
 * SOS-Estudante - Controller: GradeController
 * Gerencia boletim escolar, notas, avaliações e aprovação
 */

require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
require_once __DIR__ . '/../middlewares/CsrfMiddleware.php';
require_once __DIR__ . '/../services/GradeService.php';
require_once __DIR__ . '/../services/SubjectService.php';

class GradeController {
    private GradeService $gradeService;
    private SubjectService $subjectService;

    public function __construct() {
        $this->gradeService = new GradeService();
        $this->subjectService = new SubjectService();
    }

    public function index(): void {
        AuthMiddleware::requireAuth();
        $userId = AuthMiddleware::id();

        $grades = $this->gradeService->getAll($userId);
        $subjects = $this->subjectService->getAll($userId);
        $overallAverage = $this->gradeService->getOverallAverage($userId);

        $flash = AuthMiddleware::getFlash();
        $user = AuthMiddleware::user();

        require __DIR__ . '/../views/grades/index.php';
    }

    public function store(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=grades');
            exit;
        }

        $userId = AuthMiddleware::id();
        $result = $this->gradeService->create($userId, $_POST);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=grades');
        exit;
    }

    public function update(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=grades');
            exit;
        }

        $userId = AuthMiddleware::id();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->gradeService->update($id, $userId, $_POST);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=grades');
        exit;
    }

    public function delete(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=grades');
            exit;
        }

        $userId = AuthMiddleware::id();
        $id = (int) ($_POST['id'] ?? 0);
        $result = $this->gradeService->delete($id, $userId);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=grades');
        exit;
    }
}
