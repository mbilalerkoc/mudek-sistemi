<form action="{{ route('ders.katki.kaydet') }}" method="POST">
    @csrf
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label fw-bold text-primary mb-3">
            {{ $seciliDers->ders_adi }} Program Çıktılarına Katkısı (1-5)
        </label>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Vize</label>
                <input type="number" name="vize_katki"
                    class="form-control @error('vize_katki') is-invalid @enderror" min="1" max="5"
                    placeholder="Örn: 4" value="{{ old('vize_katki', $seciliDers->mevcut_katki ?? '') }}">
                
                @error('vize_katki')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Final</label>
                <input type="number" name="final_katki"
                    class="form-control @error('final_katki') is-invalid @enderror" min="1" max="5"
                    placeholder="Örn: 4" value="{{ old('final_katki', $seciliDers->mevcut_katki ?? '') }}">
                
                @error('final_katki')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="text-end mt-4">
        <a href="{{ route('user.ders.detay', $seciliDers->id) }}" class="btn btn-secondary">İptal</a>
        <button type="submit" class="btn btn-primary-light">Kaydet</button>
    </div>
</form>