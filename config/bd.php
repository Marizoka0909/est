<?php
/**
 * SOS-Estudante - Configuração e Conexão com o Banco de Dados (PDO)
 * Atende às diretrizes de segurança (seção 4.1 dos requisitos):
 * - PDO com Prepared Statements
 * - Prevenção contra SQL Injection
 * - Ocultação de credenciais e erros amigáveis
 */

require_once __DIR__ . '/app.php';

class Database {
    private static ?PDO $instance = null;
    private static ?string $lastError = null;

    /**
     * Retorna a instância única do PDO (Singleton)
     */
    public static function getConnection(): ?PDO {
        if (self::$instance === null) {
            $host = env('DB_HOST', '127.0.0.1');
            $port = env('DB_PORT', '3306');
            $dbname = env('DB_NAME', 'sos_estudante');
            $user = env('DB_USER', 'root');
            $pass = env('DB_PASS', '');
            $charset = env('DB_CHARSET', 'utf8mb4');

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset} COLLATE utf8mb4_unicode_ci"
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                self::$lastError = $e->getMessage();
                // Registrar no log sem expor senha
                $logFile = APP_ROOT . '/logs/app.log';
                $logMessage = "[" . date('Y-m-d H:i:s') . "] ERRO DE CONEXÃO BD: " . $e->getMessage() . PHP_EOL;
                @file_put_contents($logFile, $logMessage, FILE_APPEND);
                return null;
            }
        }

        return self::$instance;
    }

    /**
     * Retorna o último erro de conexão, se houver
     */
    public static function getLastError(): ?string {
        return self::$lastError;
    }

    /**
     * Executa comandos DDL / SQL para inicializar ou testar conexão
     */
    public static function isConnected(): bool {
        return self::getConnection() !== null;
    }
}

/**
 * Função helper para acesso rápido ao PDO
 */
function getDB(): ?PDO {
    return Database::getConnection();
}
