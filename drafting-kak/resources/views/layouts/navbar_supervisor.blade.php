<div class="sidebar" data-background-color="light">
    <div class="sidebar-logo">
        <div class="logo-header" style="justify-content: center">
            <a href="{{ route('supervisor.index') }}" class="logo">
                <img src="{{ asset('assets/img/kaiadmin/logo_bakti_light.svg') }}" alt="navbar brand" class="navbar-brand"
                    height="60" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ Route::is('supervisor.index') ? 'active' : '' }}">
                    <a href="{{ route('supervisor.index') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('supervisor.daftar') ? 'active' : '' }}">
                    <a href="{{ route('supervisor.daftar') }}">
                        <i class="fa-sharp fa-solid fa-clipboard-list"></i>
                        <p>Daftar KAK</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('supervisor.laporan') ? 'active' : '' }}">
                    <a href="{{ route('supervisor.laporan') }}">
                        <i class="fas fa-file"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" onclick="logoutConfirm(event)">
                        <i class="fas fa-right-from-bracket"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
