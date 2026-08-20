@extends('layouts.app')

@section('title', $assignment->title . ' — Teslimler')

@section('content')

    @php
        $isAdmin = auth()->user()->role === 'super_admin';
        $derslerRoute = $isAdmin ? 'admin.dersler' : 'user.dersler';
        $detayRoute = $isAdmin ? 'admin.ders.detay' : 'user.ders.detay';
        $formRoute = $isAdmin ? 'admin.form.goster' : 'user.form.goster';
        $saveRoute = $isAdmin ? 'admin.dersler.odevler.teslimler.kaydet' : 'user.dersler.odevler.teslimler.kaydet';
    @endphp

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $assignment->title }}</h3>
                    <p class="text-subtitle text-muted">Öğrenci teslim bilgilerini girin</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route($derslerRoute) }}">Dersler</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route($detayRoute, $course->id) }}">{{ $course->name }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route($formRoute, ['ders_id' => $course->id, 'form_id' => 3]) }}">Ödevler</a>
                            </li>
                            <li class="breadcrumb-item active">Teslimler</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">{{ $assignment->title }} — Teslim Bilgileri</h4>
                <span class="badge bg-ktun-soft px-3 py-2">Maks. {{ $assignment->max_score }} puan</span>
            </div>
            <div class="card-body pt-4">
                <form action="{{ route($saveRoute, ['ders_id' => $course->id, 'odev_id' => $assignment->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center">
                            <thead>
                                <tr>
                                    <th>Öğrenci No</th>
                                    <th class="text-start">Ad Soyad</th>
                                    <th style="width:250px;">Dosya</th>
                                    <th style="width:150px;">Puan</th>
                                    <th style="width:120px;">Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    @php
                                        $submission = $submissionMap[$student->id] ?? null;
                                    @endphp
                                    <tr>
                                        <td class="fw-bold">{{ $student->student_no }}</td>
                                        <td class="text-start">{{ $student->name }} {{ $student->surname }}</td>
                                        <td>
                                            @if ($submission && $submission->file_path)
                                                {{-- 1. ADIM: DOSYA GÖRÜNÜMÜ VE ÜÇ NOKTA MENÜSÜ --}}
                                                <div id="file-view-{{ $student->id }}"
                                                    class="d-flex justify-content-between align-items-center w-100 px-2">
                                                    <div class="text-truncate text-start pe-2" style="max-width: 180px;">
                                                        <a href="{{ asset('storage/' . $submission->file_path) }}"
                                                            target="_blank" class="ktun-text-link"
                                                            title="{{ basename($submission->file_path) }}">
                                                            <i class="bi bi-paperclip me-1 text-muted"></i>
                                                            {{ basename($submission->file_path) }}
                                                        </a>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-muted p-0" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical fs-5"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                            <li>
                                                                <button class="dropdown-item text-secondary py-2"
                                                                    type="button"
                                                                    onclick="toggleFileInput({{ $student->id }})">
                                                                    <i class="bi bi-arrow-repeat me-2"></i> Değiştir
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item text-danger py-2"
                                                                    type="button"
                                                                    onclick="markForDeletion({{ $student->id }})">
                                                                    <i class="bi bi-trash me-2"></i> Sil
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                {{-- 2. ADIM: SİLME ONAYI (Dropdown'dan Sil'e basılınca açılır) --}}
                                                <div id="file-delete-{{ $student->id }}"
                                                    class="d-none flex-column align-items-center gap-2 text-center w-100">
                                                    {{-- Tıklanabilir hale getirdiğimiz onay butonu --}}
                                                    <button type="button" class="btn btn-sm btn-danger w-100 py-1"
                                                        onclick="confirmDeletion({{ $student->id }})">
                                                        <i class="bi bi-trash me-1"></i> Dosyayı Kaldır
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-sm btn-link text-secondary p-0 text-decoration-none"
                                                        onclick="cancelDeletion({{ $student->id }})">
                                                        Vazgeç
                                                    </button>
                                                </div>

                                                {{-- 3. ADIM: DEĞİŞTİRME VEYA SİLİNDİKTEN SONRAKİ INPUT EKRANI --}}
                                                <div id="file-input-{{ $student->id }}" class="d-none">
                                                    <input type="file" name="submissions[{{ $student->id }}][file]"
                                                        class="form-control form-control-sm file-size-check"
                                                        accept=".pdf,.doc,.docx,.ppt,.pptx" data-max-size="10"
                                                        data-warning-id="dosyaUyari{{ $student->id }}">
                                                    <button type="button"
                                                        class="btn btn-sm btn-link text-danger mt-1 p-0 text-decoration-none"
                                                        onclick="cancelInput({{ $student->id }})">
                                                        <i class="bi bi-x-circle me-1"></i> İptal Et
                                                    </button>
                                                    <div id="dosyaUyari{{ $student->id }}" class="text-danger mt-1 d-none"
                                                        style="font-size:0.8rem;">
                                                        Dosya 10MB'dan büyük olamaz.
                                                    </div>
                                                </div>
                                                <input type="hidden" name="submissions[{{ $student->id }}][delete_file]"
                                                    id="delete-flag-{{ $student->id }}" value="0">
                                            @else
                                                <input type="file" name="submissions[{{ $student->id }}][file]"
                                                    class="form-control form-control-sm file-size-check"
                                                    accept=".pdf,.doc,.docx,.ppt,.pptx" data-max-size="10"
                                                    data-warning-id="dosyaUyari{{ $student->id }}">
                                                <div id="dosyaUyari{{ $student->id }}" class="text-danger mt-1 d-none"
                                                    style="font-size:0.8rem;">
                                                    Dosya 10MB'dan büyük olamaz.
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" name="submissions[{{ $student->id }}][grade_score]"
                                                class="form-control form-control-sm text-center hide-spinners"
                                                min="0" max="{{ $assignment->max_score }}"
                                                value="{{ $submission->grade_score ?? '' }}"
                                                placeholder="0-{{ $assignment->max_score }}">
                                        </td>
                                        <td>
                                            @if ($submission && ($submission->file_path || $submission->grade_score !== null))
                                                <span id="status-badge-{{ $student->id }}" class="badge bg-success">
                                                    Girildi
                                                </span>
                                            @else
                                                <span id="status-badge-{{ $student->id }}" class="badge bg-secondary">
                                                    Teslim Edilmedi
                                                </span>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-muted py-4">
                                            Bu derse kayıtlı öğrenci bulunmamaktadır.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($students->count() > 0)
                        <div class="text-end mt-3">
                            <a href="{{ route($formRoute, ['ders_id' => $course->id, 'form_id' => 3]) }}"
                                class="btn btn-outline-secondary me-2">
                                İptal
                            </a>
                            <button type="submit" class="btn btn-primary-light px-4">
                                <i class="bi bi-check-lg me-1"></i> Kaydet
                            </button>
                        </div>
                    @endif

                </form>
            </div>
        </div>

    </section>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/custom/file-size-check.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/custom/file-toggle.js') }}?v={{ time() }}"></script>
@endpush
