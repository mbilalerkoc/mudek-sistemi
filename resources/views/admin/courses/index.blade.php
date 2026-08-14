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

    {{-- Başarı / Hata mesajı --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">

        {{-- SOL: Ders Listesi --}}
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Dersler</span>
                    <span class="badge bg-primary">{{ $courses->count() }} ders</span>
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
                                        <td><span class="badge bg-secondary">{{ $course->code }}</span></td>
                                        <td>{{ $course->name }}</td>
                                        <td>{{ $course->credits ?? '-' }}</td>
                                        <td>{{ $course->semester ?? '-' }}</td>
                                        <td>
                                            {{ $course->users->pluck('name')->join(', ') ?: '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{-- Düzenle --}}
                                            <a href="{{ route('admin.courses.edit', $course->id) }}"
                                               class="btn btn-sm btn-warning me-1">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            {{-- Sil --}}
                                            <form action="{{ route('admin.courses.destroy', $course->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Bu dersi silmek istediğine emin misin?')">
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
                                        <option value="{{ $course->id }}">{{ $course->code }} - {{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-5">
                                <label class="form-label">Öğretmen</label>
                                <select name="user_id" class="form-select" required>
                                    <option value="" disabled selected>Öğretmen seçiniz</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Ata</button>
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
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Örn: Veri Tabanı Yönetimi"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ders Kodu <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code') }}"
                                   placeholder="Örn: BIL301"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kredi</label>
                            <input type="number"
                                   name="credits"
                                   class="form-control"
                                   value="{{ old('credits', 3) }}"
                                   min="1"
                                   max="10">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dönem</label>
                            <input type="text"
                                   name="semester"
                                   class="form-control"
                                   value="{{ old('semester') }}"
                                   placeholder="Örn: 2024-2025 Güz">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-1"></i> Ders Ekle
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection