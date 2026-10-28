<header class="bg-white border-b border-slate-200 sticky top-0 z-30">
    <div class="px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <!-- Esquerda: Botão Menu Mobile & Logo -->
        <div class="flex items-center space-x-3">
            <button type="button" id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500" aria-label="Abrir menu de navegação">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="index.php?route=dashboard" class="flex items-center space-x-2.5 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-brand-500/20 group-hover:scale-105 transition transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <div>
                    <span class="text-lg font-bold bg-clip-text text-transparent bg-gradient-to-r from-brand-700 to-indigo-600">SOS Estudante</span>
                    <span class="hidden sm:inline-block ml-1.5 px-2 py-0.5 text-[10px] font-semibold bg-brand-50 text-brand-700 rounded-full border border-brand-200">Acadêmico</span>
                </div>
            </a>
        </div>

        <!-- Direita: Perfil do Estudante & Ações Rápidas -->
        <div class="flex items-center space-x-3 sm:space-x-4">
            <a href="index.php?route=profile" class="flex items-center space-x-3 p-1.5 rounded-xl hover:bg-slate-100 transition group">
                <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 font-bold flex items-center justify-center text-xs border border-brand-300">
                    <?= strtoupper(substr($user['nome'] ?? 'E', 0, 1)) ?>
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-xs font-semibold text-slate-800 leading-tight group-hover:text-brand-600 transition"><?= htmlspecialchars($user['nome'] ?? 'Estudante') ?></p>
                    <p class="text-[11px] text-slate-500 leading-tight truncate max-w-[140px]"><?= htmlspecialchars($user['curso'] ?? 'Estudante') ?></p>
                </div>
            </a>

            <div class="h-6 w-px bg-slate-200"></div>

            <a href="index.php?route=logout" class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition" title="Sair do sistema" aria-label="Sair">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </a>
        </div>
    </div>
</header>
