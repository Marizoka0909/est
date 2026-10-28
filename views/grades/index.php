<?php
$pageTitle = 'Notas & Boletim';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="flex-1 flex overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
        <!-- Header da Página -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Notas & Desempenho</h1>
                <p class="text-sm text-slate-500 mt-1">Acompanhe seu boletim, médias ponderadas e status de aprovação</p>
            </div>
            <button type="button" onclick="Modal.open('new-grade-modal')" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow-md shadow-brand-500/20 transition">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Lançar Nota
            </button>
        </div>

        <!-- Card de Resumo do Coeficiente Geral -->
        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white rounded-2xl p-6 sm:p-7 shadow-lg mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="text-xs uppercase tracking-wider font-semibold text-indigo-300">Desempenho Acadêmico Global</span>
                    <h2 class="text-3xl sm:text-4xl font-black mt-1">
                        <?= number_format($overallAverage, 2, ',', '.') ?> <span class="text-lg font-medium text-indigo-200">/ 10,00</span>
                    </h2>
                    <p class="text-xs text-slate-300 mt-1">Média ponderada baseada em todas as avaliações registradas</p>
                </div>

                <div class="sm:text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $overallAverage >= 7.0 ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' ?>">
                        <?= $overallAverage >= 7.0 ? 'Aproveitamento Excelente' : 'Aproveitamento Regular' ?>
                    </span>
                    <p class="text-xs text-slate-400 mt-2">Total de <?= count($grades) ?> notas lançadas</p>
                </div>
            </div>
        </div>

        <!-- Seções por Disciplina -->
        <?php if (empty($subjects)): ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-lg mx-auto my-8">
                <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Nenhuma disciplina cadastrada</h3>
                <p class="text-sm text-slate-500 mt-2 mb-6">Cadastre primeiro suas disciplinas para depois lançar as notas das avaliações.</p>
                <a href="index.php?route=subjects" class="px-5 py-2.5 rounded-xl bg-brand-600 text-white font-semibold text-sm shadow hover:bg-brand-700 transition">
                    Cadastrar Disciplinas
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($subjects as $sb): ?>
                    <?php
                        // Filtrar notas desta disciplina
                        $subjectGrades = array_filter($grades, fn($g) => $g['disciplina_id'] == $sb['id']);
                        
                        // Cálculo da média ponderada
                        $somaPonderada = 0;
                        $somaPesos = 0;
                        foreach ($subjectGrades as $g) {
                            $somaPonderada += ($g['nota_obtida'] / $g['nota_maxima'] * 10) * $g['peso'];
                            $somaPesos += $g['peso'];
                        }
                        $mediaCalculada = ($somaPesos > 0) ? round($somaPonderada / $somaPesos, 2) : null;
                        $isAprovado = ($mediaCalculada !== null && $mediaCalculada >= $sb['media_minima']);
                        $percentProgress = ($mediaCalculada !== null) ? min(100, ($mediaCalculada / 10) * 100) : 0;
                    ?>
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                        <!-- Cabeçalho da Disciplina -->
                        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
                            <div class="flex items-center space-x-3">
                                <div class="w-3.5 h-3.5 rounded-full" style="background-color: <?= htmlspecialchars($sb['cor']) ?>;"></div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900"><?= htmlspecialchars($sb['nome']) ?></h3>
                                    <span class="text-xs text-slate-500">
                                        Meta para aprovação: <strong><?= number_format($sb['media_minima'], 1, ',', '.') ?></strong>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Média da Matéria</span>
                                    <span class="text-xl font-black <?= $isAprovado ? 'text-emerald-600' : ($mediaCalculada !== null ? 'text-amber-600' : 'text-slate-400') ?>">
                                        <?= $mediaCalculada !== null ? number_format($mediaCalculada, 2, ',', '.') : '--' ?>
                                    </span>
                                </div>

                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg border <?= $isAprovado ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($mediaCalculada !== null ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-100 text-slate-600 border-slate-200') ?>">
                                    <?= $isAprovado ? 'Aprovado' : ($mediaCalculada !== null ? 'Em Avaliação' : 'Sem Notas') ?>
                                </span>
                            </div>
                        </div>

                        <!-- Barra de Progresso da Média -->
                        <div class="w-full bg-slate-100 h-1.5">
                            <div class="h-1.5 transition-all duration-500 <?= $isAprovado ? 'bg-emerald-500' : 'bg-amber-500' ?>" style="width: <?= $percentProgress ?>%;"></div>
                        </div>

                        <!-- Tabela de Avaliações -->
                        <?php if (empty($subjectGrades)): ?>
                            <div class="p-6 text-center text-xs text-slate-400">
                                Nenhuma avaliação lançada para esta disciplina.
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs text-slate-600">
                                    <thead class="bg-slate-50 text-[11px] font-bold uppercase text-slate-400 border-b border-slate-100">
                                        <tr>
                                            <th class="py-3 px-5">Avaliação</th>
                                            <th class="py-3 px-4">Peso</th>
                                            <th class="py-3 px-4">Nota Obtida</th>
                                            <th class="py-3 px-4">Data</th>
                                            <th class="py-3 px-4">Observações</th>
                                            <th class="py-3 px-5 text-right">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <?php foreach ($subjectGrades as $grd): ?>
                                            <tr class="hover:bg-slate-50/70 transition">
                                                <td class="py-3.5 px-5 font-semibold text-slate-900">
                                                    <?= htmlspecialchars($grd['titulo']) ?>
                                                </td>
                                                <td class="py-3.5 px-4 font-mono">
                                                    <?= number_format($grd['peso'], 1, ',', '.') ?>
                                                </td>
                                                <td class="py-3.5 px-4">
                                                    <span class="font-bold text-sm <?= ($grd['nota_obtida'] >= ($grd['nota_maxima'] * 0.7)) ? 'text-emerald-600' : 'text-slate-800' ?>">
                                                        <?= number_format($grd['nota_obtida'], 2, ',', '.') ?>
                                                    </span>
                                                    <span class="text-slate-400">/ <?= number_format($grd['nota_maxima'], 1, ',', '.') ?></span>
                                                </td>
                                                <td class="py-3.5 px-4 text-slate-500">
                                                    <?= !empty($grd['data_avaliacao']) ? date('d/m/Y', strtotime($grd['data_avaliacao'])) : '--' ?>
                                                </td>
                                                <td class="py-3.5 px-4 text-slate-500 max-w-xs truncate">
                                                    <?= htmlspecialchars($grd['observacoes'] ?? '--') ?>
                                                </td>
                                                <td class="py-3.5 px-5 text-right space-x-1 whitespace-nowrap">
                                                    <button type="button" 
                                                            onclick='openEditGradeModal(<?= json_encode($grd, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                                                            class="p-1 rounded text-slate-400 hover:text-brand-600 transition" title="Editar Nota">
                                                        <svg class="w-4 h-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </button>
                                                    <form action="index.php?route=grades/delete" method="POST" class="inline" data-confirm="Tem certeza que deseja excluir esta nota do boletim?">
                                                        <?= CsrfMiddleware::field() ?>
                                                        <input type="hidden" name="id" value="<?= $grd['id'] ?>">
                                                        <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-600 transition" title="Excluir Nota">
                                                            <svg class="w-4 h-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

<!-- MODAL: LANÇAR NOTA -->
<div id="new-grade-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="modal-backdrop fixed inset-0"></div>
    <div class="modal-container relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-10 overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Lançar Avaliação / Nota</h3>
            <button type="button" data-close-modal="new-grade-modal" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="index.php?route=grades/store" method="POST" class="mt-4 space-y-4">
            <?= CsrfMiddleware::field() ?>

            <div>
                <label for="grd_disciplina_id" class="block text-xs font-bold uppercase text-slate-700">Disciplina *</label>
                <select id="grd_disciplina_id" name="disciplina_id" required class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                    <option value="">Selecione a disciplina</option>
                    <?php foreach ($subjects as $sb): ?>
                        <option value="<?= $sb['id'] ?>"><?= htmlspecialchars($sb['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="grd_titulo" class="block text-xs font-bold uppercase text-slate-700">Título da Avaliação *</label>
                <input id="grd_titulo" name="titulo" type="text" required placeholder="Ex: Prova 1, Seminário, Trabalho prático"
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label for="grd_nota_obtida" class="block text-xs font-bold uppercase text-slate-700">Nota Obtida *</label>
                    <input id="grd_nota_obtida" name="nota_obtida" type="number" step="0.01" min="0" max="100" required placeholder="Ex: 8.5"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="grd_nota_maxima" class="block text-xs font-bold uppercase text-slate-700">Nota Máxima</label>
                    <input id="grd_nota_maxima" name="nota_maxima" type="number" step="0.01" min="1" value="10.0"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="grd_peso" class="block text-xs font-bold uppercase text-slate-700">Peso</label>
                    <input id="grd_peso" name="peso" type="number" step="0.1" min="0.1" value="1.0"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label for="grd_data_avaliacao" class="block text-xs font-bold uppercase text-slate-700">Data da Avaliação</label>
                <input id="grd_data_avaliacao" name="data_avaliacao" type="date" value="<?= date('Y-m-d') ?>"
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label for="grd_observacoes" class="block text-xs font-bold uppercase text-slate-700">Observações / Feedback do Professor</label>
                <input id="grd_observacoes" name="observacoes" type="text" placeholder="Ex: Excelente argumentação na questão 3"
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100">
                <button type="button" data-close-modal="new-grade-modal" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow transition">
                    Registrar Nota
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDITAR NOTA -->
<div id="edit-grade-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="modal-backdrop fixed inset-0"></div>
    <div class="modal-container relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-10 overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Editar Avaliação</h3>
            <button type="button" data-close-modal="edit-grade-modal" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="index.php?route=grades/update" method="POST" class="mt-4 space-y-4">
            <?= CsrfMiddleware::field() ?>
            <input type="hidden" id="edit_grd_id" name="id">

            <div>
                <label for="edit_grd_disciplina_id" class="block text-xs font-bold uppercase text-slate-700">Disciplina *</label>
                <select id="edit_grd_disciplina_id" name="disciplina_id" required class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                    <?php foreach ($subjects as $sb): ?>
                        <option value="<?= $sb['id'] ?>"><?= htmlspecialchars($sb['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="edit_grd_titulo" class="block text-xs font-bold uppercase text-slate-700">Título da Avaliação *</label>
                <input id="edit_grd_titulo" name="titulo" type="text" required
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label for="edit_grd_nota_obtida" class="block text-xs font-bold uppercase text-slate-700">Nota Obtida *</label>
                    <input id="edit_grd_nota_obtida" name="nota_obtida" type="number" step="0.01" min="0" max="100" required
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="edit_grd_nota_maxima" class="block text-xs font-bold uppercase text-slate-700">Nota Máxima</label>
                    <input id="edit_grd_nota_maxima" name="nota_maxima" type="number" step="0.01" min="1"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="edit_grd_peso" class="block text-xs font-bold uppercase text-slate-700">Peso</label>
                    <input id="edit_grd_peso" name="peso" type="number" step="0.1" min="0.1"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label for="edit_grd_data_avaliacao" class="block text-xs font-bold uppercase text-slate-700">Data</label>
                <input id="edit_grd_data_avaliacao" name="data_avaliacao" type="date"
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label for="edit_grd_observacoes" class="block text-xs font-bold uppercase text-slate-700">Observações</label>
                <input id="edit_grd_observacoes" name="observacoes" type="text"
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100">
                <button type="button" data-close-modal="edit-grade-modal" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow transition">
                    Atualizar Nota
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditGradeModal(grade) {
    document.getElementById('edit_grd_id').value = grade.id;
    document.getElementById('edit_grd_disciplina_id').value = grade.disciplina_id;
    document.getElementById('edit_grd_titulo').value = grade.titulo;
    document.getElementById('edit_grd_nota_obtida').value = grade.nota_obtida;
    document.getElementById('edit_grd_nota_maxima').value = grade.nota_maxima || '10.0';
    document.getElementById('edit_grd_peso').value = grade.peso || '1.0';
    document.getElementById('edit_grd_data_avaliacao').value = grade.data_avaliacao || '';
    document.getElementById('edit_grd_observacoes').value = grade.observacoes || '';
    Modal.open('edit-grade-modal');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
