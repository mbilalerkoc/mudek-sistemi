{{-- partials/alerts.blade.php --}}
{{--
    Kullanım: @include('partials.alerts')
    Desteklenen session'lar:
        session('success')         → yeşil
        session('error')           → kırmızı
        session('warning')         → sarı
        session('info')            → mavi
        session('import_errors')   → kırmızı, liste
        session('import_imported') + session('import_skipped') → sarı, kısmi içe aktarma
    Desteklenen: $errors (validation hataları) → kırmızı, liste
--}}

{{-- BAŞARI --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- HATA --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- UYARI --}}
@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- BİLGİ --}}
@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- VALIDATION HATALARI --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle me-2"></i>
        <strong>Lütfen aşağıdaki hataları düzeltin:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- İÇE AKTARMA HATALARI --}}
@if (session('import_errors'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle me-2"></i>
        <strong>İçe aktarma sırasında hatalar oluştu:</strong>
        <ul class="mb-0 mt-2">
            @foreach (session('import_errors') as $hata)
                <li>{{ $hata }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- KISMİ İÇE AKTARMA --}}
@if (session('import_imported') !== null)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Kısmi içe aktarma:</strong>
        {{ session('import_imported') }} öğrenci eklendi,
        {{ session('import_skipped') }} öğrenci atlandı.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif