<form action="#" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label">Ders Öğrenme Çıktıları</label>
        <textarea class="form-control" rows="3" placeholder="Bu dersi alan öğrenci şunları yapabilir hale gelir..."></textarea>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 mb-3">
            <label class="form-label">Öğretim Yöntemi</label>
            <select class="form-select">
                <option selected disabled>Seçiniz</option>
                <option>Anlatım</option>
                <option>Tartışma</option>
                <option>Proje Tabanlı Öğrenme</option>
                <option>Laboratuvar Uygulaması</option>
            </select>
        </div>

        <div class="col-12 col-md-6 mb-3">
            <label class="form-label">Ölçme Değerlendirme Yöntemi</label>
            <select class="form-select">
                <option selected disabled>Seçiniz</option>
                <option>Yazılı Sınav</option>
                <option>Ödev</option>
                <option>Proje</option>
                <option>Sözlü Sınav</option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Dersin Program Çıktılarına Katkısı (1-5)</label>
        <input type="number" class="form-control" min="1" max="5" placeholder="Örn: 4">
    </div>

    <div class="mb-3">
        <label class="form-label">Ders İzlencesi (Dosya)</label>
        <input type="file" class="form-control">
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-primary-light">Kaydet</button>
    </div>
</form>