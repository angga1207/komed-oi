<div>
    <!-- Page Sidebar Start-->
    <div class="sidebar-wrapper">
        <div id="sidebarEffect"></div>
        <div>
            <div class="logo-wrapper logo-wrapper-center">
                <a href="{{ route('dashboard') }}" data-bs-original-title="" title="">
                    <h2 class="text-white">
                        KOMED OI
                    </h2>
                </a>
                <div class="back-btn">
                    <i class="fa fa-angle-left"></i>
                </div>
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
                                <i class="ri-newspaper-line"></i>
                                <span>
                                    Media Order
                                </span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li>
                                    <a href="{{ route('a.media-order') }}">
                                        Media Order
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('a.media-order.create') }}">
                                        Agenda Jadwalin Bae
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-list">
                            <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)">
                                <i class="ri-government-line"></i>
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
                        @elseif (in_array(auth()->user()->role_id, [4]))
                        <li class="sidebar-list">
                            <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)">
                                <i class="ri-newspaper-line"></i>
                                <span>
                                    Media Order
                                </span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li>
                                    <a href="{{ route('media-order') }}">
                                        Daftar Media Order
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Laporan
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
