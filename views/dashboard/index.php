<?php
$pageTitle = 'Painel Geral';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="flex-1 flex overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
        <!-- Banner de Boas-vindas -->
        <div class="bg-gradient-to-r from-brand-700 via-indigo-600 to-brand-600 rounded-2xl p-6 sm:p-8 text-white shadow-xl shadow-brand-500/10 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm mb-3">
                        📅 <?= date('d/m/Y') ?> • <?= ucfirst(strftime('%A', time()) ?: date('l')) ?>
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Olá, <?= htmlspecialchars(explode(' ', $user['nome'] ?? 'Estudante')[0]) ?>! 👋
                    </h1>
                    <p class="mt-1 text-brand-100 text-sm sm:text-base max-w-xl">
                        Aqui está o panorama atual dos seus estudos, tarefas e compromissos acadêmicos.
                    </p>
                </div>

                <!-- Botões de Ação Rápida -->
                <div class="flex flex-wrap gap-2.5 sm:self-center">
                    <a href="index.php?route=tasks" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-brand-700 font-semibold text-xs sm:text-sm shadow-md hover:bg-brand-50 transition transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nova Tarefa
                    </a>
                    <a href="index.php?route=events" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs sm:text-sm border border-white/20 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Novo Evento
                    </a>
                </div>
            </div>
        </div>

        <!-- 4 Cards de Métricas Principais -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <!-- Card 1: Tarefas Pendentes -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Tarefas Pendentes</p>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline space-x-2">
                    <span class="text-3xl font-black text-slate-800"><?= $stats['pendente'] + $stats['em_andamento'] ?></span>
                    <span class="text-xs text-slate-500 font-medium">de <?= $stats['total'] ?> no total</span>
                </div>
                <div class="mt-3 flex items-center text-xs text-slate-600">
                    <span class="text-emerald-600 font-semibold flex items-center mr-2">
                        <?= $stats['concluida'] ?> concluídas
                    </span>
                </div>
            </div>

            <!-- Card 2: Média Acadêmica Geral -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Coeficiente Geral</p>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-brand-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline space-x-2">
                    <span class="text-3xl font-black <?= $overallAverage >= 7.0 ? 'text-emerald-600' : ($overallAverage >= 5.0 ? 'text-amber-600' : 'text-slate-800') ?>">
                        <?= number_format($overallAverage, 1, ',', '.') ?>
                    </span>
                    <span class="text-xs text-slate-500 font-medium">/ 10,0</span>
                </div>
                <p class="mt-3 text-xs text-slate-500">Média ponderada das avaliações</p>
            </div>

            <!-- Card 3: Disciplinas Ativas -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Disciplinas</p>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline space-x-2">
                    <span class="text-3xl font-black text-slate-800"><?= count($subjects) ?></span>
                    <span class="text-xs text-slate-500 font-medium">matérias cadastradas</span>
                </div>
                <a href="index.php?route=subjects" class="mt-3 inline-block text-xs font-semibold text-brand-600 hover:text-brand-700">Ver todas &rarr;</a>
            </div>

            <!-- Card 4: Próximos Compromissos -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Provas & Eventos</p>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline space-x-2">
                    <span class="text-3xl font-black text-slate-800"><?= count($upcomingEvents) ?></span>
                    <span class="text-xs text-slate-500 font-medium">próximos</span>
                </div>
                <a href="index.php?route=events" class="mt-3 inline-block text-xs font-semibold text-brand-600 hover:text-brand-700">Ver agenda &rarr;</a>
            </div>
        </div>

        <!-- Seções Principais: 2 Colunas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna Esquerda: Tarefas Imediatas e Disciplinas (2/3 da largura em desktop) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Próximas Entregas de Tarefas -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-3 h-3 rounded-full bg-brand-500"></div>
                            <h2 class="text-base font-bold text-slate-800">Próximas Tarefas e Prazos</h2>
                        </div>
                        <a href="index.php?route=tasks" class="text-xs font-semibold text-brand-600 hover:text-brand-700">Ver todas</a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        <?php if (empty($upcomingTasks)): ?>
                            <div class="p-8 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-medium">Nenhuma tarefa pendente com prazo próximo!</p>
                                <a href="index.php?route=tasks" class="mt-2 inline-block text-xs font-semibold text-brand-600 hover:underline">Cadastrar uma tarefa</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($upcomingTasks as $task): ?>
                                <div class="p-4 hover:bg-slate-50/80 transition flex items-start justify-between gap-3">
                                    <div class="flex items-start space-x-3">
                                        <form action="index.php?route=tasks/toggle" method="POST" class="mt-0.5">
                                            <?= CsrfMiddleware::field() ?>
                                            <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                            <button type="submit" class="w-5 h-5 rounded border border-slate-300 hover:border-brand-500 flex items-center justify-center transition" title="Marcar como concluída">
                                            </button>
                                        </form>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800 leading-snug"><?= htmlspecialchars($task['titulo']) ?></p>
                                            <div class="flex flex-wrap items-center gap-2 mt-1.5 text-xs text-slate-500">
                                                <?php if (!empty($task['disciplina_nome'])): ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium" style="background-color: <?= htmlspecialchars($task['disciplina_cor']) ?>15; color: <?= htmlspecialchars($task['disciplina_cor']) ?>;">
                                                        <?= htmlspecialchars($task['disciplina_nome']) ?>
                                                    </span>
                                                <?php endif; ?>

                                                <?php if (!empty($task['data_entrega'])): ?>
                                                    <span class="flex items-center text-slate-500">
                                                        <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <?= date('d/m/Y H:i', strtotime($task['data_entrega'])) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Badge de Prioridade -->
                                    <?php
                                        $prioColors = [
                                            'urgente' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'alta'    => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'media'   => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'baixa'   => 'bg-slate-50 text-slate-600 border-slate-200',
                                        ];
                                    ?>
                                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full border <?= $prioColors[$task['prioridade']] ?? 'bg-slate-50 text-slate-600' ?>">
                                        <?= htmlspecialchars($task['prioridade']) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Disciplinas e Médias -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-slate-800">Aproveitamento por Disciplina</h2>
                        <a href="index.php?route=grades" class="text-xs font-semibold text-brand-600 hover:text-brand-700">Boletim completo</a>
                    </div>

                    <?php if (empty($subjects)): ?>
                        <p class="text-sm text-slate-400 text-center py-4">Nenhuma disciplina cadastrada ainda.</p>
                    <?php else: ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <?php foreach ($subjects as $s): ?>
                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2 truncate">
                                            <div class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: <?= htmlspecialchars($s['cor']) ?>;"></div>
                                            <span class="text-sm font-bold text-slate-800 truncate" title="<?= htmlspecialchars($s['nome']) ?>"><?= htmlspecialchars($s['nome']) ?></span>
                                        </div>
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-full <?= ($s['media_atual'] !== null && $s['media_atual'] >= $s['media_minima']) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' ?>">
                                            <?= $s['media_atual'] !== null ? number_format($s['media_atual'], 1, ',', '.') : '--' ?>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs text-slate-500">
                                        <span><?= $s['tarefas_pendentes'] ?> tarefas pendentes</span>
                                        <span>Meta: <?= number_format($s['media_minima'], 1, ',', '.') ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Coluna Direita: Próximos Eventos e Auditoria Recente (1/3 em desktop) -->
            <div class="space-y-8">
                <!-- Agenda / Eventos -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-slate-800">Próximos Eventos & Provas</h2>
                        <a href="index.php?route=events" class="text-xs font-semibold text-brand-600 hover:text-brand-700">Agenda</a>
                    </div>

                    <div class="space-y-3">
                        <?php if (empty($upcomingEvents)): ?>
                            <p class="text-xs text-slate-400 text-center py-4">Nenhum evento agendado para os próximos dias.</p>
                        <?php else: ?>
                            <?php foreach ($upcomingEvents as $evt): ?>
                                <div class="p-3.5 rounded-xl border-l-4 border-brand-500 bg-slate-50/70 hover:bg-slate-100/70 transition">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-brand-100 text-brand-800">
                                            <?= htmlspecialchars($evt['tipo']) ?>
                                        </span>
                                        <span class="text-xs text-slate-500 font-medium">
                                            <?= date('d/m • H:i', strtotime($evt['data_inicio'])) ?>
                                        </span>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-800 mt-1.5"><?= htmlspecialchars($evt['titulo']) ?></p>
                                    <?php if (!empty($evt['local'])): ?>
                                        <p class="text-xs text-slate-500 mt-1 flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <?= htmlspecialchars($evt['local']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Histórico Recente de Auditoria -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-slate-800">Atividades Recentes</h2>
                        <a href="index.php?route=history" class="text-xs font-semibold text-brand-600 hover:text-brand-700">Histórico</a>
                    </div>

                    <div class="space-y-3">
                        <?php if (empty($recentHistory['items'])): ?>
                            <p class="text-xs text-slate-400 text-center py-4">Nenhuma atividade registrada.</p>
                        <?php else: ?>
                            <?php foreach ($recentHistory['items'] as $log): ?>
                                <div class="flex items-start space-x-3 text-xs">
                                    <div class="w-2 h-2 rounded-full bg-brand-500 mt-1.5 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-slate-700 font-medium"><?= htmlspecialchars($log['descricao']) ?></p>
                                        <span class="text-slate-400 text-[11px]"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
