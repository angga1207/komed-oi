<div>
    <!-- Page Sidebar Start-->
    <div class="sidebar-wrapper">
        <div id="sidebarEffect"></div>
        <div>
            <div class="logo-wrapper logo-wrapper-center">
                <a href="{{ route('dashboard') }}" data-bs-original-title="" title="">
                    <img class="img-fluid for-white" src="{{ asset('assets/images/logo/full-white.png') }}" alt="logo">
                </a>
                <div class="back-btn">
                    <i class="fa fa-angle-left"></i>
                </div>
                <div class="toggle-sidebar">
                    <i class="ri-apps-line status_toggle middle sidebar-toggle"></i>
                </div>
            </div>
            <div class="logo-icon-wrapper">
                <a href="{{ route('dashboard') }}">
                    <img class="img-fluid main-logo main-white" src="{{ asset('assets/images/logo/logo.png') }}"
                        alt="logo">
                    <img class="img-fluid main-logo main-dark" src="{{ asset('assets/images/logo/logo-white.png') }}"
                        alt="logo">
                </a>
            </div>
            <nav class="sidebar-main">
                <div class="left-arrow" id="left-arrow">
                    <i data-feather="arrow-left"></i>
                </div>

                <div id="sidebar-menu">
                    <ul class="sidebar-links" id="simple-bar">
                        <li class="back-btn"></li>

                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('dashboard') }}">
                                <i class="ri-home-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        @if (in_array(auth()->user()->role_id, [1, 2, 3]))
                        <li class="sidebar-list">
                            <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)">
                                {{-- <i class="ri-store-3-line"></i> --}}
                                {{-- news office icon --}}
                                <i class="ri-newspaper-line"></i>
                                <span>
                                    Media
                                </span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li>
                                    <a href="{{ route('media') }}">
                                        Daftar Media
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('media.need-approval') }}">
                                        Menunggu Persetujuan
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-list">
                            <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)">
                                <i class="ri-user-6-line"></i>
                                <span>
                                    Pengguna
                                </span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li>
                                    <a href="{{ route('users.user') }}">
                                        Daftar Klien
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('users.admin') }}">
                                        Daftar Admin & Verifikator
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                    </ul>
                </div>

                <div class="right-arrow" id="right-arrow">
                    <i data-feather="arrow-right"></i>
                </div>
            </nav>
        </div>
    </div>
    <!-- Page Sidebar Ends-->
</div>
