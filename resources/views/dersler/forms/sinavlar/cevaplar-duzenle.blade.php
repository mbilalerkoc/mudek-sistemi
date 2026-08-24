@extends('layouts.app')
@section('title', $exam->course->name . ' — Cevap Düzenleme')

@section('content')

    @php
        $isAdmin = auth()->user()->role === 'super_admin';
        $kaydetRoute = $isAdmin ? 'admin.sinavlar.cevaplar.kaydet' : 'user.sinavlar.cevaplar.kaydet';
        $cevaplarRoute = $isAdmin ? 'admin.sinavlar.cevaplar' : 'user.sinavlar.cevaplar';
        $goruntuleRoute = $isAdmin ? 'admin.sinavlar.cevaplar' : 'user.sinavlar.cevaplar';
        $ornekRoute = $isAdmin ? 'admin.sinavlar.ornek.kaydet' : 'user.sinavlar.ornek.kaydet';

        $siraliOgrenciler = $exam->studentExams
            ->filter(fn($se) => $se->studentCourse?->student)
            ->sortByDesc('total_score')
            ->values();

        $bestExam = $exam->studentExams->first(fn($se) => $se->path && str_contains($se->path, '/best/'));
        $avgExam = $exam->studentExams->first(fn($se) => $se->path && str_contains($se->path, '/average/'));
        $worstExam = $exam->studentExams->first(fn($se) => $se->path && str_contains($se->path, '/worst/'));
    @endphp

    <div class="page-heading mb-4">
        <h3>{{ $exam->course->name }} — Sınav ve Örnek Kağıt Düzenleme</h3>
    </div>

    <section class="section">

        {{-- ÖRNEK SINAV KAĞITLARI YÖNETİM KARTI --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="bi bi-award me-2 text-primary"></i>
                    Örnek Sınav Kağıtları
                    <small class="text-muted fw-normal ms-2" style="font-size:0.8rem;">
                        Akreditasyon dosyaları için
                    </small>
                </h4>
            </div>

            <div class="card-body">
                <form action="{{ route($ornekRoute, $exam->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

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
                                    <div>
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
                                                @if ($bestExam->path)
                                                    <a href="{{ asset('storage/' . $bestExam->path) }}" target="_blank"
                                                        class="text-success small text-decoration-underline">
                                                        <i class="bi bi-paperclip me-1"></i>Mevcut kağıdı görüntüle
                                                    </a>
                                                @endif
                                            </div>
                                        @endif

                                        <label class="form-label fw-semibold small">Öğrenci Seç</label>
                                        <select name="best[student_exam_id]" class="form-select form-select-sm mb-3">
                                            <option value="">— Seçiniz —</option>
                                            @foreach ($siraliOgrenciler as $se)
                                                <option value="{{ $se->id }}"
                                                    {{ $bestExam && $bestExam->id === $se->id ? 'selected' : '' }}>
                                                    {{ $se->studentCourse->student->student_no }} -
                                                    {{ $se->studentCourse->student->name }} ({{ $se->total_score }}p)
                                                </option>
                                            @endforeach
                                        </select>

                                        <label class="form-label fw-semibold small">Kağıt Dosyası</label>
                                        <input type="file" name="best[file]" class="form-control form-control-sm"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
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
                                    <div>
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
                                                @if ($avgExam->path)
                                                    <a href="{{ asset('storage/' . $avgExam->path) }}" target="_blank"
                                                        class="text-warning text-dark small text-decoration-underline">
                                                        <i class="bi bi-paperclip me-1"></i>Mevcut kağıdı görüntüle
                                                    </a>
                                                @endif
                                            </div>
                                        @endif

                                        <label class="form-label fw-semibold small">Öğrenci Seç</label>
                                        <select name="average[student_exam_id]" class="form-select form-select-sm mb-3">
                                            <option value="">— Seçiniz —</option>
                                            @foreach ($siraliOgrenciler as $se)
                                                <option value="{{ $se->id }}"
                                                    {{ $avgExam && $avgExam->id === $se->id ? 'selected' : '' }}>
                                                    {{ $se->studentCourse->student->student_no }} -
                                                    {{ $se->studentCourse->student->name }} ({{ $se->total_score }}p)
                                                </option>
                                            @endforeach
                                        </select>

                                        <label class="form-label fw-semibold small">Kağıt Dosyası</label>
                                        <input type="file" name="average[file]" class="form-control form-control-sm"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- EN KÖTÜ --}}
                        <div class="col-12 col-md-4">
                            <div class="card h-100 border-danger">
                                <div
                                    class="card-header bg-danger bg-opacity-10 border-danger d-flex align-items-center gap-2">
                                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                    <span class="fw-bold text-danger">En Kötü Kağıt</span>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
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
                                                @if ($worstExam->path)
                                                    <a href="{{ asset('storage/' . $worstExam->path) }}" target="_blank"
                                                        class="text-danger small text-decoration-underline">
                                                        <i class="bi bi-paperclip me-1"></i>Mevcut kağıdı görüntüle
                                                    </a>
                                                @endif
                                            </div>
                                        @endif

                                        <label class="form-label fw-semibold small">Öğrenci Seç</label>
                                        <select name="worst[student_exam_id]" class="form-select form-select-sm mb-3">
                                            <option value="">— Seçiniz —</option>
                                            @foreach ($siraliOgrenciler as $se)
                                                <option value="{{ $se->id }}"
                                                    {{ $worstExam && $worstExam->id === $se->id ? 'selected' : '' }}>
                                                    {{ $se->studentCourse->student->student_no }} -
                                                    {{ $se->studentCourse->student->name }} ({{ $se->total_score }}p)
                                                </option>
                                            @endforeach
                                        </select>

                                        <label class="form-label fw-semibold small">Kağıt Dosyası</label>
                                        <input type="file" name="worst[file]" class="form-control form-control-sm"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route($goruntuleRoute, $exam->id) }}" class="btn btn-outline-secondary me-2">
                            İptal
                        </a>
                        <button type="submit" class="btn btn-primary-light px-4">
                            <i class="bi bi-check-lg me-1"></i> Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- CEVAP DÜZENLEME TABLOSU --}}
        <form action="{{ route($kaydetRoute, $exam->id) }}" method="POST">
            @csrf
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-dark"><i class="bi bi-pencil-square me-2"></i> Öğrenci Puanları Düzenleme</h4>
                    <div>
                        <a href="{{ route($cevaplarRoute, $exam->id) }}" class="btn btn-outline-secondary me-2">İptal</a>
                        <button type="submit" class="btn btn-primary-light px-4">
                            <i class="bi bi-check-lg me-1"></i> Tüm Değişiklikleri Kaydet
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:100px;">Öğrenci No</th>
                                    <th class="text-start">Ad Soyad</th>
                                    <th style="width:120px;">Seviye</th>
                                    @foreach ($exam->questions as $index => $question)
                                        <th style="width:90px;">
                                            S{{ $index + 1 }}<br><small
                                                class="text-muted">({{ $question->score }}p)</small>
                                        </th>
                                    @endforeach
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
                                        <td class="text-start">{{ $student->name ?? '' }} {{ $student->surname ?? '' }}
                                        </td>
                                        <td>
                                            <select name="grades[{{ $studentExam->id }}][level]"
                                                class="form-select form-select-sm">
                                                <option value="">-</option>
                                                <option value="1"
                                                    {{ (int) $studentExam->level === 1 ? 'selected' : '' }}>İyi</option>
                                                <option value="2"
                                                    {{ (int) $studentExam->level === 2 ? 'selected' : '' }}>Orta</option>
                                                <option value="3"
                                                    {{ (int) $studentExam->level === 3 ? 'selected' : '' }}>Kötü</option>
                                            </select>
                                        </td>
                                        @foreach ($exam->questions as $question)
                                            @php $answer = $existingAnswers->get($question->id); @endphp
                                            <td>
                                                <input type="number"
                                                    name="grades[{{ $studentExam->id }}][answers][{{ $question->id }}]"
                                                    class="form-control form-control-sm text-center hide-spinners"
                                                    min="0" max="{{ $question->score }}" step="0.01"
                                                    value="{{ $answer->score ?? '' }}">
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 3 + $exam->questions->count() }}" class="text-muted py-4">
                                            Bu sınava kayıtlı öğrenci bulunmamaktadır.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
