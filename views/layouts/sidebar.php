<?php
$currentRoute = $_GET['route'] ?? 'dashboard';

$navItems = [
    [
        'route' => 'dashboard',
        'label' => 'Painel Geral',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />'
    ],
    [
        'route' => 'subjects',
        'label' => 'Disciplinas / Matérias',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />'
    ],
    [
        'route' => 'tasks',
        'label' => 'Tarefas & Atividades',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />'
    ],
    [
        'route' => 'events',
        'label' => 'Agenda & Provas',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />'
    ],
    [
        'route' => 'grades',
        'label' => 'Notas & Boletim',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />'
    ],
    [
        'route' => 'history',
        'label' => 'Histórico & Auditoria',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />'
    ],
    [
        'route' => 'profile',
        'label' => 'Meu Perfil',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />'
    ]
];
?>

<!-- SIDEBAR DESKTOP -->
<aside class="hidden lg:flex lg:flex-shrink-0">
    <div class="w-64 flex flex-col border-r border-slate-200 bg-white">
        <div class="h-0 flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
            <nav class="mt-2 flex-1 px-3 space-y-1">
                <?php foreach ($navItems as $item): ?>
                    <?php 
                        $isActive = ($currentRoute === $item['route']);
                        $classes = $isActive 
                            ? 'bg-brand-50 text-brand-700 font-semibold shadow-sm shadow-brand-500/5' 
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium';
                        $iconClasses = $isActive ? 'text-brand-600' : 'text-slate-400 group-hover:text-slate-600';
                    ?>
                    <a href="index.php?route=<?= $item['route'] ?>" class="<?= $classes ?> group flex items-center px-3.5 py-2.5 text-sm rounded-xl transition">
                        <svg class="<?= $iconClasses ?> mr-3 flex-shrink-0 h-5 w-5 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <?= $item['icon'] ?>
                        </svg>
                        <span><?= $item['label'] ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>

        <!-- Rodapé do Menu Lateral -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-xs font-medium text-slate-500">Sistema Online • v1.0</span>
            </div>
        </div>
    </div>
</aside>

<!-- DRAWER MOBILE -->
<div id="mobile-sidebar" class="fixed inset-0 z-50 lg:hidden hidden" role="dialog" aria-modal="true">
    <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-y-0 left-0 max-w-xs w-full bg-white shadow-xl flex flex-col z-10 animate-in slide-in-from-left duration-200">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-brand-600 text-white flex items-center justify-center font-bold">SOS</div>
                <span class="font-bold text-slate-800">SOS Estudante</span>
            </div>
            <button type="button" id="mobile-sidebar-close" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 focus:outline-none" aria-label="Fechar menu">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <?php foreach ($navItems as $item): ?>
                <?php 
                    $isActive = ($currentRoute === $item['route']);
                    $classes = $isActive 
                        ? 'bg-brand-50 text-brand-700 font-semibold' 
                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium';
                    $iconClasses = $isActive ? 'text-brand-600' : 'text-slate-400';
                ?>
                <a href="index.php?route=<?= $item['route'] ?>" class="<?= $classes ?> flex items-center px-3.5 py-3 text-sm rounded-xl transition">
                    <svg class="<?= $iconClasses ?> mr-3 flex-shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <?= $item['icon'] ?>
                    </svg>
                    <span><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50">
            <a href="index.php?route=logout" class="flex items-center space-x-2 text-sm font-medium text-rose-600 hover:text-rose-700 p-2 rounded-lg hover:bg-rose-50 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Sair da conta</span>
            </a>
        </div>
    </div>
</div>
