<ul class="ktun-menu">
    <li class="ktun-menu-title">SÜPER ADMİN MENÜSÜ</li>

    <li class="ktun-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard') }}">
            <i class="bi bi-house-door"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="ktun-menu-item {{ request()->routeIs('admin.dersler') ? 'active' : '' }}">
        <a href="{{ route('admin.dersler') }}" class='sidebar-link'>
            <i class="bi bi-journal-bookmark-fill"></i>
            <span>Dersler</span>
        </a>
    </li>
    <li class="ktun-menu-item {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
        <a href="{{ route('admin.courses.index') }}">
            <i class="bi bi-sliders"></i>
            <span>Ders Yönetimi</span>
        </a>
    </li>

    <li class="ktun-menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a href="{{ route('admin.users.index') }}">
            <i class="bi bi-people-fill"></i>
            <span>Kullanıcı Yönetimi</span>
        </a>
    </li>

    <li class="ktun-menu-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
        <a href="{{ route('admin.students.index') }}">
            <i class="bi bi-mortarboard-fill"></i>
            <span>Öğrenci Yönetimi</span>
        </a>

    <li class="ktun-menu-title mt-2">HESAP</li>

    <li class="ktun-menu-item">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-left"></i>
            <span>Çıkış Yap</span>
        </a>
    </li>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</ul>
