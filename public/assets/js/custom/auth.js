document.addEventListener("DOMContentLoaded", function () {
    // 1. Şifre Göster / Gizle
    const togglePassword = document.getElementById('togglePassword');
    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (!passwordInput || !icon) return;

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    }

    // 2. Şifremi Unuttum Modali Yönetimi
    const modal = document.getElementById('forgotPasswordModal');
    const openBtn = document.getElementById('openForgotModal');
    const closeBtn = document.getElementById('closeForgotModalBtn');
    const cancelBtn = document.getElementById('cancelForgotModalBtn');

    if (modal && openBtn) {
        function openModal(e) {
            e.preventDefault();
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
        }

        function closeModal() {
            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');
        }

        openBtn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
});