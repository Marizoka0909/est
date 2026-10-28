/**
 * SOS-Estudante - Script Principal da Aplicação
 * Gerenciamento de menu mobile, interceptação de exclusões e interações
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Menu Mobile (Drawer)
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const mobileSidebarClose = document.getElementById('mobile-sidebar-close');
    const mobileSidebarBackdrop = document.getElementById('mobile-sidebar-backdrop');

    if (mobileMenuBtn && mobileSidebar) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileSidebar.classList.remove('hidden');
        });
    }

    const closeMobileMenu = () => {
        if (mobileSidebar) {
            mobileSidebar.classList.add('hidden');
        }
    };

    if (mobileSidebarClose) mobileSidebarClose.addEventListener('click', closeMobileMenu);
    if (mobileSidebarBackdrop) mobileSidebarBackdrop.addEventListener('click', closeMobileMenu);

    // 2. Interceptador de formulários de exclusão com data-confirm
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const message = form.getAttribute('data-confirm') || 'Tem certeza que deseja excluir este item permanentemente?';
            const title = form.getAttribute('data-confirm-title') || 'Confirmar Exclusão';

            Modal.confirm({
                title: title,
                message: message,
                confirmText: 'Sim, excluir',
                cancelText: 'Cancelar',
                isDanger: true,
                onConfirm: () => {
                    form.submit();
                }
            });
        });
    });

    // 3. Fechamento de modais via botões data-close-modal
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modalId = btn.getAttribute('data-close-modal');
            Modal.close(modalId);
        });
    });

    // 4. Fechamento ao clicar no backdrop de qualquer modal
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', (e) => {
            const modal = e.target.closest('.modal-wrapper') || e.target.closest('[id$="-modal"]');
            if (modal) {
                Modal.close(modal.id);
            }
        });
    });
});
