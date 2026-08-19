document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.getElementById('excel_file');
    const importBtn = document.getElementById('importBtn');
    const fileError = document.getElementById('file-error');
    
    if (!fileInput || !importBtn) return;

    const validExts = ['.xlsx', '.xls', '.csv'];

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        fileError.style.display = 'none';
        fileError.textContent = '';
        importBtn.disabled = true;

        if (!file) return;

        // Uzantı kontrolü
        const ext = '.' + file.name.split('.').pop().toLowerCase();
        if (!validExts.includes(ext)) {
            fileError.textContent = 'Geçersiz dosya formatı! Sadece .xlsx, .xls veya .csv yükleyebilirsiniz.';
            fileError.style.display = 'block';
            this.value = '';
            return;
        }

        // Boyut kontrolü (2MB)
        if (file.size > 2 * 1024 * 1024) {
            fileError.textContent = 'Dosya boyutu 2MB\'ı aşamaz!';
            fileError.style.display = 'block';
            this.value = '';
            return;
        }

        // Her şey tamam, butonu aktif et
        importBtn.disabled = false;
    });
});