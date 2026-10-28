<?php
$pageTitle = 'Histórico & Auditoria';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="flex-1 flex overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
        <!-- Header da Página -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Histórico & Auditoria</h1>
                <p class="text-sm text-slate-500 mt-1">Rastreabilidade completa de todas as alterações realizadas na sua conta</p>
            </div>
            <div class="inline-flex items-center px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold">
                <svg class="w-4 h-4 mr-1.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Registros Imutáveis & Auditáveis
            </div>
        </div>

        <!-- Filtros de Pesquisa -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 mb-6 shadow-sm">
            <form action="index.php" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <input type="hidden" name="route" value="history">

                <!-- Operação -->
                <div>
                    <label for="f_operacao" class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Operação</label>
                    <select id="f_operacao" name="operacao" class="w-full text-xs font-semibold rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-slate-700 focus:ring-brand-500">
                        <option value="">Todas as Operações</option>
                        <option value="CRIAR" <?= ($filters['operacao'] === 'CRIAR') ? 'selected' : '' ?>>CRIAR (Inclusão)</option>
                        <option value="ATUALIZAR" <?= ($filters['operacao'] === 'ATUALIZAR') ? 'selected' : '' ?>>ATUALIZAR (Edição)</option>
                        <option value="EXCLUIR" <?= ($filters['operacao'] === 'EXCLUIR') ? 'selected' : '' ?>>EXCLUIR (Remoção)</option>
                        <option value="LOGIN" <?= ($filters['operacao'] === 'LOGIN') ? 'selected' : '' ?>>LOGIN (Acesso)</option>
                        <option value="LOGOUT" <?= ($filters['operacao'] === 'LOGOUT') ? 'selected' : '' ?>>LOGOUT (Saída)</option>
                    </select>
                </div>

                <!-- Recurso -->
                <div>
                    <label for="f_recurso" class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Recurso</label>
                    <select id="f_recurso" name="recurso" class="w-full text-xs font-semibold rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-slate-700 focus:ring-brand-500">
                        <option value="">Todos os Recursos</option>
                        <option value="tarefa" <?= ($filters['recurso'] === 'tarefa') ? 'selected' : '' ?>>Tarefas</option>
                        <option value="disciplina" <?= ($filters['recurso'] === 'disciplina') ? 'selected' : '' ?>>Disciplinas</option>
                        <option value="evento" <?= ($filters['recurso'] === 'evento') ? 'selected' : '' ?>>Eventos / Agenda</option>
                        <option value="nota" <?= ($filters['recurso'] === 'nota') ? 'selected' : '' ?>>Notas / Boletim</option>
                        <option value="usuario" <?= ($filters['recurso'] === 'usuario') ? 'selected' : '' ?>>Usuário / Conta</option>
                    </select>
                </div>

                <!-- Data Início -->
                <div>
                    <label for="f_data_inicio" class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Data Início</label>
                    <input id="f_data_inicio" name="data_inicio" type="date" value="<?= htmlspecialchars($filters['data_inicio'] ?? '') ?>"
                           class="w-full text-xs font-semibold rounded-xl border-slate-200 bg-slate-50 py-2 px-3 text-slate-700 focus:ring-brand-500">
                </div>

                <!-- Data Fim -->
                <div>
                    <label for="f_data_fim" class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Data Fim</label>
                    <input id="f_data_fim" name="data_fim" type="date" value="<?= htmlspecialchars($filters['data_fim'] ?? '') ?>"
                           class="w-full text-xs font-semibold rounded-xl border-slate-200 bg-slate-50 py-2 px-3 text-slate-700 focus:ring-brand-500">
                </div>

                <!-- Botões de Ação -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-xs transition shadow-sm">
                        Filtrar
                    </button>
                    <a href="index.php?route=history" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-xs transition text-center" title="Limpar Filtros">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabela de Auditoria -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <?php if (empty($historyData['items'])): ?>
                <div class="p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">Nenhum registro de auditoria encontrado</p>
                    <p class="text-xs text-slate-400 mt-1">Tente ajustar os filtros de busca acima.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-5">Data & Hora</th>
                                <th class="py-3 px-4">Operação</th>
                                <th class="py-3 px-4">Recurso</th>
                                <th class="py-3 px-4">Descrição</th>
                                <th class="py-3 px-4">Resultado</th>
                                <th class="py-3 px-4">IP / Origem</th>
                                <th class="py-3 px-5 text-right">Rastreabilidade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($historyData['items'] as $log): ?>
                                <?php
                                    $opBadges = [
                                        'CRIAR'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'ATUALIZAR' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'EXCLUIR'   => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'LOGIN'     => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'LOGOUT'    => 'bg-slate-100 text-slate-700 border-slate-200',
                                    ];
                                ?>
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-3.5 px-5 font-mono text-slate-500 whitespace-nowrap">
                                        <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md border <?= $opBadges[$log['operacao']] ?? 'bg-slate-100 text-slate-700' ?>">
                                            <?= htmlspecialchars($log['operacao']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-800 uppercase text-[11px] whitespace-nowrap">
                                        <?= htmlspecialchars($log['recurso']) ?> #<?= $log['recurso_id'] ?? '-' ?>
                                    </td>
                                    <td class="py-3.5 px-4 font-medium text-slate-800 max-w-sm">
                                        <?= htmlspecialchars($log['descricao']) ?>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center text-[11px] font-bold <?= ($log['status_resultado'] === 'SUCESSO') ? 'text-emerald-600' : 'text-rose-600' ?>">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 <?= ($log['status_resultado'] === 'SUCESSO') ? 'bg-emerald-500' : 'bg-rose-500' ?>"></span>
                                            <?= htmlspecialchars($log['status_resultado']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-[11px] text-slate-400 whitespace-nowrap">
                                        <?= htmlspecialchars($log['ip_address'] ?? '127.0.0.1') ?>
                                    </td>
                                    <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                        <?php if (!empty($log['valores_anteriores']) || !empty($log['valores_posteriores'])): ?>
                                            <button type="button" 
                                                    onclick='showAuditDetails(<?= json_encode($log, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                                                    class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-brand-50 text-brand-700 hover:bg-brand-100 transition">
                                                Ver Detalhes
                                            </button>
                                        <?php else: ?>
                                            <span class="text-slate-300 text-xs">--</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <?php if ($historyData['total_pages'] > 1): ?>
                    <div class="p-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Página <strong><?= $historyData['page'] ?></strong> de <strong><?= $historyData['total_pages'] ?></strong> (<?= $historyData['total'] ?> registros)</span>
                        
                        <div class="flex items-center space-x-1">
                            <?php if ($historyData['page'] > 1): ?>
                                <a href="index.php?route=history&page=<?= $historyData['page'] - 1 ?>&operacao=<?= urlencode($filters['operacao']) ?>&recurso=<?= urlencode($filters['recurso']) ?>" 
                                   class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 font-medium">Anterior</a>
                            <?php endif; ?>

                            <?php if ($historyData['page'] < $historyData['total_pages']): ?>
                                <a href="index.php?route=history&page=<?= $historyData['page'] + 1 ?>&operacao=<?= urlencode($filters['operacao']) ?>&recurso=<?= urlencode($filters['recurso']) ?>" 
                                   class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 font-medium">Próxima</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- MODAL: DETALHES DA AUDITORIA (DIFF DE VALORES) -->
<div id="audit-details-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="modal-backdrop fixed inset-0"></div>
    <div class="modal-container relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6 z-10 overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-bold text-slate-900" id="audit_modal_title">Detalhes da Alteração</h3>
                <p class="text-xs text-slate-500" id="audit_modal_sub"></p>
            </div>
            <button type="button" data-close-modal="audit-details-modal" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="mt-4 space-y-4">
            <div id="audit_box_prev" class="space-y-1">
                <h4 class="text-xs font-bold uppercase text-slate-500">Valores Anteriores (Antes da Modificação)</h4>
                <pre id="audit_json_prev" class="bg-slate-900 text-slate-200 text-xs p-3.5 rounded-xl overflow-x-auto font-mono"></pre>
            </div>

            <div id="audit_box_post" class="space-y-1">
                <h4 class="text-xs font-bold uppercase text-slate-500">Valores Posteriores (Novo Estado Gravado)</h4>
                <pre id="audit_json_post" class="bg-slate-900 text-slate-200 text-xs p-3.5 rounded-xl overflow-x-auto font-mono"></pre>
            </div>
        </div>

        <div class="pt-5 mt-5 flex justify-end border-t border-slate-100">
            <button type="button" data-close-modal="audit-details-modal" class="px-5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold transition">
                Fechar
            </button>
        </div>
    </div>
</div>

<script>
function showAuditDetails(log) {
    document.getElementById('audit_modal_title').innerText = `${log.operacao} no recurso ${log.recurso.toUpperCase()} #${log.recurso_id || ''}`;
    document.getElementById('audit_modal_sub').innerText = `${log.descricao} • ${log.created_at}`;

    const prevBox = document.getElementById('audit_box_prev');
    const postBox = document.getElementById('audit_box_post');

    if (log.valores_anteriores) {
        try {
            const parsed = JSON.parse(log.valores_anteriores);
            document.getElementById('audit_json_prev').innerText = JSON.stringify(parsed, null, 2);
            prevBox.classList.remove('hidden');
        } catch (e) {
            document.getElementById('audit_json_prev').innerText = log.valores_anteriores;
            prevBox.classList.remove('hidden');
        }
    } else {
        prevBox.classList.add('hidden');
    }

    if (log.valores_posteriores) {
        try {
            const parsed = JSON.parse(log.valores_posteriores);
            document.getElementById('audit_json_post').innerText = JSON.stringify(parsed, null, 2);
            postBox.classList.remove('hidden');
        } catch (e) {
            document.getElementById('audit_json_post').innerText = log.valores_posteriores;
            postBox.classList.remove('hidden');
        }
    } else {
        postBox.classList.add('hidden');
    }

    Modal.open('audit-details-modal');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
