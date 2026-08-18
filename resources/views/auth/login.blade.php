@extends('layouts.auth')

@section('title', 'Giriş Yap')

@section('content')
    <div class="row h-100">
        <div class="col-lg-4 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="{{ route('login') }}"><img src="{{ asset('assets/compiled/png/logo.png') }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title">Giriş Yap</h1>
                <p class="auth-subtitle mb-4">Kurumsal e-posta adresiniz ve şifreniz ile sisteme giriş yapın.</p>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Giriş bilgileri hatalı veya eksik.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    {{-- E-posta Alanı --}}
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">E-posta Adresi</label>
                        <div class="position-relative">
                            <input type="email" name="email" id="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="ornek@ktun.edu.tr" required autofocus>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Şifre Alanı ve Göz İkonu --}}
                    <div class="form-group mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label mb-0">Şifre</label>
                            <a class="auth-forgot-password text-primary" href="#" id="openForgotModal"
                                style="cursor: pointer;">Şifremi Unuttum</a>
                        </div>

                        <div class="position-relative">
                            <input type="password" name="password" id="password"
                                class="form-control form-control-lg pe-5 @error('password') is-invalid @enderror"
                                placeholder="********" required>


                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary"
                                id="togglePassword" style="cursor: pointer; z-index: 10;">
                                <i class="bi bi-eye-slash" id="toggleIcon"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-check-lg d-flex align-items-end mb-4">
                        <input class="form-check-input me-2" type="checkbox" name="remember" id="flexCheckDefault">
                        <label class="form-check-label text-gray-600" for="flexCheckDefault">
                            Beni Hatırla
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-2">Giriş Yap</button>
                </form>
            </div>
        </div>
        <div class="col-lg-8 d-none d-lg-block">
            <div id="auth-right" class="p-0 overflow-hidden">
                <img src="{{ asset('assets/compiled/jpg/005_KTÜN_ DRONE 003_2020.jpg') }}" alt="KTÜN Kampüs"
                    class="auth-img-fluid w-100 h-100 object-fit-cover">
            </div>
        </div>
    </div>

    {{-- Şifremi Unuttum Modali --}}
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Doğrudan Şifre Yenileme</h5>
                    <button type="button" class="btn-close" id="closeForgotModalBtn"></button>
                </div>
                <form action="{{ route('password.direct-reset') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted text-small">Sisteme kayıtlı kurumsal e-posta adresinizi ve belirlemek
                            istediğiniz yeni şifrenizi girin.</p>

                        <div class="form-group mb-3">
                            <label class="form-label">E-posta Adresi</label>
                            <input type="email" name="email" class="form-control" placeholder="ornek@ktun.edu.tr"
                                required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Yeni Şifre</label>
                            <input type="password" name="password" class="form-control" placeholder="En az 8 karakter"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelForgotModalBtn">İptal</button>
                        <button type="submit" class="btn btn-primary">Şifreyi Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript İşlemleri --}}
    <script>
        // Şifre Göster / Gizle
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });

        // Güvenli Modal Açma ve Kapatma (Bootstrap JS Bağımlılığı Olmadan)
        const modal = document.getElementById('forgotPasswordModal');
        const openBtn = document.getElementById('openForgotModal');
        const closeBtn = document.getElementById('closeForgotModalBtn');
        const cancelBtn = document.getElementById('cancelForgotModalBtn');

        function openModal(e) {
            e.preventDefault();
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
        }

        function closeModal() {
            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');
        }

        openBtn.addEventListener('click', openModal);
        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Modal dışına tıklandığında kapanma
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    </script>
@endsection
