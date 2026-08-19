document.addEventListener("DOMContentLoaded", function () {
    const deleteModal = document.getElementById('deleteConfirmModal');
    if (!deleteModal) return;

    const deleteForm = document.getElementById('deleteForm');
    const closeDeleteBtn = document.getElementById('closeDeleteModalBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            deleteForm.action = url;
            
            deleteModal.style.display = 'block';
            deleteModal.classList.add('show');
            document.body.classList.add('modal-open');
        });
    });

    function closeDeleteModal() {
        deleteModal.style.display = 'none';
        deleteModal.classList.remove('show');
        document.body.classList.remove('modal-open');
    }

    if (closeDeleteBtn) closeDeleteBtn.addEventListener('click', closeDeleteModal);
    if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', closeDeleteModal);

    window.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });
});