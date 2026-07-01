<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Giriş Yap')</title>
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}"> <link rel="stylesheet" href="{{ asset('assets/compiled/css/ktun-theme.css') }}">
</head>
<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="auth">
        @yield('content')
    </div>
</body>
</html>