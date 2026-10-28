<?php
/**
 * SOS-Estudante - Front Controller (Ponto de Entrada Central)
 */

define('APP_ROOT', __DIR__);

// Carregar configurações da aplicação e sessão
require_once APP_ROOT . '/config/app.php';
require_once APP_ROOT . '/config/bd.php';
require_once APP_ROOT . '/routes/web.php';

// Obter a rota atual via query string ?route= ou URI
$route = $_GET['route'] ?? '';
$route = trim($route, '/');

// Obter método HTTP
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Tratar e despachar com captura de exceções globais
try {
    Router::dispatch($route, $method);
} catch (Throwable $e) {
    // Registrar erro no log operacional
    $logFile = APP_ROOT . '/logs/app.log';
    $msg = "[" . date('Y-m-d H:i:s') . "] ERRO FATAL: " . $e->getMessage() . " em " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
    @file_put_contents($logFile, $msg, FILE_APPEND);

    // Se estiver em modo debug, exibir detalhes; caso contrário, página amigável 500
    if (env('APP_DEBUG', true)) {
        $errorMessage = $e->getMessage();
        $errorFile = $e->getFile() . ':' . $e->getLine();
        $errorTrace = $e->getTraceAsString();
    } else {
        $errorMessage = 'Ocorreu um erro interno ao processar sua solicitação.';
        $errorFile = '';
        $errorTrace = '';
    }

    http_response_code(500);
    require APP_ROOT . '/views/errors/500.php';
}
