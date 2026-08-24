"@php
    $isAdmin = auth()->user()->role === 'super_admin';
    $storeRoute = $isAdmin ? 'admin.dersler.odevler.store' : 'user.dersler.odevler.store';
    $destroyRoute = $isAdmin ? 'admin.dersler.odevler.destroy' : 'user.dersler.odevler.destroy';
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">{{ $course->name }} — Ödevler</h5>
    <button type="button" class="btn btn-primary-light" data-bs-toggle="collapse" data-bs-target="#odevEkleForm">
        <i class="bi bi-plus-circle me-1"></i> Ödev Ekle
    </button>
</div>

{{-- Ödev Ekleme Formu (collapse) --}}
<div class="collapse mb-4" id="odevEkleForm">
    <div class="card shadow-sm">
        <div class="card-header">
            <h6 class="mb-0">Yeni Ödev Ekle</h6>
        </div>
        <div class="card-body">
            <form action="{{ route($storeRoute, $course->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Ödev Başlığı <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Örn: Proje Taslağı"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-bold">Maksimum Puan <span class="text-danger">*</span></label>
                        <input type="number" name="max_score" class="form-control" value="100" min="0"
                            max="100" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-bold">Etki Edeceği Sınav</label>
                        <select name="exam_id" class="form-select">
                            <option value="">Bağımsız Ödev (Sınava Etki Etmez)</option>
                            @foreach ($exams as $exam)
                                <option value="{{ $exam->id }}">
                                    {{ ucfirst($exam->exam_type) }} Sınavı
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-bold">Son Teslim Tarihi <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="due_date" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Açıklama</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Ödev detayları..."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Ödev Dosyası</label>
                        <input type="file" name="file" class="form-control file-size-check"
                            accept=".pdf,.doc,.docx,.ppt,.pptx" data-max-size="10" data-warning-id="dosyaBoyutUyari">
                        <small class="text-muted">PDF, Word veya PowerPoint — maks. 10MB</small>
                        <div id="dosyaBoyutUyari" class="alert alert-danger mt-2 d-none">
                            Dosya boyutu 10MB'dan büyük olamaz.
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-toggle="collapse"
                            data-bs-target="#odevEkleForm">
                            İptal
                        </button>
                        <button type="submit" class="btn btn-primary-light px-4">
                            <i class="bi bi-check-lg me-1"></i> Oluştur
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Ödev Listesi --}}
@forelse ($assignments as $assignment)
    <div class="card shadow-sm mb-3">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3 flex-grow-1" style="cursor: pointer;" data-bs-toggle="collapse"
                data-bs-target="#odev{{ $assignment->id }}">
                <i class="bi bi-journal-text text-primary fs-5"></i>
                <div>
                    <h6 class="mb-0">{{ $assignment->title }}</h6>
                    <small class="text-muted">
                        Oluşturuldu: {{ \Carbon\Carbon::parse($assignment->created_at)->format('d.m.Y H:i') }}
                        &nbsp;·&nbsp;
                        Son teslim: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d.m.Y H:i') }}
                        &nbsp;·&nbsp;
                        Maks. puan: {{ $assignment->max_score }}

                        @if ($assignment->examAssignments && $assignment->examAssignments->isNotEmpty())
                            @php
                                $exam = $assignment->examAssignments->first()->exam;
                            @endphp

                            @if ($exam)
                                &nbsp;·&nbsp;
                                <span class="badge bg-info text-dark">
                                    {{ ucfirst($exam->exam_type) }} Sınavı
                                </span>
                            @else
                                &nbsp;·&nbsp;
                                <span class="badge bg-secondary">Bağımsız Ödev</span>
                            @endif
                        @else
                            &nbsp;·&nbsp;
                            <span class="badge bg-secondary">Bağımsız Ödev</span>
                        @endif
                    </small>
                </div>
            </div>

            @php
                $teslimRoute =
                    auth()->user()->role === 'super_admin'
                        ? 'admin.dersler.odevler.teslimler'
                        : 'user.dersler.odevler.teslimler';
            @endphp

            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-ktun-soft px-3 py-2">
                    {{ $assignment->submissions->count() }} teslim
                </span>

                <a href="{{ route($teslimRoute, ['ders_id' => $course->id, 'odev_id' => $assignment->id]) }}"
                    class="btn btn-primary-light">
                    <i class="bi bi-pencil-square me-1"></i> Teslim Gir
                </a>

                <i class="bi bi-chevron-down text-muted ms-1" style="cursor: pointer;" data-bs-toggle="collapse"
                    data-bs-target="#odev{{ $assignment->id }}"></i>
            </div>
        </div>

        <div class="collapse" id="odev{{ $assignment->id }}">
            <div class="card-body">

                @if ($assignment->description)
                    <p class="text-muted mb-3">{{ $assignment->description }}</p>
                @endif

                {{-- Ödev dosyası --}}
                @if ($assignment->file_path)
                    <div class="mb-3">
                        <i class="bi bi-paperclip text-muted me-1"></i>
                        <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank"
                            class="ktun-text-link">
                            {{ basename($assignment->file_path) }}
                        </a>
                    </div>
                @else
                    <p class="text-muted mb-3">
                        <i class="bi bi-file-earmark-x me-1"></i> Ödev dosyası eklenmemiş.
                    </p>
                @endif
                {{-- Silme butonu --}}
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                        data-url="{{ route($destroyRoute, $assignment->id) }}">
                        <i class="bi bi-trash me-1"></i> Ödevi Sil
                    </button>
                </div>

            </div>
        </div>
    </div>
@empty
    <div class="card shadow-sm">
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-journal-x fs-1 mb-3 d-block"></i>
            Bu derse ait henüz ödev oluşturulmamış.
        </div>
    </div>
@endforelse

{{-- Silme Onay Modali --}}
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true"
    style="background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white">Silme Onayı</h5>
                <button type="button" class="btn-close btn-close-white" id="closeDeleteModalBtn"></button>
            </div>
            <div class="modal-body py-4 text-center">
                <i class="bi bi-exclamation-circle text-danger mb-3" style="font-size: 3rem;"></i>
                <p class="mb-0 fs-5">Bu ödevi silmek istediğinize emin misiniz?</p>
                <p class="text-muted mt-2">Bu işlem geri alınamaz.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary px-4" id="cancelDeleteBtn">İptal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 fw-bold">Evet, Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/js/custom/delete-modal.js') }}"></script>
@endpush
