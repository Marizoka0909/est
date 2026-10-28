<?php
$pageTitle = 'Agenda & Provas';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="flex-1 flex overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
        <!-- Header da Página -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Agenda & Provas</h1>
                <p class="text-sm text-slate-500 mt-1">Acompanhe datas de provas, apresentações e eventos acadêmicos</p>
            </div>
            <button type="button" onclick="Modal.open('new-event-modal')" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow-md shadow-brand-500/20 transition">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agendar Evento
            </button>
        </div>

        <!-- Filtros Rápidos de Tipo -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 mb-6 shadow-sm flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <?php
                    $tipos = [
                        'todos'        => 'Todos os Eventos',
                        'prova'        => 'Provas & Exames',
                        'trabalho'     => 'Trabalhos',
                        'apresentacao' => 'Apresentações',
                        'aula'         => 'Aulas / Monitoria',
                        'reuniao'      => 'Reuniões de Grupo',
                    ];
                ?>
                <?php foreach ($tipos as $key => $lbl): ?>
                    <a href="index.php?route=events&tipo=<?= $key ?>" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition <?= ($tipoFilter === $key) ? 'bg-brand-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                        <?= $lbl ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <span class="text-xs text-slate-500 font-medium">Total: <strong><?= count($events) ?></strong> eventos</span>
        </div>

        <!-- Listagem de Eventos -->
        <?php if (empty($events)): ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-lg mx-auto my-8">
                <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Nenhum evento agendado</h3>
                <p class="text-sm text-slate-500 mt-2 mb-6">Não perca datas de provas ou apresentações de trabalho.</p>
                <button type="button" onclick="Modal.open('new-event-modal')" class="px-5 py-2.5 rounded-xl bg-brand-600 text-white font-semibold text-sm shadow hover:bg-brand-700 transition">
                    Agendar Primeiro Evento
                </button>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($events as $evt): ?>
                    <?php
                        $isPast = strtotime($evt['data_inicio']) < time();
                        $badgeTheme = [
                            'prova'        => 'bg-rose-100 text-rose-800 border-rose-200',
                            'trabalho'     => 'bg-amber-100 text-amber-800 border-amber-200',
                            'apresentacao' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'aula'         => 'bg-blue-100 text-blue-800 border-blue-200',
                            'reuniao'      => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'outro'        => 'bg-slate-100 text-slate-800 border-slate-200',
                        ];
                    ?>
                    <div class="bg-white rounded-2xl border <?= $isPast ? 'border-slate-200 opacity-75' : 'border-slate-200/90 shadow-sm hover:shadow-md' ?> p-5 transition flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2">
                                <span class="px-2.5 py-1 text-[11px] font-bold uppercase rounded-lg border <?= $badgeTheme[$evt['tipo']] ?? 'bg-slate-100 text-slate-800' ?>">
                                    <?= htmlspecialchars($evt['tipo']) ?>
                                </span>

                                <div class="flex items-center space-x-1">
                                    <button type="button" 
                                            onclick='openEditEventModal(<?= json_encode($evt, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition" title="Editar Evento">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <form action="index.php?route=events/delete" method="POST" class="inline" data-confirm="Deseja realmente remover este evento da sua agenda?">
                                        <?= CsrfMiddleware::field() ?>
                                        <input type="hidden" name="id" value="<?= $evt['id'] ?>">
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Excluir Evento">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <h3 class="text-base font-bold text-slate-900 mt-2.5 leading-snug"><?= htmlspecialchars($evt['titulo']) ?></h3>

                            <?php if (!empty($evt['disciplina_nome'])): ?>
                                <span class="inline-flex items-center px-2 py-0.5 mt-2 rounded text-[11px] font-semibold" style="background-color: <?= htmlspecialchars($evt['disciplina_cor']) ?>15; color: <?= htmlspecialchars($evt['disciplina_cor']) ?>;">
                                    <?= htmlspecialchars($evt['disciplina_nome']) ?>
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($evt['descricao'])): ?>
                                <p class="text-xs text-slate-600 mt-2 line-clamp-2"><?= htmlspecialchars($evt['descricao']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 space-y-1 text-xs text-slate-500">
                            <div class="flex items-center font-medium <?= $isPast ? 'text-slate-400' : 'text-slate-700' ?>">
                                <svg class="w-4 h-4 mr-1.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Início: <?= date('d/m/Y • H:i', strtotime($evt['data_inicio'])) ?>
                                <?= $isPast ? ' <span class="ml-1 text-[10px] text-slate-400">(Concluído)</span>' : '' ?>
                            </div>

                            <?php if (!empty($evt['data_fim'])): ?>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-slate-400 flex-shrink-0 opacity-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M12 8v4" />
                                    </svg>
                                    Término: <?= date('d/m/Y • H:i', strtotime($evt['data_fim'])) ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($evt['local'])): ?>
                                <div class="flex items-center text-slate-600">
                                    <svg class="w-4 h-4 mr-1.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Local: <?= htmlspecialchars($evt['local']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

<!-- MODAL: NOVO EVENTO -->
<div id="new-event-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="modal-backdrop fixed inset-0"></div>
    <div class="modal-container relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-10 overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Agendar Prova ou Evento</h3>
            <button type="button" data-close-modal="new-event-modal" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="index.php?route=events/store" method="POST" class="mt-4 space-y-4">
            <?= CsrfMiddleware::field() ?>

            <div>
                <label for="evt_titulo" class="block text-xs font-bold uppercase text-slate-700">Título do Evento *</label>
                <input id="evt_titulo" name="titulo" type="text" required placeholder="Ex: Prova 1 de Algoritmos"
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="evt_tipo" class="block text-xs font-bold uppercase text-slate-700">Tipo de Evento</label>
                    <select id="evt_tipo" name="tipo" class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                        <option value="prova" selected>Prova / Exame</option>
                        <option value="trabalho">Entrega de Trabalho</option>
                        <option value="apresentacao">Apresentação / Seminário</option>
                        <option value="aula">Aula Especial / Reposição</option>
                        <option value="reuniao">Reunião de Grupo</option>
                        <option value="outro">Outro Compromisso</option>
                    </select>
                </div>
                <div>
                    <label for="evt_disciplina_id" class="block text-xs font-bold uppercase text-slate-700">Disciplina</label>
                    <select id="evt_disciplina_id" name="disciplina_id" class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                        <option value="">Sem disciplina vinculada</option>
                        <?php foreach ($subjects as $sb): ?>
                            <option value="<?= $sb['id'] ?>"><?= htmlspecialchars($sb['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="evt_data_inicio" class="block text-xs font-bold uppercase text-slate-700">Data e Hora de Início *</label>
                    <input id="evt_data_inicio" name="data_inicio" type="datetime-local" required
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="evt_data_fim" class="block text-xs font-bold uppercase text-slate-700">Data e Hora de Término</label>
                    <input id="evt_data_fim" name="data_fim" type="datetime-local"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label for="evt_local" class="block text-xs font-bold uppercase text-slate-700">Local ou Link Online</label>
                <input id="evt_local" name="local" type="text" placeholder="Ex: Sala 304, Teams, Google Meet"
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label for="evt_descricao" class="block text-xs font-bold uppercase text-slate-700">Conteúdo / Observações</label>
                <textarea id="evt_descricao" name="descricao" rows="3" placeholder="Capítulos cobrados na prova, regras de apresentação..."
                          class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500"></textarea>
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100">
                <button type="button" data-close-modal="new-event-modal" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow transition">
                    Agendar Evento
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDITAR EVENTO -->
<div id="edit-event-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="modal-backdrop fixed inset-0"></div>
    <div class="modal-container relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-10 overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Editar Evento</h3>
            <button type="button" data-close-modal="edit-event-modal" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="index.php?route=events/update" method="POST" class="mt-4 space-y-4">
            <?= CsrfMiddleware::field() ?>
            <input type="hidden" id="edit_evt_id" name="id">

            <div>
                <label for="edit_evt_titulo" class="block text-xs font-bold uppercase text-slate-700">Título do Evento *</label>
                <input id="edit_evt_titulo" name="titulo" type="text" required
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_evt_tipo" class="block text-xs font-bold uppercase text-slate-700">Tipo de Evento</label>
                    <select id="edit_evt_tipo" name="tipo" class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                        <option value="prova">Prova / Exame</option>
                        <option value="trabalho">Entrega de Trabalho</option>
                        <option value="apresentacao">Apresentação / Seminário</option>
                        <option value="aula">Aula Especial / Reposição</option>
                        <option value="reuniao">Reunião de Grupo</option>
                        <option value="outro">Outro Compromisso</option>
                    </select>
                </div>
                <div>
                    <label for="edit_evt_disciplina_id" class="block text-xs font-bold uppercase text-slate-700">Disciplina</label>
                    <select id="edit_evt_disciplina_id" name="disciplina_id" class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                        <option value="">Sem disciplina vinculada</option>
                        <?php foreach ($subjects as $sb): ?>
                            <option value="<?= $sb['id'] ?>"><?= htmlspecialchars($sb['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="edit_evt_data_inicio" class="block text-xs font-bold uppercase text-slate-700">Data e Hora de Início *</label>
                    <input id="edit_evt_data_inicio" name="data_inicio" type="datetime-local" required
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label for="edit_evt_data_fim" class="block text-xs font-bold uppercase text-slate-700">Data e Hora de Término</label>
                    <input id="edit_evt_data_fim" name="data_fim" type="datetime-local"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label for="edit_evt_local" class="block text-xs font-bold uppercase text-slate-700">Local ou Link Online</label>
                <input id="edit_evt_local" name="local" type="text"
                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label for="edit_evt_descricao" class="block text-xs font-bold uppercase text-slate-700">Conteúdo / Observações</label>
                <textarea id="edit_evt_descricao" name="descricao" rows="3"
                          class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500"></textarea>
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100">
                <button type="button" data-close-modal="edit-event-modal" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow transition">
                    Atualizar Evento
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditEventModal(evt) {
    document.getElementById('edit_evt_id').value = evt.id;
    document.getElementById('edit_evt_titulo').value = evt.titulo;
    document.getElementById('edit_evt_tipo').value = evt.tipo || 'prova';
    document.getElementById('edit_evt_disciplina_id').value = evt.disciplina_id || '';
    
    if (evt.data_inicio) {
        const dt = new Date(evt.data_inicio);
        const y = dt.getFullYear();
        const m = String(dt.getMonth() + 1).padStart(2, '0');
        const d = String(dt.getDate()).padStart(2, '0');
        const h = String(dt.getHours()).padStart(2, '0');
        const min = String(dt.getMinutes()).padStart(2, '0');
        document.getElementById('edit_evt_data_inicio').value = `${y}-${m}-${d}T${h}:${min}`;
    }

    if (evt.data_fim) {
        const dt = new Date(evt.data_fim);
        const y = dt.getFullYear();
        const m = String(dt.getMonth() + 1).padStart(2, '0');
        const d = String(dt.getDate()).padStart(2, '0');
        const h = String(dt.getHours()).padStart(2, '0');
        const min = String(dt.getMinutes()).padStart(2, '0');
        document.getElementById('edit_evt_data_fim').value = `${y}-${m}-${d}T${h}:${min}`;
    } else {
        document.getElementById('edit_evt_data_fim').value = '';
    }

    document.getElementById('edit_evt_local').value = evt.local || '';
    document.getElementById('edit_evt_descricao').value = evt.descricao || '';
    Modal.open('edit-event-modal');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
