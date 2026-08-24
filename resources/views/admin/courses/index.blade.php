@extends('layouts.app')

@section('title', 'Ders Yönetimi')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Ders Yönetimi</h3>
                    <p class="text-subtitle text-muted">Dersleri listele, ekle, düzenle ve sil</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Dersler</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">

            {{-- SOL: Ders Listesi --}}
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Dersler</span>
                        <span class="badge bg-ktun-soft">{{ $courses->count() }} ders</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Kod</th>
                                        <th>Ders Adı</th>
                                        <th>Kredi</th>
                                        <th>Dönem</th>
                                        <th>Öğretmen</th>
                                        <th class="text-center">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($courses as $course)
                                        <tr>
                                            <td><span class="badge bg-ktun-soft">{{ $course->code }}</span></td>
                                            <td class="fw-500">{{ $course->name }}</td>
                                            <td>{{ $course->credits ?? '-' }}</td>
                                            <td>{{ $course->semester ?? '-' }}</td>
                                            <td>
                                                @forelse($course->users as $teacher)
                                                    {{ optional($teacher->academicTitle)->title }} {{ $teacher->name }}
                                                    {{ $teacher->surname }}@if (!$loop->last),@endif
                                                @empty
                                                    <span class="text-muted">-</span>
                                                @endforelse
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    {{-- Öğrenciler --}}
                                                    <a href="{{ route('admin.courses.ogrenciler', $course->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Öğrenciler">
                                                        <i class="bi bi-people"></i>
                                                    </a>

                                                    {{-- Düzenle --}}
                                                    <a href="{{ route('admin.courses.edit', $course->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Düzenle">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    {{-- Sil (Modern Modal Tetikleyici) --}}
                                                    <form action="{{ route('admin.courses.destroy', $course->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
                                                                data-url="{{ route('admin.courses.destroy', $course->id) }}"
                                                                title="Sil">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                Henüz ders eklenmemiş.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Öğretmen Atama --}}
                <div class="card mt-3">
                    <div class="card-header">Öğretmen Ata</div>
                    <div class="card-body">
                        <form action="{{ route('admin.courses.assign') }}" method="POST">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-md-5">
                                    <label class="form-label">Ders</label>
                                    <select name="course_id" class="form-select" required>
                                        <option value="" disabled selected>Ders seçiniz</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->code }} - {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-5">
                                    <label class="form-label">Öğretmen</label>
                                    <select name="user_id" class="form-select" required>
                                        <option value="">Öğretmen seçiniz</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ optional($user->academicTitle)->title }} {{ $user->name }}
                                                {{ $user->surname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <button type="submit" class="btn btn-primary-light w-100">Ata</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- SAĞ: Ders Ekleme Formu --}}
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">Yeni Ders Ekle</div>
                    <div class="card-body">
                        <form action="{{ route('admin.courses.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Ders Adı <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Örn: Veri Tabanı Yönetimi" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ders Kodu <span class="text-danger">*</span></label>
                                <input type="text" name="code"
                                    class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}"
                                    placeholder="Örn: BIL301" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kredi</label>
                                <input type="number" name="credits" class="form-control" value="{{ old('credits', 3) }}"
                                    min="1" max="10">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dönem</label>
                                <input type="text" name="semester" class="form-control"
                                    value="{{ old('semester') }}" placeholder="Örn: 2024-2025 Güz">
                            </div>

                            <button type="submit" class="btn btn-primary-light w-100">
                                <i class="bi bi-plus-circle me-1"></i> Ders Ekle
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- Modern Silme Onay Modali --}}
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--ktun-danger); border-bottom: none;">
                    <h5 class="modal-title text-white">Silme Onayı</h5>
                    <button type="button" class="btn-close btn-close-white" id="closeDeleteModalBtn"></button>
                </div>
                <div class="modal-body py-4">
                    <p class="mb-0">Bu dersi silmek istediğinize emin misiniz? Bu işlem geri alınamaz.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">İptal</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background: var(--ktun-danger); color: #fff;">Evet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/custom/delete-modal.js') }}"></script>
@endpush