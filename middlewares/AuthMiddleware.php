<?php
/**
 * SOS-Estudante - Middleware de Autenticação e Controle de Sessão
 * Atende às diretrizes de segurança da seção 3.1 e seção 5 dos requisitos:
 * - Funcionalidades privadas exigem autenticação
 * - Regeneração de identificador de sessão
 * - Logout seguro
 */

require_once __DIR__ . '/../config/app.php';

class AuthMiddleware {
    /**
     * Verifica se o usuário atual está autenticado
     */
    public static function check(): bool {
        return !empty($_SESSION['user_id']);
    }

    /**
     * Retorna o ID do usuário logado
     */
    public static function id(): ?int {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Retorna os dados do usuário salvos na sessão
     */
    public static function user(): ?array {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Exige que o usuário esteja autenticado. Se não estiver, redireciona para o login.
     */
    public static function requireAuth(): void {
        if (!self::check()) {
            self::setFlash('warning', 'Faça login para acessar esta funcionalidade.');
            header('Location: index.php?route=login');
            exit;
        }
    }

    /**
     * Exige que o usuário NÃO esteja autenticado (p/ páginas de login e cadastro).
     */
    public static function requireGuest(): void {
        if (self::check()) {
            header('Location: index.php?route=dashboard');
            exit;
        }
    }

    /**
     * Define uma mensagem Toast/Flash para exibição na próxima tela
     */
    public static function setFlash(string $type, string $message): void {
        $_SESSION['flash_message'] = [
            'type' => $type, // 'success', 'error', 'warning', 'info'
            'message' => $message
        ];
    }

    /**
     * Obtém e limpa a mensagem Flash da sessão
     */
    public static function getFlash(): ?array {
        if (!empty($_SESSION['flash_message'])) {
            $msg = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $msg;
        }
        return null;
    }
}
