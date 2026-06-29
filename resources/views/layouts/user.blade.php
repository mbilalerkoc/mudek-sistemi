<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Kullanıcı Paneli</title>

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
</head>
<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>

    <div id="app">

        @include('layouts.partials.sidebar-user')

        <div id="main">

            @include('layouts.partials.navbar')

            <div class="page-content">
                @yield('content')
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2024 &copy; Üniversite Yönetim Sistemi</p>
                    </div>
                    <div class="float-end">
                        <p>Kullanıcı Paneli</p>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>