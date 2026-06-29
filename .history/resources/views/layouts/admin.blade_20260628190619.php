<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    
</head>
<body>
    <div id="app">
        @include('layouts.partials.sidebar-admin') {{-- Sadece admine özel sidebar --}}
        <div id="main">
            @include('layouts.partials.navbar')
            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>