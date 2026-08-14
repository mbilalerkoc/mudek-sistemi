@extends('layouts.app')

@section('title', 'Öğrenci Düzenle')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <h3>Öğrenci Düzenle</h3>
            <p class="text-subtitle text-muted">{{ $student->name }} {{ $student->surname }} adlı öğrencinin bilgilerini güncelleyin.</p>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Öğrenci No <span class="text-danger">*</span></label>
                                <input type="text" name="student_no" class="form-control @error('student_no') is-invalid @enderror" value="{{ old('student_no', $student->student_no) }}" required>
                                @error('student_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ad <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $student->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Soyad <span class="text-danger">*</span></label>
                                <input type="text" name="surname" class="form-control @error('surname') is-invalid @enderror" value="{{ old('surname', $student->surname) }}" required>
                                @error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">İptal</a>
                                <button type="submit" class="btn btn-primary">Güncelle</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection