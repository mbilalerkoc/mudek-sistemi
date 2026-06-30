<form action="{{ route('ders.katki.kaydet') }}" method="POST">
    @csrf
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @forelse ($dersler as $ders)
        <div class="mb-3">
            <label class="form-label fw-bold">
                {{ $ders->ders_adi }} Program Çıktılarına Katkısı (1-5)
            </label>
            </p>
            <label class="form-label fw-bold">
                Vize
            </label>
            <input type="number" name="katkilar[{{ $ders->id }}]"
                class="form-control @error('katkilar.' . $ders->id) is-invalid @enderror" min="1" max="5"
                placeholder="Örn: 4" value="{{ old('katkilar.' . $ders->id, $ders->mevcut_katki ?? '') }}">
            </p>
            <label class="form-label fw-bold">
                Final
            </label>
            <input type="number" name="katkilar[{{ $ders->id }}]"
                class="form-control @error('katkilar.' . $ders->id) is-invalid @enderror" min="1" max="5"
                placeholder="Örn: 4" value="{{ old('katkilar.' . $ders->id, $ders->mevcut_katki ?? '') }}">

            @error('katkilar.' . $ders->id)
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    @empty
        <div class="alert alert-warning">
            Şu an sistemde ders bulunmuyor.
        </div>
    @endforelse

    <div class="text-end mt-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-primary-light">Kaydet</button>
    </div>
</form>
