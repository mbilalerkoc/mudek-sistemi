@extends('layouts.app')
@section('title', $exam->course->name . ' — Cevap Görüntüleme')

@section('content')

    @php
        $isAdmin = auth()->user()->role === 'super_admin';
        $sorularRoute = $isAdmin ? 'admin.sinavlar.detay' : 'user.sinavlar.detay';
        $duzenleRoute = $isAdmin ? 'admin.sinavlar.cevaplar.duzenle' : 'user.sinavlar.cevaplar.duzenle';
        $importRoute = $isAdmin ? 'admin.sinavlar.cevaplar.import' : 'user.sinavlar.cevaplar.import';
        $ornekRoute = $isAdmin ? 'admin.sinavlar.ornek.kaydet' : 'user.sinavlar.ornek.kaydet';
        $sinavlarRoute = $isAdmin ? 'admin.dersler.sinavlar' : 'user.dersler.sinavlar';

        $examTypeLabel = match ($exam->exam_type) {
            'midterm' => 'Vize',
            'final' => 'Final',
            'makeup' => 'Bütünleme',
            default => ucfirst($exam->exam_type),
        };

        // Öğrencileri puana göre sırala
        $siraliOgrenciler = $exam->studentExams
            ->filter(fn($se) => $se->studentCourse?->student)
            ->sortByDesc('total_score')
            ->values();

        // Mevcut sınavdaki kayıtlı örnek kağıtları bulalım
        $bestExam = $exam->studentExams->first(fn($se) => $se->path && str_contains($se->path, '/best/'));
        $avgExam = $exam->studentExams->first(fn($se) => $se->path && str_contains($se->path, '/average/'));
        $worstExam = $exam->studentExams->first(fn($se) => $se->path && str_contains($se->path, '/worst/'));
    @endphp

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $exam->course->name }} — {{ $examTypeLabel }} Cevapları</h3>
                    <p class="text-subtitle text-muted">Salt okunur görünüm</p>
                </div>
            </div>
        </div>
    </div>

    <section class="section">

        {{-- ÖRNEK SINAV KAĞITLARI --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="bi bi-award me-2 text-primary"></i>
                    Örnek Sınav Kağıtları
                    <small class="text-muted fw-normal ms-2" style="font-size:0.8rem;">
                        Akreditasyon dosyaları için
                    </small>
                </h4>
                <a href="{{ route($duzenleRoute, $exam->id) }}" class="btn btn-primary-light px-4">
                    <i class="bi bi-pencil-square me-1"></i> Örnek Kağıtları Düzenle
                </a>
            </div>
            <div class="card-body">
                <div class="row g-3">

                    {{-- EN İYİ --}}
                    <div class="col-12 col-md-4">
                        <div class="card h-100 border-success">
                            <div
                                class="card-header bg-success bg-opacity-10 border-success d-flex align-items-center gap-2">
                                <i class="bi bi-trophy-fill text-success"></i>
                                <span class="fw-bold text-success">En İyi Kağıt</span>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                @if ($bestExam && $bestExam->studentCourse?->student)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <div class="rounded-circle bg-success bg-opacity-10 p-2">
                                                <i class="bi bi-person-fill text-success"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size:0.85rem;">
                                                    {{ $bestExam->studentCourse->student->name }}
                                                    {{ $bestExam->studentCourse->student->surname }}
                                                </div>
                                                <div class="text-muted" style="font-size:0.78rem;">
                                                    {{ $bestExam->studentCourse->student->student_no }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="badge bg-success">
                                                {{ $bestExam->total_score }} puan
                                            </span>
                                            <span class="text-muted" style="font-size:0.78rem;">
                                                <i class="bi bi-paperclip me-1"></i>
                                                {{ basename($bestExam->path) }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $bestExam->path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success w-100">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Kağıdı Görüntüle
                                    </a>
                                @else
                                    <div class="text-center text-muted py-3">
                                        <i class="bi bi-file-earmark-x fs-2 mb-2 d-block"></i>
                                        Henüz eklenmemiş
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ORTA --}}
                    <div class="col-12 col-md-4">
                        <div class="card h-100 border-warning">
                            <div
                                class="card-header bg-warning bg-opacity-10 border-warning d-flex align-items-center gap-2">
                                <i class="bi bi-dash-circle-fill text-warning"></i>
                                <span class="fw-bold text-warning">Orta Kağıt</span>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                @if ($avgExam && $avgExam->studentCourse?->student)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <div class="rounded-circle bg-warning bg-opacity-10 p-2">
                                                <i class="bi bi-person-fill text-warning"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size:0.85rem;">
                                                    {{ $avgExam->studentCourse->student->name }}
                                                    {{ $avgExam->studentCourse->student->surname }}
                                                </div>
                                                <div class="text-muted" style="font-size:0.78rem;">
                                                    {{ $avgExam->studentCourse->student->student_no }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="badge bg-warning text-dark">
                                                {{ $avgExam->total_score }} puan
                                            </span>
                                            <span class="text-muted" style="font-size:0.78rem;">
                                                <i class="bi bi-paperclip me-1"></i>
                                                {{ basename($avgExam->path) }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $avgExam->path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-warning text-dark w-100">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Kağıdı Görüntüle
                                    </a>
                                @else
                                    <div class="text-center text-muted py-3">
                                        <i class="bi bi-file-earmark-x fs-2 mb-2 d-block"></i>
                                        Henüz eklenmemiş
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- EN KÖTÜ --}}
                    <div class="col-12 col-md-4">
                        <div class="card h-100 border-danger">
                            <div class="card-header bg-danger bg-opacity-10 border-danger d-flex align-items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                <span class="fw-bold text-danger">En Kötü Kağıt</span>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                @if ($worstExam && $worstExam->studentCourse?->student)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <div class="rounded-circle bg-danger bg-opacity-10 p-2">
                                                <i class="bi bi-person-fill text-danger"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size:0.85rem;">
                                                    {{ $worstExam->studentCourse->student->name }}
                                                    {{ $worstExam->studentCourse->student->surname }}
                                                </div>
                                                <div class="text-muted" style="font-size:0.78rem;">
                                                    {{ $worstExam->studentCourse->student->student_no }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="badge bg-danger">
                                                {{ $worstExam->total_score }} puan
                                            </span>
                                            <span class="text-muted" style="font-size:0.78rem;">
                                                <i class="bi bi-paperclip me-1"></i>
                                                {{ basename($worstExam->path) }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $worstExam->path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-danger w-100">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Kağıdı Görüntüle
                                    </a>
                                @else
                                    <div class="text-center text-muted py-3">
                                        <i class="bi bi-file-earmark-x fs-2 mb-2 d-block"></i>
                                        Henüz eklenmemiş
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- CEVAP TABLOSU --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Öğrenci Cevapları</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel'den Aktar
                    </button>
                    <a href="{{ route($duzenleRoute, $exam->id) }}" class="btn btn-primary-light px-4">
                        <i class="bi bi-pencil-square me-1"></i> Cevapları Düzenle
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:100px;">Öğrenci No</th>
                                <th class="text-start">Ad Soyad</th>
                                <th style="width:90px;">Seviye</th>
                                @foreach ($exam->questions as $index => $question)
                                    <th style="width:80px;">
                                        S{{ $index + 1 }}<br>
                                        <small class="text-muted">({{ $question->score }}p)</small>
                                    </th>
                                @endforeach
                                <th style="width:100px;">Sınav</th>
                                <th style="width:100px;">Ödev</th>
                                <th style="width:100px;">Toplam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exam->studentExams as $studentExam)
                                @php
                                    $student = $studentExam->studentCourse->student ?? null;
                                    $existingAnswers = $studentExam->answers->keyBy('question_id');
                                @endphp
                                <tr>
                                    <td class="fw-bold">{{ $student->student_no ?? '-' }}</td>
                                    <td class="text-start">{{ $student->name ?? '' }} {{ $student->surname ?? '' }}</td>
                                    <td>
                                        @if ((int) $studentExam->level === 1)
                                            <span class="badge bg-success">İyi</span>
                                        @elseif((int) $studentExam->level === 2)
                                            <span class="badge bg-warning text-dark">Orta</span>
                                        @elseif((int) $studentExam->level === 3)
                                            <span class="badge bg-danger">Kötü</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    @foreach ($exam->questions as $question)
                                        @php $answer = $existingAnswers->get($question->id); @endphp
                                        <td>{{ $answer->score ?? '-' }}</td>
                                    @endforeach
                                    <td class="fw-bold text-primary">{{ $studentExam->exam_score ?? 0 }}</td>
                                    <td class="text-secondary">{{ $studentExam->assignment_score ?? 0 }}</td>
                                    <td class="fw-bold text-success">{{ $studentExam->total_score ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 6 + $exam->questions->count() }}" class="text-muted py-4">
                                        Bu sınava kayıtlı öğrenci bulunmamaktadır.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>

    {{-- Excel Import Modal --}}
    <div class="modal fade" id="importExcelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route($importRoute, $exam->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold">Optik Form Excel Yükle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <label class="form-label fw-semibold">Excel Dosyası</label>
                        <input type="file" name="excel_file" class="form-control file-size-check"
                            accept=".xlsx,.xls,.csv" required data-max-size="10" data-warning-id="excel-dosya-uyari">
                        <div id="excel-dosya-uyari" class="alert alert-danger mt-1 d-none">
                            Dosya 10MB'dan büyük olamaz.
                        </div>
                        <p class="text-muted small mt-2 mb-0">
                            1. sütun: Öğrenci No &nbsp;·&nbsp; 8. sütundan itibaren: Soru cevapları (1=Doğru, 0=Yanlış)
                        </p>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-upload me-1"></i> Yükle ve Hesapla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
