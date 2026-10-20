document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');

    menuToggle?.addEventListener('click', () => {
        sidebar?.classList.toggle('sidebar-open');
    });

    document.addEventListener('click', (event) => {
        if (sidebar && menuToggle && window.innerWidth <= 800 &&
            !sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
            sidebar.classList.remove('sidebar-open');
        }
    });

    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.togglePassword);
            if (!input) return;
            const reveal = input.type === 'password';
            input.type = reveal ? 'text' : 'password';
            const icon = button.querySelector('.material-symbols-outlined');
            if (icon) icon.textContent = reveal ? 'visibility_off' : 'visibility';
        });
    });

    document.querySelectorAll('[data-confirm]').forEach((element) => {
        element.addEventListener('submit', (event) => {
            if (!window.confirm(element.dataset.confirm || 'Apakah Anda yakin?')) {
                event.preventDefault();
            }
        });
        element.addEventListener('click', (event) => {
            if (element.tagName === 'A' && !window.confirm(element.dataset.confirm || 'Apakah Anda yakin?')) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('form[data-validate]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
            }
        });
    });
});
