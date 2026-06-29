<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoş Geldiniz</title>
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
</head>
<body>
    <div class="container mt-5">
        <div class="text-center">
            <h1>Proje Yönetim Paneline Hoş Geldiniz</h1>
            <p class="lead">Devam etmek için lütfen giriş yapın.</p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Giriş Yap</a>
        </div>
    </div>
</body>
</html>