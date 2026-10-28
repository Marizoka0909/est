<?php
/**
 * SOS-Estudante - Service: TaskService
 * Regras de negócio de tarefas, prioridades, prazos e auditoria
 */

require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/AuditService.php';

class TaskService {
    private Task $taskModel;
    private AuditService $auditService;

    public function __construct() {
        $this->taskModel = new Task();
        $this->auditService = new AuditService();
    }

    public function getAll(int $userId, ?string $status = null, ?string $priority = null, ?int $subjectId = null): array {
        return $this->taskModel->allByUserId($userId, $status, $priority, $subjectId);
    }

    public function getById(int $id, int $userId): ?array {
        return $this->taskModel->findById($id, $userId);
    }

    public function getUpcoming(int $userId, int $limit = 5): array {
        return $this->taskModel->getUpcoming($userId, $limit);
    }

    public function getStats(int $userId): array {
        return $this->taskModel->countByStatus($userId);
    }

    public function create(int $userId, array $data): array {
        $titulo = trim(strip_tags($data['titulo'] ?? ''));
        $descricao = trim(strip_tags($data['descricao'] ?? ''));
        $disciplinaId = !empty($data['disciplina_id']) ? (int) $data['disciplina_id'] : null;
        $dataEntrega = !empty($data['data_entrega']) ? $data['data_entrega'] : null;
        $prioridade = in_array($data['prioridade'] ?? '', ['baixa', 'media', 'alta', 'urgente']) ? $data['prioridade'] : 'media';
        $status = in_array($data['status'] ?? '', ['pendente', 'em_andamento', 'concluida']) ? $data['status'] : 'pendente';

        if (empty($titulo)) {
            return ['success' => false, 'message' => 'O título da tarefa é obrigatório.'];
        }

        $cleanData = [
            'usuario_id'    => $userId,
            'disciplina_id' => $disciplinaId,
            'titulo'        => $titulo,
            'descricao'     => $descricao ?: null,
            'data_entrega'  => $dataEntrega,
            'prioridade'    => $prioridade,
            'status'        => $status
        ];

        $id = $this->taskModel->create($cleanData);

        if (!$id) {
            return ['success' => false, 'message' => 'Falha ao registrar a tarefa no banco de dados.'];
        }

        $this->auditService->log($userId, 'CRIAR', 'tarefa', $id, "Criou a tarefa '{$titulo}'", null, $cleanData, 'SUCESSO');

        return ['success' => true, 'message' => 'Tarefa cadastrada com sucesso!', 'id' => $id];
    }

    public function update(int $id, int $userId, array $data): array {
        $existing = $this->taskModel->findById($id, $userId);
        if (!$existing) {
            return ['success' => false, 'message' => 'Tarefa não encontrada ou acesso não autorizado.'];
        }

        $titulo = trim(strip_tags($data['titulo'] ?? ''));
        $descricao = trim(strip_tags($data['descricao'] ?? ''));
        $disciplinaId = !empty($data['disciplina_id']) ? (int) $data['disciplina_id'] : null;
        $dataEntrega = !empty($data['data_entrega']) ? $data['data_entrega'] : null;
        $prioridade = in_array($data['prioridade'] ?? '', ['baixa', 'media', 'alta', 'urgente']) ? $data['prioridade'] : 'media';
        $status = in_array($data['status'] ?? '', ['pendente', 'em_andamento', 'concluida']) ? $data['status'] : 'pendente';

        if (empty($titulo)) {
            return ['success' => false, 'message' => 'O título da tarefa é obrigatório.'];
        }

        $cleanData = [
            'disciplina_id' => $disciplinaId,
            'titulo'        => $titulo,
            'descricao'     => $descricao ?: null,
            'data_entrega'  => $dataEntrega,
            'prioridade'    => $prioridade,
            'status'        => $status
        ];

        $updated = $this->taskModel->update($id, $userId, $cleanData);

        if (!$updated) {
            return ['success' => false, 'message' => 'Não foi possível atualizar a tarefa.'];
        }

        $this->auditService->log($userId, 'ATUALIZAR', 'tarefa', $id, "Atualizou a tarefa '{$titulo}'", $existing, $cleanData, 'SUCESSO');

        return ['success' => true, 'message' => 'Tarefa atualizada com sucesso!'];
    }

    public function toggleStatus(int $id, int $userId): array {
        $existing = $this->taskModel->findById($id, $userId);
        if (!$existing) {
            return ['success' => false, 'message' => 'Tarefa não encontrada.'];
        }

        $newStatus = ($existing['status'] === 'concluida') ? 'pendente' : 'concluida';
        $updated = $this->taskModel->updateStatus($id, $userId, $newStatus);

        if (!$updated) {
            return ['success' => false, 'message' => 'Falha ao alterar status da tarefa.'];
        }

        $msg = ($newStatus === 'concluida') ? 'Tarefa marcada como concluída! Parabéns!' : 'Tarefa reaberta como pendente.';
        $this->auditService->log($userId, 'ATUALIZAR', 'tarefa', $id, "Alterou status da tarefa '{$existing['titulo']}' para {$newStatus}", ['status' => $existing['status']], ['status' => $newStatus], 'SUCESSO');

        return ['success' => true, 'message' => $msg, 'new_status' => $newStatus];
    }

    public function delete(int $id, int $userId): array {
        $existing = $this->taskModel->findById($id, $userId);
        if (!$existing) {
            return ['success' => false, 'message' => 'Tarefa não encontrada para exclusão.'];
        }

        $deleted = $this->taskModel->delete($id, $userId);

        if (!$deleted) {
            return ['success' => false, 'message' => 'Falha ao excluir a tarefa.'];
        }

        $this->auditService->log($userId, 'EXCLUIR', 'tarefa', $id, "Excluiu a tarefa '{$existing['titulo']}'", $existing, null, 'SUCESSO');

        return ['success' => true, 'message' => 'Tarefa excluída com sucesso!'];
    }
}
