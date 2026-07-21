<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Konya Teknik Üniversitesi - MÜDEK Sistemi')</title>

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/ktun-theme.css') }}">
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>

    <div id="wrapper">

        {{-- ==================== NAVBAR ==================== --}}
        <nav class="ktun-navbar">
            <div class="ktun-navbar-inner">
                <div class="ktun-navbar-left d-flex align-items-center gap-3">
                    <a href="{{ route('user.dersler') }}">
                        <img src="{{ asset('assets/compiled/jpg/ktun_logo_koyu_zemin.gif') }}" alt="Üniversite Logosu"
                            class="logo-large" style="height: 40px; width: auto;">

                        <img src="{{ asset('assets/compiled/png/simple_logo.png') }}" alt="Küçük Logo"
                            class="logo-small" style="height: 40px; width: auto;">
                    </a>
                    <button class="ktun-burger" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>

                </div>
                <div class="ktun-navbar-right d-flex align-items-center gap-3">
                    {{-- <a href="#" class="ktun-nav-icon"><i class="bi bi-fullscreen"></i></a> --}}
                    {{-- <a href="#" class="ktun-nav-icon"><i class="bi bi-grid-3x3-gap"></i></a> --}}
                    {{-- <a href="#" class="ktun-nav-icon position-relative">
                    <i class="bi bi-envelope"></i>
                    <span class="ktun-badge">0</span>
                </a> --}}

                    <a href="#" class="d-flex align-items-center gap-2 text-white text-decoration-none">
                        <span
                            class="ktun-nav-username">{{ auth()->check() ? auth()->user()->name : 'Kullanıcı' }}</span>
                        <i class="bi bi-chevron-down"
                            style="font-size: 0.7rem; font-weight: bold; -webkit-text-stroke: 1px;"></i>
                    </a>

                    <div class="ktun-avatar">
                        <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Profil">
                    </div>
                </div>
            </div>
        </nav>

        {{-- ==================== SIDEBAR ==================== --}}
        <div id="ktun-sidebar">

            <div class="ktun-sidebar-profile">
                <div class="ktun-sidebar-avatar">
                    <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Profil">
                </div>
                <div class="ktun-sidebar-username">
                    {{ auth()->check() ? auth()->user()->name : 'Kullanıcı' }}
                    <i class="bi bi-chevron-down ms-1" style="font-size:0.7rem;"></i>
                </div>
                @if (auth()->check())
                    <div class="ktun-sidebar-meta">Öğr. No: 000000</div>
                @endif
            </div>

            <div class="ktun-sidebar-menu">
                @if (Request::is('admin*'))
                    @include('layouts.partials.sidebar-admin')
                @else
                    @include('layouts.partials.sidebar-user')
                @endif
            </div>

        </div>

        {{-- ==================== ANA İÇERİK ==================== --}}
        <div id="ktun-main">
            <div id="ktun-content">
                @yield('content')
            </div>
            <footer class="ktun-footer">
                <p class="mb-0">© {{ date('Y') }} Konya Teknik Üniversitesi - MÜDEK Bilgi Sistemi</p>
            </footer>
        </div>

    </div>

    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('ktun-sidebar').classList.toggle('collapsed');
            document.getElementById('ktun-main').classList.toggle('expanded');
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Burger butonunu ID'sinden yakalıyoruz
            const sidebarToggleBtn = document.getElementById('sidebarToggle');

            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function(event) {
                    event.preventDefault(); // Sayfa yenilenmesini engeller

                    // Body etiketine 'sidebar-collapsed' sınıfını ekler/çıkarır
                    document.body.classList.toggle('sidebar-collapsed');
                });
            }
        });
    </script>

    @stack('scripts')

</body>

</html>
