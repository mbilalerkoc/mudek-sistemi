<ul class="ktun-menu">

    <li class="ktun-menu-title">GENEL MENÜ</li>

    <li class="ktun-menu-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
        <a href="{{ route('user.dashboard') }}">
            <i class="bi bi-house-door"></i>
            <span>Anasayfa</span>
        </a>
    </li>

    <li class="ktun-menu-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
        <a href="{{ route('user.profile') }}">
            <i class="bi bi-person"></i>
            <span>Profil</span>
        </a>
    </li>

    <li class="ktun-menu-item {{ request()->routeIs('user.dersler') ? 'active' : '' }}">
        <a href="{{ route('user.dersler') }}">
            <i class="bi bi-book"></i>
            <span>Dersler</span>
        </a>
    </li>




    <li class="ktun-menu-title mt-2">HESAP</li>

    <li class="ktun-menu-item">
        <form action="..." method="POST">
            @csrf
            <button type="submit" class="ktun-logout-btn">
                <i class="bi bi-box-arrow-left"></i>
                <span>Çıkış Yap</span> </button>
        </form>
    </li>

</ul>
