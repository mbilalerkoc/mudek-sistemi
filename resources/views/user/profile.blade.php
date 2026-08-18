@extends('layouts.app')

@section('title', 'Profilim')

@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Hesap Profili</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ auth()->user()->role === 'super_admin' ? route('admin.dashboard') : route('user.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Profil</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <div class="row">
                
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <div class="ktun-sidebar-avatar mb-3" style="width: 100px; height: 100px;">
                                    <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Profil" class="rounded-circle w-100 h-100 object-fit-cover">
                                </div>
                                <h3 class="mt-2 text-center">{{ auth()->user()->name }} {{ auth()->user()->surname }}</h3>
                                <p class="text-muted text-small">
                                    @if(auth()->user()->role === 'super_admin')
                                        <span class="badge bg-danger">Süper Admin</span>
                                    @elseif(auth()->user()->academicTitle)
                                        {{ auth()->user()->academicTitle->title }}
                                    @else
                                        Akademisyen
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

               
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                           
                            <form action="{{ route('user.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="name" class="form-label">Ad</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name', auth()->user()->name) }}" required>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="surname" class="form-label">Soyad</label>
                                        <input type="text" name="surname" id="surname" class="form-control"
                                            value="{{ old('surname', auth()->user()->surname) }}" required>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ old('email', auth()->user()->email) }}" required>
                                </div>

                                <hr class="my-4">
                                <h5 class="mb-3 text-secondary" style="font-size: 1rem;">Şifre Değiştir (İsteğe Bağlı)</h5>

                                <div class="form-group mb-3">
                                    <label for="current_password" class="form-label">Mevcut Şifre</label>
                                    <input type="password" name="current_password" id="current_password"
                                        class="form-control" placeholder="Şifrenizi değiştirmek istiyorsanız girin">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="new_password" class="form-label">Yeni Şifre</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control"
                                        placeholder="Yeni Şifre">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="new_password_confirmation" class="form-label">Yeni Şifre Tekrar</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                        class="form-control" placeholder="Yeni Şifre Tekrar">
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary px-4">Değişiklikleri Kaydet</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection