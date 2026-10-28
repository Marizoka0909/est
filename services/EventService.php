<?php
/**
 * SOS-Estudante - Service: EventService
 * Regras de negócio de eventos, provas e agenda acadêmica
 */

require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/AuditService.php';

class EventService {
    private Event $eventModel;
    private AuditService $auditService;

    public function __construct() {
        $this->eventModel = new Event();
        $this->auditService = new AuditService();
    }

    public function getAll(int $userId, ?string $tipo = null): array {
        return $this->eventModel->allByUserId($userId, $tipo);
    }

    public function getUpcoming(int $userId, int $limit = 5): array {
        return $this->eventModel->getUpcoming($userId, $limit);
    }

    public function getById(int $id, int $userId): ?array {
        return $this->eventModel->findById($id, $userId);
    }

    public function create(int $userId, array $data): array {
        $titulo = trim(strip_tags($data['titulo'] ?? ''));
        $tipo = in_array($data['tipo'] ?? '', ['prova', 'trabalho', 'apresentacao', 'aula', 'reuniao', 'outro']) ? $data['tipo'] : 'prova';
        $disciplinaId = !empty($data['disciplina_id']) ? (int) $data['disciplina_id'] : null;
        $dataInicio = !empty($data['data_inicio']) ? $data['data_inicio'] : null;
        $dataFim = !empty($data['data_fim']) ? $data['data_fim'] : null;
        $local = trim(strip_tags($data['local'] ?? ''));
        $descricao = trim(strip_tags($data['descricao'] ?? ''));
        $lembreteAtivo = isset($data['lembrete_ativo']) ? 1 : 0;

        if (empty($titulo)) {
            return ['success' => false, 'message' => 'O título do evento/prova é obrigatório.'];
        }

        if (empty($dataInicio)) {
            return ['success' => false, 'message' => 'A data e horário de início são obrigatórios.'];
        }

        if ($dataFim && strtotime($dataFim) < strtotime($dataInicio)) {
            return ['success' => false, 'message' => 'A data de término não pode ser anterior ao início.'];
        }

        $cleanData = [
            'usuario_id'     => $userId,
            'disciplina_id'  => $disciplinaId,
            'titulo'         => $titulo,
            'tipo'           => $tipo,
            'data_inicio'    => $dataInicio,
            'data_fim'       => $dataFim,
            'local'          => $local ?: null,
            'descricao'      => $descricao ?: null,
            'lembrete_ativo' => $lembreteAtivo
        ];

        $id = $this->eventModel->create($cleanData);

        if (!$id) {
            return ['success' => false, 'message' => 'Falha ao agendar evento no banco de dados.'];
        }

        $this->auditService->log($userId, 'CRIAR', 'evento', $id, "Agendou o evento '{$titulo}' ({$tipo})", null, $cleanData, 'SUCESSO');

        return ['success' => true, 'message' => 'Evento/Compromisso agendado com sucesso!', 'id' => $id];
    }

    public function update(int $id, int $userId, array $data): array {
        $existing = $this->eventModel->findById($id, $userId);
        if (!$existing) {
            return ['success' => false, 'message' => 'Evento não encontrado ou acesso não autorizado.'];
        }

        $titulo = trim(strip_tags($data['titulo'] ?? ''));
        $tipo = in_array($data['tipo'] ?? '', ['prova', 'trabalho', 'apresentacao', 'aula', 'reuniao', 'outro']) ? $data['tipo'] : 'prova';
        $disciplinaId = !empty($data['disciplina_id']) ? (int) $data['disciplina_id'] : null;
        $dataInicio = !empty($data['data_inicio']) ? $data['data_inicio'] : null;
        $dataFim = !empty($data['data_fim']) ? $data['data_fim'] : null;
        $local = trim(strip_tags($data['local'] ?? ''));
        $descricao = trim(strip_tags($data['descricao'] ?? ''));
        $lembreteAtivo = isset($data['lembrete_ativo']) ? 1 : 0;

        if (empty($titulo)) {
            return ['success' => false, 'message' => 'O título do evento/prova é obrigatório.'];
        }

        if (empty($dataInicio)) {
            return ['success' => false, 'message' => 'A data e horário de início são obrigatórios.'];
        }

        if ($dataFim && strtotime($dataFim) < strtotime($dataInicio)) {
            return ['success' => false, 'message' => 'A data de término não pode ser anterior ao início.'];
        }

        $cleanData = [
            'disciplina_id'  => $disciplinaId,
            'titulo'         => $titulo,
            'tipo'           => $tipo,
            'data_inicio'    => $dataInicio,
            'data_fim'       => $dataFim,
            'local'          => $local ?: null,
            'descricao'      => $descricao ?: null,
            'lembrete_ativo' => $lembreteAtivo
        ];

        $updated = $this->eventModel->update($id, $userId, $cleanData);

        if (!$updated) {
            return ['success' => false, 'message' => 'Não foi possível atualizar o evento.'];
        }

        $this->auditService->log($userId, 'ATUALIZAR', 'evento', $id, "Atualizou o evento '{$titulo}'", $existing, $cleanData, 'SUCESSO');

        return ['success' => true, 'message' => 'Evento atualizado com sucesso!'];
    }

    public function delete(int $id, int $userId): array {
        $existing = $this->eventModel->findById($id, $userId);
        if (!$existing) {
            return ['success' => false, 'message' => 'Evento não encontrado para cancelamento.'];
        }

        $deleted = $this->eventModel->delete($id, $userId);

        if (!$deleted) {
            return ['success' => false, 'message' => 'Falha ao remover o evento.'];
        }

        $this->auditService->log($userId, 'EXCLUIR', 'evento', $id, "Cancelou/excluiu o evento '{$existing['titulo']}'", $existing, null, 'SUCESSO');

        return ['success' => true, 'message' => 'Evento cancelado e removido com sucesso!'];
    }
}
