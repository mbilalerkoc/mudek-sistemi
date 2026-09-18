<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Konya Teknik Üniversitesi - MÜDEK Sistemi')</title>

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/ktun-theme.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>

    <div id="wrapper">

        {{-- ==================== NAVBAR ==================== --}}
        @include('layouts.partials.navbar')

        {{-- ==================== SIDEBAR ==================== --}}
        <div id="ktun-sidebar">

            <div class="ktun-sidebar-profile">
                <div class="ktun-sidebar-avatar">
                    <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Profil">
                </div>
                <div class="ktun-sidebar-username">
                    {{ auth()->check() ? auth()->user()->name . ' ' . auth()->user()->surname : 'Kullanıcı' }}
                    <i class="bi bi-chevron-down ms-1" style="font-size:0.7rem;"></i>
                </div>

                @if (auth()->check())
                    <div class="ktun-sidebar-meta">
                        @if (auth()->user()->role === 'super_admin')
                            <span class="badge bg-danger">Süper Admin</span>
                        @elseif(auth()->user()->academicTitle)
                            {{ auth()->user()->academicTitle->title }}
                        @else
                            Akademisyen
                        @endif
                    </div>
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
                @include('layouts.partials.alerts')
                @yield('content')
            </div>
            
            {{-- ==================== FOOTER ==================== --}}
            @include('layouts.partials.footer')
        </div>

    </div>

    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>

    <script src="{{ asset('assets/js/custom/sidebar-toggle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/file-size-check.js') }}"></script>
    @stack('scripts')

</body>

</html>