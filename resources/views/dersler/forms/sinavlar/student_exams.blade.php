@extends('layouts.app')

@section('title', $exam->course->name . ' — ' . ucfirst($exam->exam_type) . ' Sınavı')

@section('content')

    @php
        $isAdmin = auth()->user()->role === 'super_admin';
        $derslerRoute = $isAdmin ? 'admin.dersler' : 'user.dersler';
        $detayRoute = $isAdmin ? 'admin.ders.detay' : 'user.ders.detay';
        $sinavlarRoute = $isAdmin ? 'admin.dersler.sinavlar' : 'user.dersler.sinavlar';
        $soruKaydetRoute = $isAdmin ? 'admin.sinavlar.soru.kaydet' : 'user.sinavlar.soru.kaydet';
        $soruSilRoute = $isAdmin ? 'admin.sinavlar.soru.sil' : 'user.sinavlar.soru.sil';
        $cevaplarRoute = $isAdmin ? 'admin.sinavlar.cevaplar' : 'user.sinavlar.cevaplar';

        $examTypeLabel = match ($exam->exam_type) {
            'midterm' => 'Vize',
            'final' => 'Final',
            'makeup' => 'Bütünleme',
            default => ucfirst($exam->exam_type),
        };
    @endphp

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $exam->course->name }} — {{ $examTypeLabel }} Sınavı</h3>
                    <p class="text-subtitle text-muted">
                        Sınav Tarihi:
                        {{ $exam->exam_date ? \Carbon\Carbon::parse($exam->exam_date)->format('d.m.Y') : 'Belirsiz' }}
                    </p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route($derslerRoute) }}">Dersler</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route($detayRoute, $exam->course->id) }}">{{ $exam->course->name }}</a></li>
                            <li class="breadcrumb-item active">{{ $examTypeLabel }} Sınavı</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section">

        {{-- BÖLÜM 1: SORULAR --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Sınav Soruları</h4>
                <div class="d-flex align-items-center gap-3">
                    {{-- Toplam puan --}}
                    <span class="badge bg-ktun-soft px-3 py-2">
                        Toplam: {{ $exam->questions->sum('score') }} Puan
                    </span>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#soruEkleForm">
                        <i class="bi bi-plus-lg me-1"></i> Soru Ekle
                    </button>
                </div>
            </div>

            <div class="card-body">
                {{-- Soru Ekleme Formu --}}
                <div class="collapse mb-4" id="soruEkleForm">
                    <form action="{{ route($soruKaydetRoute, $exam->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-md-5">
                                <label class="form-label fw-semibold">Soru Metni <small
                                        class="text-muted">(Opsiyonel)</small></label>
                                <input type="text" name="question_text" class="form-control"
                                    placeholder="Örn: Aşağıdaki kodu açıklayınız...">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold">Soru Dosyası <small class="text-muted">(PDF,
                                        Görsel)</small></label>
                                <input type="file" name="file" class="form-control file-size-check"
                                    accept=".pdf,.jpg,.jpeg,.png" data-max-size="5" data-warning-id="soru-dosya-uyari">
                                <div id="soru-dosya-uyari" class="alert alert-danger mt-1 d-none">
                                    Dosya 5MB'dan büyük olamaz.
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold">Soru Puanı <span class="text-danger">*</span></label>
                                <input type="number" name="score" class="form-control" min="0" step="0.5"
                                    required placeholder="Örn: 10">
                            </div>
                            <div class="col-12 col-md-5 text-end">
                                <button type="button" class="btn btn-secondary me-2" data-bs-toggle="collapse"
                                    data-bs-target="#soruEkleForm">
                                    İptal
                                </button>
                                <button type="submit" class="btn btn-primary-light px-4">
                                    <i class="bi bi-check-lg me-1"></i> Soruyu Ekle
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr>
                </div>

                {{-- Soru Listesi --}}
                @forelse($exam->questions as $index => $question)
                    <div class="d-flex align-items-center justify-content-between p-3 mb-2 bg-light rounded">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-secondary">#{{ $index + 1 }}</span>
                            @if ($question->file)
                                <a href="{{ asset('storage/' . $question->file) }}" target="_blank" class="text-primary">
                                    <i class="bi bi-paperclip me-1"></i>
                                    {{ basename($question->file) }}
                                </a>
                            @else
                                <span class="text-muted">Dosya eklenmemiş</span>
                            @endif
                        </div>

                        {{-- Puan ve Silme Butonu Yanyana --}}
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-ktun-soft px-3 py-2 text-dark">{{ number_format($question->score, 2) }}
                                Puan</span>

                            @php
                                $isAdmin = auth()->user()->role === 'super_admin';
                                $soruSilRoute = $isAdmin ? 'admin.sinavlar.soru.sil' : 'user.sinavlar.soru.sil';
                            @endphp

                            <form action="{{ route($soruSilRoute, $question->id) }}" method="POST" class="m-0"
                                onsubmit="return confirm('Bu soruyu silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger px-2 py-1" title="Soruyu Sil">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Bu sınava henüz soru eklenmemiş.
                    </p>
                @endforelse
            </div>
        </div>

        @php
            $isAdmin = auth()->user()->role === 'super_admin';

            // Rol durumuna göre cevaplar rotasını belirliyoruz
            $cevaplarRoute = $isAdmin ? 'admin.sinavlar.cevaplar' : 'user.sinavlar.cevaplar';
        @endphp

        {{-- BÖLÜM 2: ÖĞRENCİLERİN CEVAPLARI VE NOTLANDIRMA --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 fw-bold">Öğrenci Cevapları ve Notlandırma</h5>
                    <p class="text-muted mb-0">Her öğrencinin her soruya verdiği puanı girin, toplamlar otomatik hesaplanır.
                    </p>
                </div>
                <a href="{{ route($cevaplarRoute, $exam->id) }}" class="btn btn-primary-light px-4">
                    <i class="bi bi-check2-square me-1"></i> Cevapları Gir
                </a>
            </div>
        </div>

    </section>

@endsection
