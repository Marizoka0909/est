<?php
$pageTitle = 'Criar Conta';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-indigo-50/30 to-brand-50/40">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center px-4">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-500 text-white shadow-xl shadow-brand-500/25 mb-4">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Criar Conta</h1>
        <p class="mt-2 text-sm text-slate-600">Junte-se ao SOS Estudante e organize suas matérias</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 shadow-xl shadow-slate-200/50 rounded-2xl sm:px-10 border border-slate-100">
            <form action="index.php?route=register" method="POST" class="space-y-4">
                <?= CsrfMiddleware::field() ?>

                <div>
                    <label for="nome" class="block text-sm font-semibold text-slate-700">Nome Completo *</label>
                    <input id="nome" name="nome" type="text" required
                           value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                           placeholder="Ex: João da Silva"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm">
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">E-mail Acadêmico *</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           placeholder="seu.email@exemplo.com"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="instituicao" class="block text-sm font-semibold text-slate-700">Instituição</label>
                        <input id="instituicao" name="instituicao" type="text"
                               value="<?= htmlspecialchars($_POST['instituicao'] ?? '') ?>"
                               placeholder="Ex: UFMG, USP, PUC"
                               class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm">
                    </div>
                    <div>
                        <label for="curso" class="block text-sm font-semibold text-slate-700">Curso</label>
                        <input id="curso" name="curso" type="text"
                               value="<?= htmlspecialchars($_POST['curso'] ?? '') ?>"
                               placeholder="Ex: Engenharia, Direito"
                               class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm">
                    </div>
                </div>

                <div>
                    <label for="senha" class="block text-sm font-semibold text-slate-700">Senha (mínimo 6 caracteres) *</label>
                    <input id="senha" name="senha" type="password" required minlength="6"
                           placeholder="••••••••"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm">
                </div>

                <div>
                    <label for="confirma_senha" class="block text-sm font-semibold text-slate-700">Confirmar Senha *</label>
                    <input id="confirma_senha" name="confirma_senha" type="password" required minlength="6"
                           placeholder="••••••••"
                           class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition duration-150">
                        Finalizar Cadastro
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                Já possui uma conta?
                <a href="index.php?route=login" class="font-semibold text-brand-600 hover:text-brand-500 transition ml-1">
                    Entrar agora
                </a>
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
