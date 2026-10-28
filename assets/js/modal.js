/**
 * SOS-Estudante - Gerenciador de Modais Customizados
 * Atende estritamente às seções 4.5 e 9 dos requisitos:
 * - Substituição obrigatória de alert(), confirm() nativos por modais personalizados
 * - Modais acessíveis com navegação por teclado e foco gerenciado
 */

const Modal = {
    activeModal: null,

    init() {
        // Fechar com a tecla ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activeModal) {
                this.close(this.activeModal);
            }
        });
    },

    /**
     * Abre um modal pelo ID
     */
    open(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        this.activeModal = modalId;

        // Animar backdrop e container
        const backdrop = modal.querySelector('.modal-backdrop');
        const container = modal.querySelector('.modal-container');
        if (backdrop) backdrop.classList.add('modal-backdrop-anim');
        if (container) container.classList.add('modal-content-anim');

        // Foco inicial no primeiro input ou botão
        const firstFocusable = modal.querySelector('input:not([type="hidden"]), select, textarea, button:not([data-close-modal])');
        if (firstFocusable) {
            setTimeout(() => firstFocusable.focus(), 50);
        }
    },

    /**
     * Fecha o modal
     */
    close(modalId) {
        const id = modalId || this.activeModal;
        const modal = document.getElementById(id);
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        if (this.activeModal === id) {
            this.activeModal = null;
        }
    },

    /**
     * Abre um modal de confirmação moderno (substitui o confirm() nativo)
     * @param {Object} options { title, message, confirmText, cancelText, isDanger, onConfirm }
     */
    confirm(options = {}) {
        const {
            title = 'Confirmação',
            message = 'Tem certeza que deseja executar esta ação?',
            confirmText = 'Confirmar',
            cancelText = 'Cancelar',
            isDanger = true,
            onConfirm = null
        } = options;

        let confirmModal = document.getElementById('global-confirm-modal');
        if (!confirmModal) {
            confirmModal = document.createElement('div');
            confirmModal.id = 'global-confirm-modal';
            confirmModal.className = 'fixed inset-0 z-50 hidden items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm';
            confirmModal.innerHTML = `
                <div class="modal-backdrop fixed inset-0"></div>
                <div class="modal-container relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 z-10">
                    <div class="flex items-center space-x-3">
                        <div id="confirm-icon-wrap" class="p-2.5 rounded-xl ${isDanger ? 'bg-rose-100 text-rose-600' : 'bg-indigo-100 text-indigo-600'}">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 id="confirm-title" class="text-lg font-bold text-gray-900">${title}</h3>
                    </div>
                    <p id="confirm-message" class="mt-3 text-sm text-gray-600 leading-relaxed">${message}</p>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" id="confirm-cancel-btn" class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium transition">
                            ${cancelText}
                        </button>
                        <button type="button" id="confirm-action-btn" class="px-4 py-2 rounded-xl text-white text-sm font-medium shadow-sm transition ${isDanger ? 'bg-rose-600 hover:bg-rose-700' : 'bg-indigo-600 hover:bg-indigo-700'}">
                            ${confirmText}
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(confirmModal);
        } else {
            document.getElementById('confirm-title').innerText = title;
            document.getElementById('confirm-message').innerText = message;
            document.getElementById('confirm-cancel-btn').innerText = cancelText;
            const actionBtn = document.getElementById('confirm-action-btn');
            actionBtn.innerText = confirmText;
            actionBtn.className = `px-4 py-2 rounded-xl text-white text-sm font-medium shadow-sm transition ${isDanger ? 'bg-rose-600 hover:bg-rose-700' : 'bg-indigo-600 hover:bg-indigo-700'}`;
            const iconWrap = document.getElementById('confirm-icon-wrap');
            iconWrap.className = `p-2.5 rounded-xl ${isDanger ? 'bg-rose-100 text-rose-600' : 'bg-indigo-100 text-indigo-600'}`;
        }

        const cancelBtn = confirmModal.querySelector('#confirm-cancel-btn');
        const actionBtn = confirmModal.querySelector('#confirm-action-btn');
        const backdrop = confirmModal.querySelector('.modal-backdrop');

        const cleanup = () => {
            this.close('global-confirm-modal');
        };

        cancelBtn.onclick = cleanup;
        backdrop.onclick = cleanup;

        actionBtn.onclick = () => {
            cleanup();
            if (typeof onConfirm === 'function') {
                onConfirm();
            }
        };

        this.open('global-confirm-modal');
    }
};

document.addEventListener('DOMContentLoaded', () => {
    Modal.init();
});

window.Modal = Modal;
