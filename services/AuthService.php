<?php
/**
 * SOS-Estudante - Service: AuthService
 * Implementa autenticação segura, hash de senhas, gerenciamento de sessões
 * e auditoria de acessos
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/AuditService.php';

class AuthService {
    private User $userModel;
    private AuditService $auditService;

    public function __construct() {
        $this->userModel = new User();
        $this->auditService = new AuditService();
    }

    /**
     * Autentica o usuário com email e senha
     */
    public function login(string $email, string $password): array {
        $email = trim(filter_var($email, FILTER_SANITIZE_EMAIL));

        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Informe o e-mail e a senha.'];
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $this->auditService->log(null, 'LOGIN', 'usuario', null, "Tentativa de login com e-mail inexistente: {$email}", null, null, 'FALHA');
            return ['success' => false, 'message' => 'E-mail ou senha incorretos.'];
        }

        if ($user['status'] !== 'ativo') {
            return ['success' => false, 'message' => 'Sua conta está desativada. Entre em contato com o suporte.'];
        }

        // Validação com password_verify
        if (!password_verify($password, $user['senha'])) {
            $this->auditService->log($user['id'], 'LOGIN', 'usuario', $user['id'], 'Falha na autenticação (senha incorreta)', null, null, 'FALHA');
            return ['success' => false, 'message' => 'E-mail ou senha incorretos.'];
        }

        // Regenerar ID de sessão para prevenir session fixation
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);

        // Armazenar dados na sessão (nunca salvar a senha na sessão)
        unset($user['senha']);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user'] = $user;

        // Registrar auditoria de sucesso
        $this->auditService->log($user['id'], 'LOGIN', 'usuario', $user['id'], 'Login realizado com sucesso', null, ['email' => $user['email']], 'SUCESSO');

        return ['success' => true, 'user' => $user];
    }

    /**
     * Cadastra um novo estudante no sistema
     */
    public function register(array $data): array {
        $nome = trim(strip_tags($data['nome'] ?? ''));
        $email = trim(filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL));
        $password = $data['senha'] ?? '';
        $confirmPassword = $data['confirma_senha'] ?? '';
        $instituicao = trim(strip_tags($data['instituicao'] ?? ''));
        $curso = trim(strip_tags($data['curso'] ?? ''));

        // Validações
        if (empty($nome) || mb_strlen($nome) < 3) {
            return ['success' => false, 'message' => 'O nome deve ter no mínimo 3 caracteres.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Informe um endereço de e-mail válido.'];
        }

        if (mb_strlen($password) < 6) {
            return ['success' => false, 'message' => 'A senha deve conter pelo menos 6 caracteres.'];
        }

        if ($password !== $confirmPassword) {
            return ['success' => false, 'message' => 'A confirmação de senha não confere.'];
        }

        if ($this->userModel->emailExists($email)) {
            return ['success' => false, 'message' => 'Este e-mail já está cadastrado no sistema.'];
        }

        // Hash criptográfico seguro
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userId = $this->userModel->create([
            'nome'        => $nome,
            'email'       => $email,
            'senha'       => $hashedPassword,
            'instituicao' => $instituicao ?: null,
            'curso'       => $curso ?: null,
            'status'      => 'ativo'
        ]);

        if (!$userId) {
            return ['success' => false, 'message' => 'Erro interno ao criar conta. Tente novamente.'];
        }

        // Auditoria
        $this->auditService->log($userId, 'CRIAR', 'usuario', $userId, 'Novo cadastro de estudante criado', null, [
            'nome' => $nome,
            'email' => $email,
            'curso' => $curso
        ], 'SUCESSO');

        return ['success' => true, 'message' => 'Cadastro realizado com sucesso! Você já pode entrar.', 'user_id' => $userId];
    }

    /**
     * Encerra a sessão com segurança
     */
    public function logout(): void {
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId) {
            $this->auditService->log($userId, 'LOGOUT', 'usuario', $userId, 'Usuário encerrou a sessão (Logout)', null, null, 'SUCESSO');
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Limpar variáveis
        $_SESSION = [];

        // Deletar cookie de sessão
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }

    /**
     * Atualiza os dados de perfil
     */
    public function updateProfile(int $userId, array $data): array {
        $nome = trim(strip_tags($data['nome'] ?? ''));
        $email = trim(filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL));
        $instituicao = trim(strip_tags($data['instituicao'] ?? ''));
        $curso = trim(strip_tags($data['curso'] ?? ''));

        if (empty($nome) || mb_strlen($nome) < 3) {
            return ['success' => false, 'message' => 'O nome deve ter no mínimo 3 caracteres.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Informe um endereço de e-mail válido.'];
        }

        if ($this->userModel->emailExists($email, $userId)) {
            return ['success' => false, 'message' => 'Este e-mail já está sendo utilizado por outro usuário.'];
        }

        $currentUser = $this->userModel->findById($userId);
        if (!$currentUser) {
            return ['success' => false, 'message' => 'Usuário não encontrado.'];
        }

        $updated = $this->userModel->updateProfile($userId, [
            'nome'        => $nome,
            'email'       => $email,
            'instituicao' => $instituicao ?: null,
            'curso'       => $curso ?: null
        ]);

        if (!$updated) {
            return ['success' => false, 'message' => 'Não foi possível atualizar o perfil.'];
        }

        // Atualizar dados na sessão
        $_SESSION['user']['nome'] = $nome;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['instituicao'] = $instituicao;
        $_SESSION['user']['curso'] = $curso;

        // Auditoria
        $this->auditService->log(
            $userId, 
            'ATUALIZAR', 
            'usuario', 
            $userId, 
            'Atualizou informações do perfil',
            $currentUser,
            ['nome' => $nome, 'email' => $email, 'instituicao' => $instituicao, 'curso' => $curso],
            'SUCESSO'
        );

        return ['success' => true, 'message' => 'Perfil atualizado com sucesso!'];
    }

    /**
     * Altera a senha do usuário
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword, string $confirmPassword): array {
        if (empty($currentPassword) || empty($newPassword)) {
            return ['success' => false, 'message' => 'Preencha a senha atual e a nova senha.'];
        }

        if (mb_strlen($newPassword) < 6) {
            return ['success' => false, 'message' => 'A nova senha deve ter no mínimo 6 caracteres.'];
        }

        if ($newPassword !== $confirmPassword) {
            return ['success' => false, 'message' => 'A confirmação da nova senha não confere.'];
        }

        $user = $this->userModel->findWithPasswordById($userId);
        if (!$user) {
            return ['success' => false, 'message' => 'Usuário não encontrado.'];
        }

        if (!password_verify($currentPassword, $user['senha'])) {
            $this->auditService->log($userId, 'ATUALIZAR', 'usuario', $userId, 'Falha ao alterar senha (senha atual inválida)', null, null, 'FALHA');
            return ['success' => false, 'message' => 'A senha atual informada está incorreta.'];
        }

        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updated = $this->userModel->updatePassword($userId, $newHashedPassword);

        if (!$updated) {
            return ['success' => false, 'message' => 'Erro ao salvar nova senha. Tente novamente.'];
        }

        // Auditoria (sem armazenar senhas!)
        $this->auditService->log($userId, 'ATUALIZAR', 'usuario', $userId, 'Senha de acesso alterada com sucesso', null, null, 'SUCESSO');

        return ['success' => true, 'message' => 'Senha alterada com sucesso!'];
    }
}
