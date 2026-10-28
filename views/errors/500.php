<?php
$pageTitle = 'Erro de Sistema';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-full flex flex-col justify-center items-center px-4 py-16 bg-slate-50 text-center">
    <div class="w-20 h-20 rounded-3xl bg-rose-50 text-rose-600 flex items-center justify-center mb-6 shadow-sm">
        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
    </div>
    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 font-mono">Erro 500</span>
    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">Falha no Processamento</h1>
    
    <p class="text-sm text-slate-600 mt-3 max-w-md mx-auto leading-relaxed">
        Não foi possível completar a operação solicitada. O incidente foi registrado com segurança no log de auditoria do sistema.
    </p>

    <!-- Caixa de auxílio para configuração inicial do banco de dados MySQL -->
    <div class="mt-6 bg-white p-6 rounded-2xl border border-slate-200 text-left max-w-lg w-full text-xs space-y-3 shadow-sm">
        <h4 class="font-bold text-slate-800 text-sm flex items-center">
            <svg class="w-4 h-4 mr-1.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
            Dica de Inicialização do Banco de Dados:
        </h4>
        <p class="text-slate-600">Se este erro for decorrente de conexão com o banco de dados MySQL:</p>
        <ol class="list-decimal list-inside space-y-1.5 text-slate-600 pl-1">
            <li>Certifique-se de que o serviço <strong>MySQL</strong> está ativo no seu servidor (ex: XAMPP, Laragon, WAMP ou Docker).</li>
            <li>Importe o script <code class="bg-slate-100 text-slate-800 px-1 py-0.5 rounded font-mono font-bold">database/schema.sql</code> para criar o banco de dados <code class="font-mono">sos_estudante</code>.</li>
            <li>Opcionalmente, importe o script <code class="bg-slate-100 text-slate-800 px-1 py-0.5 rounded font-mono font-bold">database/seed.sql</code> para carregar dados de demonstração.</li>
            <li>Verifique se as credenciais do seu arquivo <code class="font-mono">.env</code> coincidem com seu ambiente local.</li>
        </ol>
    </div>

    <div class="mt-6 flex space-x-3">
        <a href="index.php?route=dashboard" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow transition">
            Tentar Novamente
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
