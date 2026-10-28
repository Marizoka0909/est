<?php
/**
 * SOS-Estudante - Configurações Gerais da Aplicação
 */

// Evitar execução direta se não for via aplicação
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Carregar arquivo .env simples (sem dependências externas)
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Remover aspas
            if ((strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
                (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)) {
                $value = substr($value, 1, -1);
            }
            if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Carregar variáveis do .env se existir
loadEnv(APP_ROOT . '/.env');

// Função auxiliar para obter variável de ambiente com valor default
function env($key, $default = null) {
    $val = getenv($key);
    if ($val === false) {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
    if ($val === 'true') return true;
    if ($val === 'false') return false;
    return $val;
}

// Fuso horário
$timezone = env('APP_TIMEZONE', 'America/Sao_Paulo');
date_default_timezone_set($timezone);

// Configuração de exibição de erros conforme ambiente
$debug = env('APP_DEBUG', true);
if ($debug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Configuração de Sessão Segura
if (session_status() === PHP_SESSION_NONE) {
    $lifetime = (int) env('SESSION_LIFETIME', 7200);
    $secureCookie = (bool) env('SESSION_SECURE_COOKIE', false);
    
    // Cookie params
    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path' => '/',
        'domain' => '',
        'secure' => $secureCookie,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    session_start();
}

// Constantes Globais
if (!defined('APP_NAME')) define('APP_NAME', env('APP_NAME', 'SOS Estudante'));
if (!defined('APP_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    $base = rtrim($protocol . $host . $scriptDir, '/\\');
    define('APP_URL', env('APP_URL', $base));
}
