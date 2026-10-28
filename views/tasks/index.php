<?php
$pageTitle = 'Tarefas & Atividades';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="flex-1 flex overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
        <!-- Header da Página -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Tarefas & Atividades</h1>
                <p class="text-sm text-slate-500 mt-1">Organize listas de exercícios, trabalhos escolares e entregas</p>
            </div>
            <button type="button" onclick="Modal.open('new-task-modal')" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow-md shadow-brand-500/20 transition">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Tarefa
            </button>
        </div>

        <!-- Filtros e Barra de Status -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 mb-6 shadow-sm">
            <form action="index.php" method="GET" class="flex flex-wrap items-center justify-between gap-4">
                <input type="hidden" name="route" value="tasks">

                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <!-- Filtro por Status -->
                    <div>
                        <select name="status" onchange="this.form.submit()" class="text-xs font-semibold rounded-xl border-slate-200 bg-slate-50 py-2 px-3 text-slate-700 focus:ring-brand-500">
                            <option value="todos" <?= ($statusFilter === 'todos') ? 'selected' : '' ?>>Todos os Status</option>
                            <option value="pendente" <?= ($statusFilter === 'pendente') ? 'selected' : '' ?>>Pendentes (<?= $stats['pendente'] ?>)</option>
                            <option value="em_andamento" <?= ($statusFilter === 'em_andamento') ? 'selected' : '' ?>>Em Andamento (<?= $stats['em_andamento'] ?>)</option>
                            <option value="concluida" <?= ($statusFilter === 'concluida') ? 'selected' : '' ?>>Concluídas (<?= $stats['concluida'] ?>)</option>
                        </select>
                    </div>

                    <!-- Filtro por Prioridade -->
                    <div>
                        <select name="priority" onchange="this.form.submit()" class="text-xs font-semibold rounded-xl border-slate-200 bg-slate-50 py-2 px-3 text-slate-700 focus:ring-brand-500">
                            <option value="todas" <?= ($priorityFilter === 'todas') ? 'selected' : '' ?>>Todas as Prioridades</option>
                            <option value="urgente" <?= ($priorityFilter === 'urgente') ? 'selected' : '' ?>>Urgente</option>
                            <option value="alta" <?= ($priorityFilter === 'alta') ? 'selected' : '' ?>>Alta</option>
                            <option value="media" <?= ($priorityFilter === 'media') ? 'selected' : '' ?>>Média</option>
                            <option value="baixa" <?= ($priorityFilter === 'baixa') ? 'selected' : '' ?>>Baixa</option>
                        </select>
                    </div>

                    <!-- Filtro por Disciplina -->
                    <div>
                        <select name="subject_id" onchange="this.form.submit()" class="text-xs font-semibold rounded-xl border-slate-200 bg-slate-50 py-2 px-3 text-slate-700 focus:ring-brand-500">
                            <option value="">Todas as Matérias</option>
                            <?php foreach ($subjects as $sb): ?>
                                <option value="<?= $sb['id'] ?>" <?= ($subjectFilter == $sb['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($sb['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Resumo rápido -->
                <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                    <span>Exibindo <strong><?= count($tasks) ?></strong> registros</span>
                </div>
            </form>
        </div>

        <!-- Listagem de Tarefas -->
        <?php if (empty($tasks)): ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-lg mx-auto my-8">
                <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Nenhuma tarefa encontrada</h3>
                <p class="text-sm text-slate-500 mt-2 mb-6">Não há tarefas cadastradas para os filtros selecionados.</p>
                <button type="button" onclick="Modal.open('new-task-modal')" class="px-5 py-2.5 rounded-xl bg-brand-600 text-white font-semibold text-sm shadow hover:bg-brand-700 transition">
                    Adicionar Nova Tarefa
                </button>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($tasks as $t): ?>
                    <?php
                        $isCompleted = ($t['status'] === 'concluida');
                        $isOverdue = (!$isCompleted && !empty($t['data_entrega']) && strtotime($t['data_entrega']) < time());
                        
                        $prioBadge = [
                            'urgente' => 'bg-rose-50 text-rose-700 border-rose-200',
                            'alta'    => 'bg-amber-50 text-amber-700 border-amber-200',
                            'media'   => 'bg-blue-50 text-blue-700 border-blue-200',
                            'baixa'   => 'bg-slate-50 text-slate-600 border-slate-200',
                        ];
                    ?>
                    <div class="bg-white rounded-2xl border <?= $isOverdue ? 'border-rose-300 ring-1 ring-rose-200' : 'border-slate-200/90' ?> p-4 sm:p-5 shadow-sm hover:shadow-md transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start space-x-3.5">
                            <!-- Botão de Toggle Status -->
                            <form action="index.php?route=tasks/toggle" method="POST" class="mt-1">
                                <?= CsrfMiddleware::field() ?>
                                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                <button type="submit" 
                                        class="w-6 h-6 rounded-lg border-2 <?= $isCompleted ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 hover:border-brand-500 bg-white' ?> flex items-center justify-center transition"
                                        title="<?= $isCompleted ? 'Reabrir tarefa' : 'Marcar como concluída' ?>">
                                    <?php if ($isCompleted): ?>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    <?php endif; ?>
                                </button>
                            </form>

                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-sm sm:text-base font-bold <?= $isCompleted ? 'line-through text-slate-400' : 'text-slate-800' ?>">
                                        <?= htmlspecialchars($t['titulo']) ?>
                                    </h3>

                                    <!-- Badge de Prioridade -->
                                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md border <?= $prioBadge[$t['prioridade']] ?? 'bg-slate-100' ?>">
                                        <?= htmlspecialchars($t['prioridade']) ?>
                                    </span>

                                    <!-- Status da Tarefa -->
                                    <?php if ($t['status'] === 'em_andamento'): ?>
                                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-indigo-50 text-brand-700 border border-brand-200">
                                            Em Andamento
                                        </span>
                                    <?php elseif ($isCompleted): ?>
                                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Concluída
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($t['descricao'])): ?>
                                    <p class="text-xs text-slate-600 mt-1 max-w-2xl line-clamp-2">
                                        <?= htmlspecialchars($t['descricao']) ?>
                                    </p>
                                <?php endif; ?>

                                <!-- Tags da Tarefa -->
                                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-slate-500">
                                    <?php if (!empty($t['disciplina_nome'])): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold" style="background-color: <?= htmlspecialchars($t['disciplina_cor']) ?>15; color: <?= htmlspecialchars($t['disciplina_cor']) ?>;">
                                            <?= htmlspecialchars($t['disciplina_nome']) ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($t['data_entrega'])): ?>
                                        <span class="flex items-center <?= $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-500' ?>">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Entrega: <?= date('d/m/Y H:i', strtotime($t['data_entrega'])) ?>
                                            <?= $isOverdue ? ' (Atrasada!)' : '' ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Ações da Tarefa -->
                        <div class="flex items-center space-x-1 sm:self-center border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100 justify-end">
                            <button type="button" 
                                    onclick='openEditTaskModal(<?= json_encode($t, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                                    class="p-2 rounded-xl text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition" title="Editar Tarefa">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>

                            <form action="index.php?route=tasks/delete" method="POST" class="inline" data-confirm="Tem certeza que deseja excluir esta tarefa permanentemente?">
                                <?= CsrfMiddleware::field() ?>
                                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Excluir Tarefa">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

<!-- MODAL: NOVA TAREFA -->
<div id="new-task-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="modal-backdrop fixed inset-0"></div>
    <div class="modal-container relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-10 overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Cadastrar Nova Tarefa</h3>
            <button type="button" data-close-modal="new-task-modal" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="index.php?route=tasks/store" method="POST" class="mt-4 space-y-4">
            <?= CsrfMiddleware::field() ?>

            <div>
                <label for="task_titulo" class="block text-xs font-bold uppercase text-slate-700">Título da Tarefa *</label>
                <input id="task_titulo" name="titulo" type="text" required placeholder="Ex: Exercícios de Álgebra Linear"
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label for="task_disciplina_id" class="block text-xs font-bold uppercase text-slate-700">Disciplina Vinculada</label>
                <select id="task_disciplina_id" name="disciplina_id" class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                    <option value="">Sem disciplina vinculada</option>
                    <?php foreach ($subjects as $sb): ?>
                        <option value="<?= $sb['id'] ?>"><?= htmlspecialchars($sb['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="task_descricao" class="block text-xs font-bold uppercase text-slate-700">Descrição / Observações</label>
                <textarea id="task_descricao" name="descricao" rows="3" placeholder="Detalhes da entrega, referências bibliográficas..."
                          class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-1">
                    <label for="task_data_entrega" class="block text-xs font-bold uppercase text-slate-700">Prazo de Entrega</label>
                    <input id="task_data_entrega" name="data_entrega" type="datetime-local"
                           class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-xl text-slate-900 text-xs focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="task_prioridade" class="block text-xs font-bold uppercase text-slate-700">Prioridade</label>
                    <select id="task_prioridade" name="prioridade" class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-xl text-slate-900 text-xs focus:ring-2 focus:ring-brand-500">
                        <option value="baixa">Baixa</option>
                        <option value="media" selected>Média</option>
                        <option value="alta">Alta</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>
                <div>
                    <label for="task_status" class="block text-xs font-bold uppercase text-slate-700">Status</label>
                    <select id="task_status" name="status" class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-xl text-slate-900 text-xs focus:ring-2 focus:ring-brand-500">
                        <option value="pendente" selected>Pendente</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="concluida">Concluída</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100">
                <button type="button" data-close-modal="new-task-modal" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow transition">
                    Salvar Tarefa
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDITAR TAREFA -->
<div id="edit-task-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="modal-backdrop fixed inset-0"></div>
    <div class="modal-container relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-10 overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Editar Tarefa</h3>
            <button type="button" data-close-modal="edit-task-modal" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="index.php?route=tasks/update" method="POST" class="mt-4 space-y-4">
            <?= CsrfMiddleware::field() ?>
            <input type="hidden" id="edit_task_id" name="id">

            <div>
                <label for="edit_task_titulo" class="block text-xs font-bold uppercase text-slate-700">Título da Tarefa *</label>
                <input id="edit_task_titulo" name="titulo" type="text" required
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label for="edit_task_disciplina_id" class="block text-xs font-bold uppercase text-slate-700">Disciplina Vinculada</label>
                <select id="edit_task_disciplina_id" name="disciplina_id" class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                    <option value="">Sem disciplina vinculada</option>
                    <?php foreach ($subjects as $sb): ?>
                        <option value="<?= $sb['id'] ?>"><?= htmlspecialchars($sb['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="edit_task_descricao" class="block text-xs font-bold uppercase text-slate-700">Descrição / Observações</label>
                <textarea id="edit_task_descricao" name="descricao" rows="3"
                          class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-1">
                    <label for="edit_task_data_entrega" class="block text-xs font-bold uppercase text-slate-700">Prazo de Entrega</label>
                    <input id="edit_task_data_entrega" name="data_entrega" type="datetime-local"
                           class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-xl text-slate-900 text-xs focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="edit_task_prioridade" class="block text-xs font-bold uppercase text-slate-700">Prioridade</label>
                    <select id="edit_task_prioridade" name="prioridade" class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-xl text-slate-900 text-xs focus:ring-2 focus:ring-brand-500">
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>
                <div>
                    <label for="edit_task_status" class="block text-xs font-bold uppercase text-slate-700">Status</label>
                    <select id="edit_task_status" name="status" class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-xl text-slate-900 text-xs focus:ring-2 focus:ring-brand-500">
                        <option value="pendente">Pendente</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="concluida">Concluída</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100">
                <button type="button" data-close-modal="edit-task-modal" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow transition">
                    Atualizar Tarefa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditTaskModal(task) {
    document.getElementById('edit_task_id').value = task.id;
    document.getElementById('edit_task_titulo').value = task.titulo;
    document.getElementById('edit_task_disciplina_id').value = task.disciplina_id || '';
    document.getElementById('edit_task_descricao').value = task.descricao || '';
    if (task.data_entrega) {
        // Formato para input datetime-local: YYYY-MM-DDTHH:MM
        const dt = new Date(task.data_entrega);
        const y = dt.getFullYear();
        const m = String(dt.getMonth() + 1).padStart(2, '0');
        const d = String(dt.getDate()).padStart(2, '0');
        const h = String(dt.getHours()).padStart(2, '0');
        const min = String(dt.getMinutes()).padStart(2, '0');
        document.getElementById('edit_task_data_entrega').value = `${y}-${m}-${d}T${h}:${min}`;
    } else {
        document.getElementById('edit_task_data_entrega').value = '';
    }
    document.getElementById('edit_task_prioridade').value = task.prioridade || 'media';
    document.getElementById('edit_task_status').value = task.status || 'pendente';
    Modal.open('edit-task-modal');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
