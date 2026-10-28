<?php
/**
 * SOS-Estudante - Controller: AuthController
 * Gerencia login, registro, logout e autenticação de usuários
 */

require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
require_once __DIR__ . '/../middlewares/CsrfMiddleware.php';

class AuthController {
    private AuthService $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function showLogin(): void {
        AuthMiddleware::requireGuest();
        $flash = AuthMiddleware::getFlash();
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void {
        AuthMiddleware::requireGuest();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $result = $this->authService->login($email, $senha);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', 'Bem-vindo de volta ao SOS Estudante, ' . htmlspecialchars($result['user']['nome']) . '!');
            header('Location: index.php?route=dashboard');
            exit;
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
            header('Location: index.php?route=login');
            exit;
        }
    }

    public function showRegister(): void {
        AuthMiddleware::requireGuest();
        $flash = AuthMiddleware::getFlash();
        require __DIR__ . '/../views/auth/register.php';
    }

    public function register(): void {
        AuthMiddleware::requireGuest();

        if (!CsrfMiddleware::validate()) {
            AuthMiddleware::setFlash('error', 'Token de segurança expirado. Tente novamente.');
            header('Location: index.php?route=register');
            exit;
        }

        $result = $this->authService->register($_POST);

        if ($result['success']) {
            AuthMiddleware::setFlash('success', $result['message']);
            header('Location: index.php?route=login');
            exit;
        } else {
            AuthMiddleware::setFlash('error', $result['message']);
            header('Location: index.php?route=register');
            exit;
        }
    }

    public function logout(): void {
        $this->authService->logout();
        AuthMiddleware::setFlash('info', 'Você saiu do sistema com segurança. Até breve!');
        header('Location: index.php?route=login');
        exit;
    }
}
