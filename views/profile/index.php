<?php
$pageTitle = 'Meu Perfil';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';
?>

<div class="flex-1 flex overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
        <!-- Header da Página -->
        <div class="mb-6">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Meu Perfil</h1>
            <p class="text-sm text-slate-500 mt-1">Gerencie seus dados pessoais, instituição acadêmica e credenciais de acesso</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna Esquerda: Cartão Resumo -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 text-center">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-brand-600 to-indigo-500 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-md shadow-brand-500/20 mb-4">
                        <?= strtoupper(substr($userProfile['nome'] ?? 'E', 0, 1)) ?>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900"><?= htmlspecialchars($userProfile['nome'] ?? 'Estudante') ?></h2>
                    <p class="text-xs text-brand-600 font-semibold mt-0.5"><?= htmlspecialchars($userProfile['email'] ?? '') ?></p>

                    <div class="mt-4 pt-4 border-t border-slate-100 text-left space-y-2 text-xs text-slate-600">
                        <?php if (!empty($userProfile['curso'])): ?>
                            <p><span class="font-bold text-slate-700">Curso:</span> <?= htmlspecialchars($userProfile['curso']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($userProfile['instituicao'])): ?>
                            <p><span class="font-bold text-slate-700">Instituição:</span> <?= htmlspecialchars($userProfile['instituicao']) ?></p>
                        <?php endif; ?>
                        <p><span class="font-bold text-slate-700">Membro desde:</span> <?= date('d/m/Y', strtotime($userProfile['created_at'])) ?></p>
                    </div>
                </div>

                <!-- Card de Segurança da Informação -->
                <div class="bg-indigo-50/60 rounded-2xl border border-indigo-100 p-5 text-xs text-indigo-950">
                    <h4 class="font-bold flex items-center mb-1 text-brand-700">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Segurança & Privacidade
                    </h4>
                    <p class="text-slate-600 leading-relaxed mt-2">
                        Suas senhas são criptografadas com hash nativo e nunca armazenadas em texto puro. Todas as alterações geram registros de auditoria auditáveis.
                    </p>
                </div>
            </div>

            <!-- Coluna Direita: Formulários -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Formulário de Informações Pessoais -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-7">
                    <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">Informações Básicas</h3>

                    <form action="index.php?route=profile/update" method="POST" class="mt-5 space-y-4">
                        <?= CsrfMiddleware::field() ?>

                        <div>
                            <label for="prof_nome" class="block text-xs font-bold uppercase text-slate-700">Nome Completo *</label>
                            <input id="prof_nome" name="nome" type="text" required
                                   value="<?= htmlspecialchars($userProfile['nome'] ?? '') ?>"
                                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                        </div>

                        <div>
                            <label for="prof_email" class="block text-xs font-bold uppercase text-slate-700">E-mail Acadêmico *</label>
                            <input id="prof_email" name="email" type="email" required
                                   value="<?= htmlspecialchars($userProfile['email'] ?? '') ?>"
                                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="prof_instituicao" class="block text-xs font-bold uppercase text-slate-700">Instituição de Ensino</label>
                                <input id="prof_instituicao" name="instituicao" type="text"
                                       value="<?= htmlspecialchars($userProfile['instituicao'] ?? '') ?>"
                                       placeholder="Ex: Universidade Federal"
                                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                            </div>
                            <div>
                                <label for="prof_curso" class="block text-xs font-bold uppercase text-slate-700">Curso / Graduação</label>
                                <input id="prof_curso" name="curso" type="text"
                                       value="<?= htmlspecialchars($userProfile['curso'] ?? '') ?>"
                                       placeholder="Ex: Ciência da Computação"
                                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                            </div>
                        </div>

                        <div class="pt-3 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm shadow transition">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Formulário de Alteração de Senha -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-7">
                    <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">Alterar Senha de Acesso</h3>

                    <form action="index.php?route=profile/password" method="POST" class="mt-5 space-y-4">
                        <?= CsrfMiddleware::field() ?>

                        <div>
                            <label for="senha_atual" class="block text-xs font-bold uppercase text-slate-700">Senha Atual *</label>
                            <input id="senha_atual" name="senha_atual" type="password" required
                                   placeholder="••••••••"
                                   class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nova_senha" class="block text-xs font-bold uppercase text-slate-700">Nova Senha (mínimo 6) *</label>
                                <input id="nova_senha" name="nova_senha" type="password" required minlength="6"
                                       placeholder="••••••••"
                                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                            </div>
                            <div>
                                <label for="confirma_nova_senha" class="block text-xs font-bold uppercase text-slate-700">Confirmar Nova Senha *</label>
                                <input id="confirma_nova_senha" name="confirma_nova_senha" type="password" required minlength="6"
                                       placeholder="••••••••"
                                       class="mt-1 block w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-brand-500">
                            </div>
                        </div>

                        <div class="pt-3 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-semibold text-sm shadow transition">
                                Atualizar Senha
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
