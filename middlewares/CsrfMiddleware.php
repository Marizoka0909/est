<?php
/**
 * SOS-Estudante - Middleware de Proteção CSRF
 * Atende às diretrizes de segurança da seção 5 dos requisitos:
 * - Implementar proteção contra CSRF nas operações que alterem dados
 */

class CsrfMiddleware {
    private const SESSION_KEY = '_csrf_token';

    /**
     * Gera ou retorna o token CSRF atual da sessão
     */
    public static function getToken(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * Retorna o campo HTML oculto com o token
     */
    public static function field(): string {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Valida o token CSRF recebido em requisições POST
     */
    public static function validate(): bool {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true;
        }

        $sessionToken = $_SESSION[self::SESSION_KEY] ?? null;
        $receivedToken = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

        if (!$sessionToken || !$receivedToken || !hash_equals($sessionToken, $receivedToken)) {
            return false;
        }

        return true;
    }

    /**
     * Regenera o token CSRF (após login ou logout)
     */
    public static function regenerate(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        return $_SESSION[self::SESSION_KEY];
    }
}
