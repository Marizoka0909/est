<?php
/**
 * SOS-Estudante - Suíte de Testes Automatizados
 * Valida os requisitos essenciais de segurança, regras de negócio e persistência.
 * Pode ser executado via CLI (php tests/test_db_and_services.php) ou navegador.
 */

define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/config/app.php';
require_once APP_ROOT . '/config/bd.php';
require_once APP_ROOT . '/middlewares/CsrfMiddleware.php';
require_once APP_ROOT . '/models/AuditLog.php';
require_once APP_ROOT . '/services/AuthService.php';

$isCli = (php_sapi_name() === 'cli');

function assertTest(string $description, bool $condition): void {
    global $isCli;
    if ($condition) {
        if ($isCli) {
            echo " [OK] " . $description . PHP_EOL;
        } else {
            echo "<div style='color: #059669; margin: 4px 0; font-family: monospace;'>✔ <strong>[OK]</strong> " . htmlspecialchars($description) . "</div>";
        }
    } else {
        if ($isCli) {
            echo " [FALHA] " . $description . PHP_EOL;
        } else {
            echo "<div style='color: #dc2626; margin: 4px 0; font-family: monospace;'>✘ <strong>[FALHA]</strong> " . htmlspecialchars($description) . "</div>";
        }
    }
}

if (!$isCli) {
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Testes SOS-Estudante</title></head><body style='padding: 24px; font-family: sans-serif; background: #f8fafc;'>";
    echo "<h2 style='color: #1e293b;'>SOS-Estudante - Execução dos Testes Automatizados</h2><hr style='border:0; border-top:1px solid #e2e8f0; margin-bottom: 20px;'>";
}

echo $isCli ? "=== 1. Testes de Configuração e Ambiente ===" . PHP_EOL : "<h3>1. Testes de Configuração e Ambiente</h3>";
assertTest("Constante APP_ROOT definida corretamente", defined('APP_ROOT') && file_exists(APP_ROOT));
assertTest("Constante APP_NAME configurada", defined('APP_NAME') && strlen(APP_NAME) > 0);
assertTest("Sessão nativa do PHP inicializada", session_status() === PHP_SESSION_ACTIVE);

echo $isCli ? PHP_EOL . "=== 2. Testes de Segurança e Criptografia de Senhas ===" . PHP_EOL : "<h3>2. Testes de Segurança e Criptografia de Senhas</h3>";
$senhaOriginal = "SenhaUltraSecreta123!";
$hash = password_hash($senhaOriginal, PASSWORD_DEFAULT);
assertTest("Geração de hash de senha com password_hash", strlen($hash) > 20);
assertTest("Validação de senha correta com password_verify", password_verify($senhaOriginal, $hash) === true);
assertTest("Rejeição de senha incorreta com password_verify", password_verify("SenhaErrada", $hash) === false);

echo $isCli ? PHP_EOL . "=== 3. Testes de Proteção CSRF ===" . PHP_EOL : "<h3>3. Testes de Proteção CSRF</h3>";
$token = CsrfMiddleware::getToken();
assertTest("Geração de token CSRF não vazio", !empty($token) && strlen($token) === 64);
$campoHtml = CsrfMiddleware::field();
assertTest("Geração do campo oculto HTML CSRF", strpos($campoHtml, 'type="hidden"') !== false && strpos($campoHtml, $token) !== false);

echo $isCli ? PHP_EOL . "=== 4. Testes de Sanitização de Auditoria (Sem Senhas) ===" . PHP_EOL : "<h3>4. Testes de Sanitização de Auditoria (Sem Senhas)</h3>";
$dadosSensiveis = [
    'nome' => 'Estudante Teste',
    'email' => 'teste@exemplo.com',
    'senha' => 'Segredo123',
    'csrf_token' => 'tokensecreto456',
    'nova_senha' => 'NovaSenha789'
];
$dadosHigienizados = AuditLog::sanitizeData($dadosSensiveis);
assertTest("Preservação de campos seguros (nome, email)", $dadosHigienizados['nome'] === 'Estudante Teste' && $dadosHigienizados['email'] === 'teste@exemplo.com');
assertTest("Ocultação obrigatória de campo 'senha'", $dadosHigienizados['senha'] === '[PROTEGIDO]');
assertTest("Ocultação obrigatória de campo 'csrf_token'", $dadosHigienizados['csrf_token'] === '[PROTEGIDO]');
assertTest("Ocultação obrigatória de campo 'nova_senha'", $dadosHigienizados['nova_senha'] === '[PROTEGIDO]');

echo $isCli ? PHP_EOL . "=== 5. Testes de Conexão com o Banco de Dados (PDO) ===" . PHP_EOL : "<h3>5. Testes de Conexão com o Banco de Dados (PDO)</h3>";
$db = getDB();
if ($db instanceof PDO) {
    assertTest("Conexão PDO estabelecida com sucesso", true);
    assertTest("Modo de tratamento de erros configurado para ERRMODE_EXCEPTION", $db->getAttribute(PDO::ATTR_ERRMODE) === PDO::ERRMODE_EXCEPTION);
    assertTest("Fetch mode configurado para FETCH_ASSOC", $db->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE) === PDO::FETCH_ASSOC);
} else {
    assertTest("Conexão PDO com MySQL (Aviso: Servidor MySQL local não está ativo no momento)", false);
    if ($isCli) {
        echo "   [INFO] Erro reportado pelo PDO: " . Database::getLastError() . PHP_EOL;
        echo "   [INFO] Para conectar, inicie o MySQL (XAMPP/WAMP/Docker) e execute database/schema.sql." . PHP_EOL;
    } else {
        echo "<div style='color: #d97706; font-size: 13px; margin: 6px 0 16px;'><em>Aviso: Para habilitar a conexão, inicie o serviço MySQL no XAMPP/WAMP e importe o arquivo <code>database/schema.sql</code>.</em></div>";
    }
}

echo $isCli ? PHP_EOL . "Testes finalizados." . PHP_EOL : "<br><p style='font-size:13px; color:#64748b;'>Execução concluída.</p></body></html>";
