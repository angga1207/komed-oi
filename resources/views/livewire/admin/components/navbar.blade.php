<?php

use Carbon\Carbon;

?>
<div>
    <!-- Page Header Start-->
    <div class="page-header">
        <div class="header-wrapper m-0">
            <div class="header-logo-wrapper p-0">
                <div class="logo-wrapper">
                    <a href="{{ route('dashboard') }}">
                        <img class="img-fluid main-logo" src="{{ asset('images/icon.png') }}" alt="logo"
                            style="height:20px !important">
                        <img class="img-fluid white-logo" src="{{ asset('images/logo.png') }}" alt="logo"
                            style="height:20px !important">
                    </a>
                </div>
                <div class="toggle-sidebar">
                    <i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i>
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                        <img src="{{ asset('images/icon.png') }}" class="img-fluid" alt="" style="height:20px !important">
                        <span class="ms-2 text-primary font-weight-bold">
                            KOMED
                        </span>
                    </a>
                </div>
            </div>

            {{-- <form class="form-inline search-full d-none" action="javascript:void(0)" method="get">
                <div class="form-group w-100">
                    <div class="Typeahead Typeahead--twitterUsers">
                        <div class="u-posRelative">
                            <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text"
                                placeholder="Search Fastkart .." name="q" title="" autofocus>
                            <i class="close-search" data-feather="x"></i>
                            <div class="spinner-border Typeahead-spinner" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div class="Typeahead-menu"></div>
                    </div>
                </div>
            </form> --}}

            <div class="nav-right col-6 pull-right right-header p-0">
                <ul class="nav-menus">
                    <li>
                        <span class="header-search">
                            <i class="ri-search-line"></i>
                        </span>
                    </li>
                    <li class="onhover-dropdown">
                        <div class="notification-box">
                            <i class="ri-notification-line"></i>
                            @if (count(collect($notifications)->where('read_at', null)) > 0)
                                <span class="badge rounded-pill badge-theme">
                                    {{ count(collect($notifications)->where('read_at', null)) }}
                                </span>
                            @endif
                        </div>
                        <ul class="notification-dropdown onhover-show-div">
                            <li>
                                <i class="ri-notification-line"></i>
                                <h6 class="f-18 mb-0">
                                    Notifikasi
                                </h6>
                            </li>
                            @forelse($notifications as $notif)
                                <li>
                                    @if ($notif->type == 'App\Notifications\RegVerifNotification')
                                        <a href="#">
                                            <p
                                                class="@if ($notif->read_at == null) text-success @else text-primary @endif">
                                                @if ($notif->read_at == null)
                                                    <i class="fa fa-circle me-2 font-primary"></i>
                                                @else
                                                    <i class="fa fa-circle me-2 font-secondary"></i>
                                                @endif
                                                {{ str()->headline($notif->data['title']) ?? '-' }}
                                                <span class="pull-right">
                                                    {{ Carbon::parse($notif->created_at)->diffForHumans() }}
                                                </span>
                                            </p>
                                        </a>
                                    @elseif($notif->type == 'App\Notifications\OrderNotifications')
                                        <a href="{{ route('media-order') }}"
                                            wire:click.prevent="markAsReadNotif('{{ $notif->id }}')">
                                            <p
                                                class="@if ($notif->read_at == null) text-success @else text-primary @endif">
                                                @if ($notif->read_at == null)
                                                    <i class="fa fa-circle me-2 font-primary"></i>
                                                @else
                                                    <i class="fa fa-circle me-2 font-danger"></i>
                                                @endif
                                                {{ str()->headline($notif->data['title']) ?? '-' }}
                                                <span class="pull-right">
                                                    {{ Carbon::parse($notif->created_at)->diffForHumans() }}
                                                </span>
                                            </p>
                                        </a>
                                    @endif
                                </li>
                            @empty
                                <li>
                                    <p>
                                        <i class="fa fa-circle me-2 font-primary"></i>
                                        Tidak ada notifikasi
                                    </p>
                                </li>
                            @endforelse
                            <li>
                                <a class="btn btn-primary" href="javascript:void(0)">
                                    Lihat Semua
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <div class="mode">
                            <i class="ri-moon-line"></i>
                        </div>
                    </li>
                    <li class="profile-nav onhover-dropdown pe-0 me-0">
                        <div class="media profile-media">
                            <img class="user-profile rounded-circle" src="{{ asset(auth()->user()->photo) }}"
                                alt="">
                            <div class="user-name-hide media-body">
                                <span>
                                    Hi, {{ auth()->user()->first_name }}
                                </span>
                                <p class="mb-0 font-roboto">
                                    {{ auth()->user()->Role->name ?? '' }}
                                    <i class="middle ri-arrow-down-s-line"></i>
                                </p>
                            </div>
                        </div>
                        <ul class="profile-dropdown onhover-show-div">
                            <li>
                                <a href="{{ route('me') }}">
                                    <i data-feather="users"></i>
                                    <span>Profile</span>
                                </a>
                            </li>

                            @php
                                $manager = app('impersonate');
                            @endphp

                            @if ($manager->isImpersonating())
                                <li>
                                    <a href="javascript:void(0)" wire:click="stopImpersonate">
                                        <i data-feather="log-out"></i>
                                        <span>Stop Impersonate</span>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="javascript:void(0)" wire:click="logout">
                                        <i data-feather="log-out"></i>
                                        <span>
                                            Keluar Aplikasi
                                        </span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Header Ends-->
</div>
