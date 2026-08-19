@extends('layouts.auth')

@section('title', 'Giriş Yap')

@section('content')
    <div class="row h-100">
        <div class="col-lg-4 col-12">
            <div id="auth-left">
                <div class="auth-logo">

                    <a href="{{ route('login') }}"><img src="{{ asset('assets/compiled/png/logo.png') }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title" style="color: var(--ktun-text);">Giriş Yap</h1>
                <p class="auth-subtitle mb-4" style="color: var(--ktun-card-text);">Kurumsal e-posta adresiniz ve şifreniz ile sisteme giriş yapın.</p>

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">E-posta Adresi</label>
                        <input type="email" name="email" id="email" 
                               class="form-control form-control-lg" 
                               value="{{ old('email') }}" placeholder="ornek@ktun.edu.tr" required autofocus>
                    </div>

                    <div class="form-group mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label mb-0">Şifre</label>
                            <a class="auth-forgot-password" href="#" id="openForgotModal">Şifremi Unuttum</a>
                        </div>
                        <div class="position-relative">
                            <input type="password" name="password" id="password" 
                                   class="form-control form-control-lg pe-5" placeholder="********" required>
                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary" 
                                  id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye-slash" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Beni Hatırla</label>
                    </div>

                    <button type="submit" class="btn auth-btn btn-lg shadow-lg">Giriş Yap</button>
                </form>
            </div>
        </div>
        <div class="col-lg-8 d-none d-lg-block">
            <div id="auth-right">
                <img src="{{ asset('assets/compiled/jpg/005_KTÜN_ DRONE 003_2020.jpg') }}" alt="KTÜN Kampüs" class="auth-img-fluid">
            </div>
        </div>
    </div>

    {{-- Modal Yapısı --}}
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Şifre Yenileme</h5>
                    <button type="button" class="btn-close" id="closeForgotModalBtn"></button>
                </div>
                <form action="{{ route('password.direct-reset') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Kurumsal E-posta</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Yeni Şifre</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelForgotModalBtn">İptal</button>
                        <button type="submit" class="btn btn-primary" style="background-color: var(--ktun-auth-btn);">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/custom/auth.js') }}"></script>
@endsection