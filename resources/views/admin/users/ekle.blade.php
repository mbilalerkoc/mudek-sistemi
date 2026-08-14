@extends('layouts.app')

@section('title', 'Yeni Kullanıcı Ekle')

@section('content')
<div class="page-heading mb-4">
    <h3>Yeni Kullanıcı Ekle</h3>
    <p class="text-muted">Sisteme yeni bir öğretmen veya yönetici kaydedin.</p>
</div>

<div class="page-content">
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ad</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Soyad</label>
                        <input type="text" name="surname" class="form-control" value="{{ old('surname') }}" required>
                        @error('surname') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">E-posta</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Şifre</label>
                        <input type="password" name="password" class="form-control" required>
                        @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Rol</label>
                        <select name="role" class="form-select" required>
                            <option value="user">Kullanıcı</option>
                            <option value="super_admin">Süper Admin</option>
                        </select>
                        @error('role') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Akademik Unvan (Zorunlu Değil)</label>
                        <select name="academic_title_id" class="form-select">
                            <option value="">-- Unvan Seçin --</option>
                            @foreach($academicTitles as $title)
                                <option value="{{ $title->id }}">{{ $title->title ?? $title->name }}</option>
                            @endforeach
                        </select>
                        @error('academic_title_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-4 text-end">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">İptal</a>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection