document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('ktun-sidebar');
    const main = document.getElementById('ktun-main');

    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function(event) {
            event.preventDefault(); 
            
            // Hem body hem de element sınıflarını toggle yapıyoruz
            document.body.classList.toggle('sidebar-collapsed');
            if (sidebar) sidebar.classList.toggle('collapsed');
            if (main) main.classList.toggle('expanded');
        });
    }
});