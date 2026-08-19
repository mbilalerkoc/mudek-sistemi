@extends('layouts.app')

@section('title', 'Öğrenci Yönetimi')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Öğrenci Yönetimi</h3>
                    <p class="text-subtitle text-muted">Öğrencileri listele, ekle, düzenle ve sil</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Öğrenciler</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            {{-- SOL: Öğrenci Listesi --}}
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Öğrenciler</span>
                        <span class="badge bg-primary">{{ $students->count() }} öğrenci</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Öğrenci No</th>
                                        <th>Ad Soyad</th>
                                        <th class="text-center">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($students as $student)
                                        <tr>
                                            <td><span class="badge bg-secondary">{{ $student->student_no }}</span></td>
                                            <td><strong>{{ $student->name }} {{ $student->surname }}</strong></td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.students.edit', $student->id) }}"
                                                    class="btn btn-sm btn-warning me-1">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                
                                                {{-- Modern Sayfa İçi Modali Tetikleyen Buton --}}
                                                <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                        data-url="{{ route('admin.students.destroy', $student->id) }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">Henüz kayıtlı öğrenci yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SAĞ: Yeni Öğrenci Ekleme ve Excel Formları --}}
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">Yeni Öğrenci Ekle</div>
                    <div class="card-body">
                        <form action="{{ route('admin.students.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Öğrenci No <span class="text-danger">*</span></label>
                                <input type="text" name="student_no"
                                    class="form-control @error('student_no') is-invalid @enderror"
                                    value="{{ old('student_no') }}" placeholder="Örn: 2101001" required>
                                @error('student_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ad <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Örn: Ahmet" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Soyad <span class="text-danger">*</span></label>
                                <input type="text" name="surname"
                                    class="form-control @error('surname') is-invalid @enderror"
                                    value="{{ old('surname') }}" placeholder="Örn: Yılmaz" required>
                                @error('surname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-circle me-1"></i> Öğrenci Kaydet
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Excel Import Kartı --}}
                <div class="card mt-3">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-excel text-success"></i>
                        Excel ile Toplu Öğrenci Ekle
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data"
                            id="importForm">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Excel Dosyası</label>
                                <input type="file" name="excel_file" id="excel_file"
                                    class="form-control @error('excel_file') is-invalid @enderror"
                                    accept=".xlsx,.xls,.csv">
                                @error('excel_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="file-error" class="text-danger mt-1" style="font-size:0.82rem; display:none;">
                                </div>
                                <div class="form-text mt-1">
                                    Kabul edilen formatlar: <strong>.xlsx, .xls, .csv</strong> — Maks. 2MB<br>
                                    İlk satır başlık olmalı:
                                    <code>student_no | name | surname</code>
                                </div>
                            </div>

                            <button type="submit" id="importBtn" class="btn btn-success w-100" disabled>
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Excel ile İçe Aktar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modern Silme Onay Modali (Sayfa İçin Ortak) --}}
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white">Silme Onayı</h5>
                    <button type="button" class="btn-close btn-close-white" id="closeDeleteModalBtn"></button>
                </div>
                <div class="modal-body py-4">
                    <p class="mb-0">Bu öğrenciyi silmek istediğinize emin misiniz? Bu işlem geri alınamaz.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">İptal</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Evet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/custom/excel-validation.js') }}"></script>
    <script src="{{ asset('assets/js/custom/delete-modal.js') }}"></script>
@endpush