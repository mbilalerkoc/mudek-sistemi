@extends('layouts.auth')

@section('title', 'Giriş Yap')

@section('content')
    <div class="row h-100">
        <div class="col-lg-4 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="{{ route('login') }}"><img src="{{ asset('assets/compiled/png/logo.png') }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title">Giriş</h1>
                <p class="auth-subtitle mb-5">Email adresiniz ve erişim şifreniz ile giriş yapabilirsiniz.</p>

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <input type="text" name="email" id="email" class="form-control form-control-lg"
                                placeholder="Email adresi" required>

                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <label for="password" class="form-label mb-0">Şifre</label>
                            <a class="auth-forgot-password" href="#">Şifremi Unuttum</a>
                        </div>

                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control form-control-lg"
                                placeholder="Şifreniz" required>
                        </div>
                    </div>

                    <button type="submit" class="btn auth-btn btn-lg mt-5">Giriş Yap</button>
                </form>
            </div>
        </div>
        <div class="col-lg-8 d-none d-lg-block">
            <div id="auth-right" class="p-0 overflow-hidden">
                <img src="{{ asset('assets/compiled/jpg/005_KTÜN_ DRONE 003_2020.jpg') }}" alt="KTÜN Kampüs" class="auth-img-fluid">
            </div>
        </div>
    </div>
@endsection
