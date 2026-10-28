<?php
$pageTitle = 'Entrar';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-indigo-50/30 to-brand-50/40">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center px-4">
        <!-- Logo -->
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-500 text-white shadow-xl shadow-brand-500/25 mb-4">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">SOS Estudante</h1>
        <p class="mt-2 text-sm text-slate-600">Acesse sua conta para organizar sua vida acadêmica</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 shadow-xl shadow-slate-200/50 rounded-2xl sm:px-10 border border-slate-100">
            <form action="index.php?route=login" method="POST" class="space-y-5">
                <?= CsrfMiddleware::field() ?>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">E-mail Acadêmico</label>
                    <div class="mt-1 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               placeholder="seu.email@exemplo.com"
                               class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm">
                    </div>
                </div>

                <div>
                    <label for="senha" class="block text-sm font-semibold text-slate-700">Senha</label>
                    <div class="mt-1 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="senha" name="senha" type="password" autocomplete="current-password" required
                               placeholder="••••••••"
                               class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm">
                    </div>
                </div>

                <div class="pt-1">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition duration-150">
                        Entrar no Sistema
                    </button>
                </div>
            </form>

            <!-- Card de Credenciais de Demonstração -->
            <div class="mt-6 pt-6 border-t border-slate-100">
                <div class="bg-brand-50/60 rounded-xl p-3.5 border border-brand-100/80 text-xs text-brand-900">
                    <p class="font-bold flex items-center mb-1">
                        <svg class="w-4 h-4 mr-1.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Conta de Teste Pré-configurada:
                    </p>
                    <p class="text-slate-600">E-mail: <code class="font-mono bg-white px-1.5 py-0.5 rounded text-brand-700 font-semibold border border-brand-200">aluno@exemplo.com</code></p>
                    <p class="text-slate-600 mt-1">Senha: <code class="font-mono bg-white px-1.5 py-0.5 rounded text-brand-700 font-semibold border border-brand-200">password123</code></p>
                </div>
            </div>

            <!-- Link para Cadastro -->
            <p class="mt-6 text-center text-sm text-slate-600">
                Ainda não tem conta?
                <a href="index.php?route=register" class="font-semibold text-brand-600 hover:text-brand-500 transition ml-1">
                    Cadastre-se gratuitamente
                </a>
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
