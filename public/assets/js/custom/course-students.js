document.addEventListener("DOMContentLoaded", function () {
    // Sol: Kayıtlı öğrenciler için tümünü seç/kaldır
    const selectAllKayitli = document.getElementById('selectAllKayitli');
    if (selectAllKayitli) {
        selectAllKayitli.addEventListener('change', function () {
            document.querySelectorAll('.kayitli-check').forEach(cb => cb.checked = this.checked);
        });
    }

    // Sağ: Kayıtsız öğrenciler için tümünü seç/kaldır
    const selectAllKayitsiz = document.getElementById('selectAllKayitsiz');
    if (selectAllKayitsiz) {
        selectAllKayitsiz.addEventListener('change', function () {
            document.querySelectorAll('.kayitsiz-check').forEach(cb => cb.checked = this.checked);
        });
    }
});