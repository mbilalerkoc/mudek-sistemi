function initFileSizeCheck() {
    const fileInputs = document.querySelectorAll('.file-size-check');
    
    fileInputs.forEach(function (input) {
        // Olayın iki kez aynı inputa eklenmesini engelle
        if (input.dataset.listenerAttached) return;
        input.dataset.listenerAttached = 'true';

        input.addEventListener('change', function () {
            const warningId = this.getAttribute('data-warning-id');
            const warningElement = warningId ? document.getElementById(warningId) : null;

            // Dosya seçimi iptal edildiyse uyarıyı gizle
            if (!this.files || this.files.length === 0) {
                if (warningElement) {
                    warningElement.classList.add('d-none');
                    warningElement.classList.remove('d-block');
                }
                return;
            }

            const file = this.files[0];
            const maxSizeMB = parseFloat(this.getAttribute('data-max-size')) || 10;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;

            if (file.size > maxSizeBytes) {
                // HATA: Dosya büyük
                this.value = ''; 
                
                if (warningElement) {
                    warningElement.classList.remove('d-none');
                    warningElement.classList.add('d-block'); 
                } else {
                    alert('Hata: Dosya boyutu ' + maxSizeMB + 'MB sınırını aşıyor!');
                }
            } else {
                if (warningElement) {
                    warningElement.classList.add('d-none');
                    warningElement.classList.remove('d-block');
                }
            }
        });
    });
}
document.addEventListener('DOMContentLoaded', initFileSizeCheck);
initFileSizeCheck();