<?php
/**
 * SOS-Estudante - Service: SubjectService
 * Regras de negócio de disciplinas, cálculo de aproveitamento e auditoria
 */

require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/AuditService.php';

class SubjectService {
    private Subject $subjectModel;
    private AuditService $auditService;

    public function __construct() {
        $this->subjectModel = new Subject();
        $this->auditService = new AuditService();
    }

    public function getAll(int $userId): array {
        return $this->subjectModel->allByUserId($userId);
    }

    public function getById(int $id, int $userId): ?array {
        return $this->subjectModel->findById($id, $userId);
    }

    public function create(int $userId, array $data): array {
        $nome = trim(strip_tags($data['nome'] ?? ''));
        $codigo = trim(strip_tags($data['codigo'] ?? ''));
        $professor = trim(strip_tags($data['professor'] ?? ''));
        $sala = trim(strip_tags($data['sala'] ?? ''));
        $cor = trim($data['cor'] ?? '#4F46E5');
        $semestre = trim(strip_tags($data['semestre'] ?? ''));
        $cargaHoraria = !empty($data['carga_horaria']) ? (int) $data['carga_horaria'] : null;
        $mediaMinima = !empty($data['media_minima']) ? (float) str_replace(',', '.', $data['media_minima']) : 7.00;
        $status = in_array($data['status'] ?? '', ['em_andamento', 'aprovado', 'reprovado']) ? $data['status'] : 'em_andamento';

        if (empty($nome)) {
            return ['success' => false, 'message' => 'O nome da disciplina é obrigatório.'];
        }

        if ($mediaMinima < 0 || $mediaMinima > 10) {
            return ['success' => false, 'message' => 'A média mínima deve estar entre 0.0 e 10.0.'];
        }

        $cleanData = [
            'usuario_id'    => $userId,
            'nome'          => $nome,
            'codigo'        => $codigo ?: null,
            'professor'     => $professor ?: null,
            'sala'          => $sala ?: null,
            'cor'           => $cor,
            'semestre'      => $semestre ?: null,
            'carga_horaria' => $cargaHoraria,
            'media_minima'  => $mediaMinima,
            'status'        => $status
        ];

        $id = $this->subjectModel->create($cleanData);

        if (!$id) {
            return ['success' => false, 'message' => 'Falha ao cadastrar a disciplina no banco de dados.'];
        }

        $this->auditService->log($userId, 'CRIAR', 'disciplina', $id, "Cadastrou a disciplina '{$nome}'", null, $cleanData, 'SUCESSO');

        return ['success' => true, 'message' => 'Disciplina cadastrada com sucesso!', 'id' => $id];
    }

    public function update(int $id, int $userId, array $data): array {
        $existing = $this->subjectModel->findById($id, $userId);
        if (!$existing) {
            return ['success' => false, 'message' => 'Disciplina não encontrada ou acesso não autorizado.'];
        }

        $nome = trim(strip_tags($data['nome'] ?? ''));
        $codigo = trim(strip_tags($data['codigo'] ?? ''));
        $professor = trim(strip_tags($data['professor'] ?? ''));
        $sala = trim(strip_tags($data['sala'] ?? ''));
        $cor = trim($data['cor'] ?? '#4F46E5');
        $semestre = trim(strip_tags($data['semestre'] ?? ''));
        $cargaHoraria = !empty($data['carga_horaria']) ? (int) $data['carga_horaria'] : null;
        $mediaMinima = !empty($data['media_minima']) ? (float) str_replace(',', '.', $data['media_minima']) : 7.00;
        $status = in_array($data['status'] ?? '', ['em_andamento', 'aprovado', 'reprovado']) ? $data['status'] : 'em_andamento';

        if (empty($nome)) {
            return ['success' => false, 'message' => 'O nome da disciplina é obrigatório.'];
        }

        $cleanData = [
            'nome'          => $nome,
            'codigo'        => $codigo ?: null,
            'professor'     => $professor ?: null,
            'sala'          => $sala ?: null,
            'cor'           => $cor,
            'semestre'      => $semestre ?: null,
            'carga_horaria' => $cargaHoraria,
            'media_minima'  => $mediaMinima,
            'status'        => $status
        ];

        $updated = $this->subjectModel->update($id, $userId, $cleanData);

        if (!$updated) {
            return ['success' => false, 'message' => 'Não foi possível atualizar a disciplina.'];
        }

        $this->auditService->log($userId, 'ATUALIZAR', 'disciplina', $id, "Atualizou a disciplina '{$nome}'", $existing, $cleanData, 'SUCESSO');

        return ['success' => true, 'message' => 'Disciplina atualizada com sucesso!'];
    }

    public function delete(int $id, int $userId): array {
        $existing = $this->subjectModel->findById($id, $userId);
        if (!$existing) {
            return ['success' => false, 'message' => 'Disciplina não encontrada para exclusão.'];
        }

        $deleted = $this->subjectModel->delete($id, $userId);

        if (!$deleted) {
            return ['success' => false, 'message' => 'Falha ao excluir a disciplina.'];
        }

        $this->auditService->log($userId, 'EXCLUIR', 'disciplina', $id, "Excluiu a disciplina '{$existing['nome']}'", $existing, null, 'SUCESSO');

        return ['success' => true, 'message' => 'Disciplina excluída com sucesso!'];
    }
}
