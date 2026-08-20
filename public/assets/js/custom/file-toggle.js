// public/assets/js/custom/file-toggle.js

// 1. Dropdowndan "Değiştir"e basıldığında çalışır
function toggleFileInput(studentId) {
    document.getElementById('file-view-' + studentId).classList.add('d-none');
    document.getElementById('file-input-' + studentId).classList.remove('d-none');
    
    const deleteFlag = document.getElementById('delete-flag-' + studentId);
    if(deleteFlag) deleteFlag.value = '0';
}

// 2. Dropdowndan "Sil"e basıldığında çalışır (Onay ekranını gösterir)
function markForDeletion(studentId) {
    document.getElementById('file-view-' + studentId).classList.add('d-none');
    document.getElementById('file-delete-' + studentId).classList.remove('d-none');
}

// 3. Kırmızı Onay (Dosyayı Kaldır) butonuna basıldığında çalışır
function confirmDeletion(studentId) {
    document.getElementById('file-delete-' + studentId).classList.add('d-none');
    document.getElementById('file-input-' + studentId).classList.remove('d-none');
    
    // Silme bayrağını 1 yap
    document.getElementById('delete-flag-' + studentId).value = '1'; 

    // YENİ: Durum etiketini "Silinecek" olarak güncelle
    let badge = document.getElementById('status-badge-' + studentId);
    if (badge) {
        badge.className = 'badge bg-danger'; // Kırmızı yap
        badge.innerHTML = 'Silinecek';
    }
}

// 4. Onay ekranından "Vazgeç"ilirse çalışır
function cancelDeletion(studentId) {
    document.getElementById('file-delete-' + studentId).classList.add('d-none');
    document.getElementById('file-view-' + studentId).classList.remove('d-none');
}

// 5. Input ekranındayken "İptal Et"e basılırsa orijinal görünüme döner
function cancelInput(studentId) {
    document.getElementById('file-input-' + studentId).classList.add('d-none');
    document.getElementById('file-view-' + studentId).classList.remove('d-none');
    
    document.getElementById('delete-flag-' + studentId).value = '0';
    const fileInput = document.getElementById('file-input-' + studentId).querySelector('input[type="file"]');
    if(fileInput) fileInput.value = '';

    // YENİ: Durum etiketini tekrar eski haline "Girildi"ye çevir
    let badge = document.getElementById('status-badge-' + studentId);
    if (badge) {
        badge.className = 'badge bg-success'; // Yeşil yap
        badge.innerHTML = 'Girildi';
    }
}