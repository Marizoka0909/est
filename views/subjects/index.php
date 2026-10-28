<?php
$pageTitle = 'Disciplinas & Matérias';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="flex-1 flex overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
        <!-- Header da Página -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Disciplinas & Matérias</h1>
                <p class="text-sm text-slate-500 mt-1">Gerencie suas matérias, professores, salas e metas acadêmicas</p>
            </div>
            <button type="button" onclick="Modal.open('new-subject-modal')" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow-md shadow-brand-500/20 transition">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Disciplina
            </button>
        </div>

        <!-- Grade de Matérias -->
        <?php if (empty($subjects)): ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-lg mx-auto my-8">
                <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Nenhuma disciplina cadastrada</h3>
                <p class="text-sm text-slate-500 mt-2 mb-6">Cadastre as matérias do seu semestre para vincular tarefas, eventos e notas.</p>
                <button type="button" onclick="Modal.open('new-subject-modal')" class="px-5 py-2.5 rounded-xl bg-brand-600 text-white font-semibold text-sm shadow hover:bg-brand-700 transition">
                    Adicionar Primeira Disciplina
                </button>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($subjects as $s): ?>
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-md transition flex flex-col justify-between overflow-hidden">
                        <!-- Cabeçalho do Card com faixa de cor -->
                        <div class="p-5 pb-4 border-b border-slate-100 relative">
                            <div class="absolute top-0 left-0 right-0 h-1.5" style="background-color: <?= htmlspecialchars($s['cor']) ?>;"></div>
                            
                            <div class="flex items-start justify-between gap-2 mt-1">
                                <div>
                                    <span class="text-[11px] font-bold font-mono uppercase px-2 py-0.5 rounded bg-slate-100 text-slate-600">
                                        <?= htmlspecialchars($s['codigo'] ?: 'S/ CÓDIGO') ?>
                                    </span>
                                    <h3 class="text-base font-bold text-slate-900 mt-1.5 leading-snug"><?= htmlspecialchars($s['nome']) ?></h3>
                                </div>

                                <div class="flex items-center space-x-1">
                                    <button type="button" 
                                            onclick='openEditSubjectModal(<?= json_encode($s, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition" title="Editar">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <form action="index.php?route=subjects/delete" method="POST" class="inline" data-confirm="Deseja realmente excluir esta disciplina? As tarefas, eventos e notas associados poderão ser afetados.">
                                        <?= CsrfMiddleware::field() ?>
                                        <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Excluir">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="mt-3 space-y-1 text-xs text-slate-500">
                                <?php if (!empty($s['professor'])): ?>
                                    <p class="flex items-center truncate">
                                        <span class="font-medium text-slate-700 mr-1.5">Docente:</span> <?= htmlspecialchars($s['professor']) ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($s['sala'])): ?>
                                    <p class="flex items-center">
                                        <span class="font-medium text-slate-700 mr-1.5">Local:</span> <?= htmlspecialchars($s['sala']) ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($s['semestre'])): ?>
                                    <p class="flex items-center">
                                        <span class="font-medium text-slate-700 mr-1.5">Semestre:</span> <?= htmlspecialchars($s['semestre']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Métricas e Indicadores da Disciplina -->
                        <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between text-xs">
                            <div>
                                <span class="block text-slate-400 text-[10px] font-semibold uppercase">Média Atual</span>
                                <span class="text-base font-extrabold <?= ($s['media_atual'] !== null && $s['media_atual'] >= $s['media_minima']) ? 'text-emerald-600' : ($s['media_atual'] !== null ? 'text-amber-600' : 'text-slate-400') ?>">
                                    <?= $s['media_atual'] !== null ? number_format($s['media_atual'], 1, ',', '.') : 'Sem notas' ?>
                                </span>
                            </div>

                            <div class="text-right">
                                <span class="block text-slate-400 text-[10px] font-semibold uppercase">Pendências</span>
                                <span class="text-xs font-bold text-slate-700">
                                    <?= $s['tarefas_pendentes'] ?> tarefas
                                </span>
                            </div>

                            <div class="text-right">
                                <span class="block text-slate-400 text-[10px] font-semibold uppercase">Mínimo p/ Aprovação</span>
                                <span class="text-xs font-bold text-slate-700">
                                    <?= number_format($s['media_minima'], 1, ',', '.') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

<!-- MODAL: NOVA DISCIPLINA -->
<div id="new-subject-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="modal-backdrop fixed inset-0"></div>
    <div class="modal-container relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-10 overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Cadastrar Nova Disciplina</h3>
            <button type="button" data-close-modal="new-subject-modal" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="index.php?route=subjects/store" method="POST" class="mt-4 space-y-4">
            <?= CsrfMiddleware::field() ?>

            <div>
                <label for="new_nome" class="block text-xs font-bold uppercase text-slate-700">Nome da Disciplina *</label>
                <input id="new_nome" name="nome" type="text" required placeholder="Ex: Cálculo I, Redes de Computadores"
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="new_codigo" class="block text-xs font-bold uppercase text-slate-700">Código</label>
                    <input id="new_codigo" name="codigo" type="text" placeholder="Ex: CC204"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="new_semestre" class="block text-xs font-bold uppercase text-slate-700">Semestre / Ano</label>
                    <input id="new_semestre" name="semestre" type="text" placeholder="Ex: 2026.2"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="new_professor" class="block text-xs font-bold uppercase text-slate-700">Docente / Professor</label>
                    <input id="new_professor" name="professor" type="text" placeholder="Ex: Prof. Dr. Silva"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="new_sala" class="block text-xs font-bold uppercase text-slate-700">Sala / Local</label>
                    <input id="new_sala" name="sala" type="text" placeholder="Ex: Lab 02, Auditório"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label for="new_media_minima" class="block text-xs font-bold uppercase text-slate-700">Média Aprovação</label>
                    <input id="new_media_minima" name="media_minima" type="number" step="0.1" min="0" max="10" value="7.0"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="new_carga_horaria" class="block text-xs font-bold uppercase text-slate-700">Carga Horária (h)</label>
                    <input id="new_carga_horaria" name="carga_horaria" type="number" placeholder="Ex: 60"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="new_cor" class="block text-xs font-bold uppercase text-slate-700">Cor Identificadora</label>
                    <input id="new_cor" name="cor" type="color" value="#4F46E5"
                           class="mt-1 block w-full h-10 border border-slate-300 rounded-xl cursor-pointer p-1">
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100">
                <button type="button" data-close-modal="new-subject-modal" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow transition">
                    Salvar Disciplina
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDITAR DISCIPLINA -->
<div id="edit-subject-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="modal-backdrop fixed inset-0"></div>
    <div class="modal-container relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-10 overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Editar Disciplina</h3>
            <button type="button" data-close-modal="edit-subject-modal" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="index.php?route=subjects/update" method="POST" class="mt-4 space-y-4">
            <?= CsrfMiddleware::field() ?>
            <input type="hidden" id="edit_id" name="id">

            <div>
                <label for="edit_nome" class="block text-xs font-bold uppercase text-slate-700">Nome da Disciplina *</label>
                <input id="edit_nome" name="nome" type="text" required
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_codigo" class="block text-xs font-bold uppercase text-slate-700">Código</label>
                    <input id="edit_codigo" name="codigo" type="text"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="edit_semestre" class="block text-xs font-bold uppercase text-slate-700">Semestre / Ano</label>
                    <input id="edit_semestre" name="semestre" type="text"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_professor" class="block text-xs font-bold uppercase text-slate-700">Docente / Professor</label>
                    <input id="edit_professor" name="professor" type="text"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="edit_sala" class="block text-xs font-bold uppercase text-slate-700">Sala / Local</label>
                    <input id="edit_sala" name="sala" type="text"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label for="edit_media_minima" class="block text-xs font-bold uppercase text-slate-700">Média Aprovação</label>
                    <input id="edit_media_minima" name="media_minima" type="number" step="0.1" min="0" max="10"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="edit_carga_horaria" class="block text-xs font-bold uppercase text-slate-700">Carga Horária (h)</label>
                    <input id="edit_carga_horaria" name="carga_horaria" type="number"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="edit_cor" class="block text-xs font-bold uppercase text-slate-700">Cor</label>
                    <input id="edit_cor" name="cor" type="color"
                           class="mt-1 block w-full h-10 border border-slate-300 rounded-xl cursor-pointer p-1">
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100">
                <button type="button" data-close-modal="edit-subject-modal" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow transition">
                    Atualizar Disciplina
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditSubjectModal(subject) {
    document.getElementById('edit_id').value = subject.id;
    document.getElementById('edit_nome').value = subject.nome;
    document.getElementById('edit_codigo').value = subject.codigo || '';
    document.getElementById('edit_semestre').value = subject.semestre || '';
    document.getElementById('edit_professor').value = subject.professor || '';
    document.getElementById('edit_sala').value = subject.sala || '';
    document.getElementById('edit_media_minima').value = subject.media_minima || '7.0';
    document.getElementById('edit_carga_horaria').value = subject.carga_horaria || '';
    document.getElementById('edit_cor').value = subject.cor || '#4F46E5';
    Modal.open('edit-subject-modal');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
