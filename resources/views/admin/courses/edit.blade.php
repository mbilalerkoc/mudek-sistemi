@extends('layouts.app')

@section('title', 'Ders Düzenle')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Ders Düzenle</h3>
                <p class="text-subtitle text-muted">{{ $course->code }} - {{ $course->name }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Dersler</a></li>
                        <li class="breadcrumb-item active">Düzenle</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">Ders Bilgilerini Güncelle</div>
                <div class="card-body">

                    <form action="{{ route('admin.courses.update', $course->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Ders Adı <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $course->name) }}"
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
                                   value="{{ old('code', $course->code) }}"
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
                                   value="{{ old('credits', $course->credits) }}"
                                   min="1"
                                   max="10">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dönem</label>
                            <input type="text"
                                   name="semester"
                                   class="form-control"
                                   value="{{ old('semester', $course->semester) }}"
                                   placeholder="Örn: 2024-2025 Güz">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Güncelle
                            </button>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> İptal
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection