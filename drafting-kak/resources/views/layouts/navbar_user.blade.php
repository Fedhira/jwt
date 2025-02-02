<!-- resources/views/layouts/navbar.blade.php -->
<div class="sidebar" data-background-color="light">
    <div class="sidebar-logo">
        <div class="logo-header" style="justify-content: center">
            <a href="{{ route('user.index') }}" class="logo">
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
                <li class="nav-item {{ Route::is('user.index') ? 'active' : '' }}">
                    <a href="{{ route('user.index') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('user.daftar') ? 'active' : '' }}">
                    <a href="{{ route('user.daftar') }}">
                        <i class="fa-sharp fa-solid fa-clipboard-list"></i>
                        <p>Daftar KAK</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('user.draft') ? 'active' : '' }}">
                    <a href="{{ route('user.draft') }}">
                        <i class="fa-solid fa-file-pen"></i>
                        <p>Draft KAK</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('user.laporan') ? 'active' : '' }}">
                    <a href="{{ route('user.laporan') }}">
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
