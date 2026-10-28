    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col space-y-3 max-w-sm w-full px-4 sm:px-0 pointer-events-none"></div>

    <!-- Scripts da Aplicação -->
    <script src="assets/js/toast.js"></script>
    <script src="assets/js/modal.js"></script>
    <script src="assets/js/app.js"></script>

    <!-- Disparo Automático de Mensagens Flash em formato Toast -->
    <?php if (!empty($flash)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Toast.show(
                '<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?>', 
                '<?= addslashes(htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8')) ?>'
            );
        });
    </script>
    <?php endif; ?>
</body>
</html>
