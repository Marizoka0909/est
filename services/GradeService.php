<?php
/**
 * SOS-Estudante - Service: GradeService
 * Regras de negócio de avaliações, cálculo de médias ponderadas e aprovação
 */

require_once __DIR__ . '/../models/Grade.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/AuditService.php';

class GradeService {
    private Grade $gradeModel;
    private Subject $subjectModel;
    private AuditService $auditService;

    public function __construct() {
        $this->gradeModel = new Grade();
        $this->subjectModel = new Subject();
        $this->auditService = new AuditService();
    }

    public function getAll(int $userId): array {
        return $this->gradeModel->allByUserId($userId);
    }

    public function getBySubject(int $subjectId, int $userId): array {
        return $this->gradeModel->allBySubject($subjectId, $userId);
    }

    public function getById(int $id, int $userId): ?array {
        return $this->gradeModel->findById($id, $userId);
    }

    public function getOverallAverage(int $userId): float {
        return $this->gradeModel->getOverallAverage($userId);
    }

    public function create(int $userId, array $data): array {
        $disciplinaId = !empty($data['disciplina_id']) ? (int) $data['disciplina_id'] : 0;
        $titulo = trim(strip_tags($data['titulo'] ?? ''));
        $peso = !empty($data['peso']) ? (float) str_replace(',', '.', $data['peso']) : 1.00;
        $notaObtida = isset($data['nota_obtida']) ? (float) str_replace(',', '.', $data['nota_obtida']) : -1;
        $notaMaxima = !empty($data['nota_maxima']) ? (float) str_replace(',', '.', $data['nota_maxima']) : 10.00;
        $dataAvaliacao = !empty($data['data_avaliacao']) ? $data['data_avaliacao'] : null;
        $observacoes = trim(strip_tags($data['observacoes'] ?? ''));

        if ($disciplinaId <= 0) {
            return ['success' => false, 'message' => 'Selecione uma disciplina válida.'];
        }

        // Verificar se a disciplina pertence ao usuário
        $subject = $this->subjectModel->findById($disciplinaId, $userId);
        if (!$subject) {
            return ['success' => false, 'message' => 'Disciplina inválida ou não encontrada.'];
        }

        if (empty($titulo)) {
            return ['success' => false, 'message' => 'O título da avaliação é obrigatório (ex: Prova 1, Seminário).'];
        }

        if ($notaObtida < 0 || $notaObtida > $notaMaxima) {
            return ['success' => false, 'message' => "A nota obtida deve estar entre 0.0 e a nota máxima ({$notaMaxima})."];
        }

        if ($peso <= 0) {
            return ['success' => false, 'message' => 'O peso da avaliação deve ser maior que zero.'];
        }

        $cleanData = [
            'usuario_id'     => $userId,
            'disciplina_id'  => $disciplinaId,
            'titulo'         => $titulo,
            'peso'           => $peso,
            'nota_obtida'    => $notaObtida,
            'nota_maxima'    => $notaMaxima,
            'data_avaliacao' => $dataAvaliacao,
            'observacoes'    => $observacoes ?: null
        ];

        $id = $this->gradeModel->create($cleanData);

        if (!$id) {
            return ['success' => false, 'message' => 'Falha ao registrar a nota no banco de dados.'];
        }

        $this->auditService->log($userId, 'CRIAR', 'nota', $id, "Lançou a nota '{$notaObtida}/{$notaMaxima}' na avaliação '{$titulo}' ({$subject['nome']})", null, $cleanData, 'SUCESSO');

        return ['success' => true, 'message' => 'Nota lançada com sucesso no boletim!', 'id' => $id];
    }

    public function update(int $id, int $userId, array $data): array {
        $existing = $this->gradeModel->findById($id, $userId);
        if (!$existing) {
            return ['success' => false, 'message' => 'Avaliação não encontrada ou acesso não autorizado.'];
        }

        $disciplinaId = !empty($data['disciplina_id']) ? (int) $data['disciplina_id'] : 0;
        $titulo = trim(strip_tags($data['titulo'] ?? ''));
        $peso = !empty($data['peso']) ? (float) str_replace(',', '.', $data['peso']) : 1.00;
        $notaObtida = isset($data['nota_obtida']) ? (float) str_replace(',', '.', $data['nota_obtida']) : -1;
        $notaMaxima = !empty($data['nota_maxima']) ? (float) str_replace(',', '.', $data['nota_maxima']) : 10.00;
        $dataAvaliacao = !empty($data['data_avaliacao']) ? $data['data_avaliacao'] : null;
        $observacoes = trim(strip_tags($data['observacoes'] ?? ''));

        if ($disciplinaId <= 0) {
            return ['success' => false, 'message' => 'Selecione uma disciplina válida.'];
        }

        $subject = $this->subjectModel->findById($disciplinaId, $userId);
        if (!$subject) {
            return ['success' => false, 'message' => 'Disciplina inválida ou não encontrada.'];
        }

        if (empty($titulo)) {
            return ['success' => false, 'message' => 'O título da avaliação é obrigatório.'];
        }

        if ($notaObtida < 0 || $notaObtida > $notaMaxima) {
            return ['success' => false, 'message' => "A nota obtida deve estar entre 0.0 e a nota máxima ({$notaMaxima})."];
        }

        if ($peso <= 0) {
            return ['success' => false, 'message' => 'O peso da avaliação deve ser maior que zero.'];
        }

        $cleanData = [
            'disciplina_id'  => $disciplinaId,
            'titulo'         => $titulo,
            'peso'           => $peso,
            'nota_obtida'    => $notaObtida,
            'nota_maxima'    => $notaMaxima,
            'data_avaliacao' => $dataAvaliacao,
            'observacoes'    => $observacoes ?: null
        ];

        $updated = $this->gradeModel->update($id, $userId, $cleanData);

        if (!$updated) {
            return ['success' => false, 'message' => 'Não foi possível atualizar a nota.'];
        }

        $this->auditService->log($userId, 'ATUALIZAR', 'nota', $id, "Atualizou a nota da avaliação '{$titulo}' para {$notaObtida}", $existing, $cleanData, 'SUCESSO');

        return ['success' => true, 'message' => 'Avaliação atualizada com sucesso!'];
    }

    public function delete(int $id, int $userId): array {
        $existing = $this->gradeModel->findById($id, $userId);
        if (!$existing) {
            return ['success' => false, 'message' => 'Avaliação não encontrada para exclusão.'];
        }

        $deleted = $this->gradeModel->delete($id, $userId);

        if (!$deleted) {
            return ['success' => false, 'message' => 'Falha ao excluir a avaliação.'];
        }

        $this->auditService->log($userId, 'EXCLUIR', 'nota', $id, "Excluiu a avaliação '{$existing['titulo']}'", $existing, null, 'SUCESSO');

        return ['success' => true, 'message' => 'Avaliação removida do boletim com sucesso!'];
    }
}
