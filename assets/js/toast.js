/**
 * SOS-Estudante - Gerenciador de Notificações Toast
 * Atende às diretrizes de interface das seções 4.5 e 9 dos requisitos.
 */

const Toast = {
    container: null,

    init() {
        if (!this.container) {
            this.container = document.getElementById('toast-container');
            if (!this.container) {
                this.container = document.createElement('div');
                this.container.id = 'toast-container';
                this.container.className = 'fixed top-4 right-4 z-50 flex flex-col space-y-3 max-w-sm w-full px-4 sm:px-0 pointer-events-none';
                document.body.appendChild(this.container);
            }
        }
    },

    /**
     * Exibe um Toast
     * @param {string} type 'success' | 'error' | 'warning' | 'info'
     * @param {string} message Texto da notificação
     * @param {string} title Título opcional
     * @param {number} duration Duração em milissegundos
     */
    show(type = 'info', message = '', title = '', duration = 4500) {
        this.init();

        const config = {
            success: {
                bg: 'bg-emerald-50 border-emerald-500 text-emerald-800',
                iconColor: 'text-emerald-500',
                defaultTitle: 'Sucesso',
                iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'
            },
            error: {
                bg: 'bg-rose-50 border-rose-500 text-rose-800',
                iconColor: 'text-rose-500',
                defaultTitle: 'Atenção / Erro',
                iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
            },
            warning: {
                bg: 'bg-amber-50 border-amber-500 text-amber-800',
                iconColor: 'text-amber-500',
                defaultTitle: 'Aviso',
                iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
            },
            info: {
                bg: 'bg-indigo-50 border-indigo-500 text-indigo-800',
                iconColor: 'text-indigo-500',
                defaultTitle: 'Informação',
                iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
            }
        };

        const theme = config[type] || config.info;
        const toastTitle = title || theme.defaultTitle;

        const toast = document.createElement('div');
        toast.className = `pointer-events-auto border-l-4 shadow-lg rounded-r-lg p-4 flex items-start space-x-3 transition-all duration-300 toast-enter ${theme.bg}`;
        toast.setAttribute('role', 'alert');

        toast.innerHTML = `
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 ${theme.iconColor}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    ${theme.iconSvg}
                </svg>
            </div>
            <div class="flex-1 text-sm">
                <p class="font-semibold">${toastTitle}</p>
                <p class="mt-0.5 text-xs sm:text-sm opacity-90">${message}</p>
            </div>
            <button type="button" class="flex-shrink-0 text-gray-400 hover:text-gray-600 focus:outline-none p-1 rounded transition" aria-label="Fechar notificação">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;

        const closeBtn = toast.querySelector('button');
        const removeToast = () => {
            toast.classList.remove('toast-enter');
            toast.classList.add('toast-leave');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 250);
        };

        closeBtn.addEventListener('click', removeToast);

        if (duration > 0) {
            setTimeout(removeToast, duration);
        }

        this.container.appendChild(toast);
    },

    success(message, title = '') {
        this.show('success', message, title);
    },

    error(message, title = '') {
        this.show('error', message, title);
    },

    warning(message, title = '') {
        this.show('warning', message, title);
    },

    info(message, title = '') {
        this.show('info', message, title);
    }
};

window.Toast = Toast;
