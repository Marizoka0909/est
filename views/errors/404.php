<?php
$pageTitle = 'Página não encontrada';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-full flex flex-col justify-center items-center px-4 py-16 bg-slate-50 text-center">
    <div class="w-20 h-20 rounded-3xl bg-indigo-50 text-brand-600 flex items-center justify-center mb-6 shadow-sm">
        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <span class="text-xs font-bold uppercase tracking-wider text-brand-600 font-mono">Erro 404</span>
    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 mt-2">Página não encontrada</h1>
    <p class="text-sm text-slate-500 mt-2 max-w-md">O recurso ou rota solicitada não existe ou foi removido do SOS Estudante.</p>
    <div class="mt-6">
        <a href="index.php?route=dashboard" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow transition">
            Voltar ao Painel
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
