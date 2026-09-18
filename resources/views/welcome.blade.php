<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KTÜN - Proje Yönetim Paneli</title>  
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/ktun-theme.css') }}">
</head>
<body class="welcome-page" style="margin: 0; padding: 0; overflow: hidden;">
    <div class="welcome-wrapper" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; display: flex; align-items: center; justify-content: center; background-color: #1a1a1a; z-index: 9999;">
        <img src="{{ asset('assets/compiled/jpg/005_KTÜN_ DRONE 003_2020.jpg') }}" alt="KTÜN Kampüs" class="welcome-bg">
        <div class="welcome-overlay"></div>
        
        <div class="welcome-content shadow-lg" style="position: relative; z-index: 10;">
            <img src="{{ asset('assets/compiled/png/logo.png') }}" alt="KTÜN Logo" class="welcome-logo">
            <h1 class="welcome-title">Proje & Ders Yönetim Paneli</h1>
            <p class="welcome-lead">
                Konya Teknik Üniversitesi akademik süreçleri, ders materyalleri, ödev teslimleri ve sınav analizlerini tek merkezden yönetin.
            </p>
            
            <div class="welcome-features">
                <span class="feature-badge bg-ktun-soft">📚 Ders & Not Yönetimi</span>
                <span class="feature-badge bg-ktun-soft">📝 Ödev & Teslim Takibi</span>
                <span class="feature-badge bg-ktun-soft">📊 Sınav & Analiz</span>
            </div>

            <a href="{{ route('login') }}" class="btn welcome-btn btn-lg shadow">
                Sisteme Giriş Yap <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</body>
</html>