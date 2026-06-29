@extends('layouts.auth')

@section('title', 'Giriş Yap')

@section('content')
<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="{{ route('login') }}"><img src="{{ asset('assets/compiled/svg/logo.svg') }}" alt="Logo"></a>
            </div>
            <h1 class="auth-title">Log in.</h1>
            <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>

            {{-- Formun "action" kısmını rota ismine çevirdik --}}
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="email" name="email" class="form-control form-control-xl" placeholder="Email" required>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" name="password" class="form-control form-control-xl" placeholder="Password" required>
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <div class="form-check form-check-lg d-flex align-items-end">
                    <input class="form-check-input me-2" type="checkbox" name="remember" id="flexCheckDefault">
                    <label class="form-check-label text-gray-600" for="flexCheckDefault">
                        Keep me logged in
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
            </form>
            
            <div class="text-center mt-5 text-lg fs-4">
                <p class="text-gray-600">Don't have an account? <a href="#" class="font-bold">Sign up</a>.</p>
                <p><a class="font-bold" href="#">Forgot password?</a>.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right">
            {{-- Mazer'ın sağ tarafındaki görsel/renk alanı --}}
        </div>
    </div>
</div>
@endsection