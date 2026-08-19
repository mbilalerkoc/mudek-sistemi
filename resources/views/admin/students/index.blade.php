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
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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
                                                <form action="{{ route('admin.students.destroy', $student->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Bu öğrenciyi silmek istediğinize emin misiniz?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">Henüz kayıtlı öğrenci
                                                yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SAĞ: Yeni Öğrenci Ekleme Formu --}}
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

                        @if (session('import_errors'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>İçe aktarma sırasında hatalar oluştu:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach (session('import_errors') as $hata)
                                        <li>{{ $hata }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('import_imported') !== null)
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Kısmi içe aktarma:</strong>
                                {{ session('import_imported') }} öğrenci eklendi,
                                {{ session('import_skipped') }} öğrenci atlandı.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

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
@endsection
@push('scripts')
    <script>
        const fileInput = document.getElementById('excel_file');
        const importBtn = document.getElementById('importBtn');
        const fileError = document.getElementById('file-error');
        const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
            'text/csv'
        ];
        const validExts = ['.xlsx', '.xls', '.csv'];

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            fileError.style.display = 'none';
            fileError.textContent = '';
            importBtn.disabled = true;

            if (!file) return;

            // Uzantı kontrolü
            const ext = '.' + file.name.split('.').pop().toLowerCase();
            if (!validExts.includes(ext)) {
                fileError.textContent = 'Geçersiz dosya formatı! Sadece .xlsx, .xls veya .csv yükleyebilirsiniz.';
                fileError.style.display = 'block';
                this.value = '';
                return;
            }

            // Boyut kontrolü (2MB)
            if (file.size > 2 * 1024 * 1024) {
                fileError.textContent = 'Dosya boyutu 2MB\'ı aşamaz!';
                fileError.style.display = 'block';
                this.value = '';
                return;
            }

            // Her şey tamam, butonu aktif et
            importBtn.disabled = false;
        });
    </script>
@endpush
