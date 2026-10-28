<?php
/**
 * SOS-Estudante - Controller: ProfileController
 * Gerencia visualização e atualização de perfil e senha do estudante
 */

require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
require_once __DIR__ . '/../middlewares/CsrfMiddleware.php';
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../models/User.php';

class ProfileController {
    private AuthService $authService;
    private User $userModel;

    public function __construct() {
        $this->authService = new AuthService();
        $this->userModel = new User();
    }

    public function index(): void {
        AuthMiddleware::requireAuth();
        $userId = AuthMiddleware::id();

        $userProfile = $this->userModel->findById($userId);
        $flash = AuthMiddleware::getFlash();
        $user = AuthMiddleware::user();

        require __DIR__ . '/../views/profile/index.php';
    }

    public function update(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=profile');
            exit;
        }

        $userId = AuthMiddleware::id();
        $result = $this->authService->updateProfile($userId, $_POST);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=profile');
        exit;
    }

    public function updatePassword(): void {
        AuthMiddleware::requireAuth();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=profile');
            exit;
        }

        $userId = AuthMiddleware::id();
        $currentPassword = $_POST['senha_atual'] ?? '';
        $newPassword = $_POST['nova_senha'] ?? '';
        $confirmPassword = $_POST['confirma_nova_senha'] ?? '';

        $result = $this->authService->changePassword($userId, $currentPassword, $newPassword, $confirmPassword);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
        }

        header('Location: index.php?route=profile');
        exit;
    }
}
