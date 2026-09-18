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
            <a href="#" class="d-flex align-items-center gap-2 text-white text-decoration-none">
                <span class="ktun-nav-username">{{ auth()->check() ? auth()->user()->name : 'Kullanıcı' }}</span>
                <i class="bi bi-chevron-down"
                    style="font-size: 0.7rem; font-weight: bold; -webkit-text-stroke: 1px;"></i>
            </a>

            <div class="ktun-avatar">
                <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Profil">
            </div>
        </div>
    </div>
</nav>