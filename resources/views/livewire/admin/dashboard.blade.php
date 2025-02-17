<?php

use Carbon\Carbon;

?>
<div class="row">
    <div class="col-12">
        <div class="title-header option-title d-sm-flex d-block">
            <h5>
                Hi, {{ auth()->user()->first_name }}. Selamat Datang di Dashboard {{ env('APP_NAME') }}
            </h5>
        </div>
    </div>

    <!-- chart caard section start -->
    <div class="col-sm-6 col-xxl-3 col-lg-6">
        <div class="main-tiles border-5 border-0  card-hover card o-hidden">
            <div class="custome-1-bg b-r-4 card-body">
                <div class="media align-items-center static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">
                            Jumlah Mitra Media
                        </span>
                        <h4 class="mb-0 counter">
                            {{ count($mediaPers) ?? 0 }}
                        </h4>
                    </div>
                    <div class="align-self-center text-center">
                        <i class="ri-database-2-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xxl-3 col-lg-6">
        <div class="main-tiles border-5 card-hover border-0 card o-hidden">
            <div class="custome-2-bg b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">
                            Jumlah Media Order
                        </span>
                        <h4 class="mb-0 counter">
                            {{ count($mediaOrders) }}
                        </h4>
                    </div>
                    <div class="align-self-center text-center">
                        <i class="ri-shopping-bag-3-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xxl-3 col-lg-6">
        <div class="main-tiles border-5 card-hover border-0  card o-hidden">
            <div class="custome-3-bg b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">
                            Selesai Dikerjakan
                        </span>
                        <h4 class="mb-0 counter">
                            {{ count($mediaOrders->whereIn('status',['verified','done'])) }}
                        </h4>
                    </div>

                    <div class="align-self-center text-center">
                        <i class="ri-check-double-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xxl-3 col-lg-6">
        <div class="main-tiles border-5 card-hover border-0 card o-hidden">
            <div class="custome-4-bg b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">
                            Belum Selesai Dikerjakan
                        </span>
                        <h4 class="mb-0 counter">
                            {{ count($mediaOrders->whereIn('status',['sent','review'])) }}
                        </h4>
                    </div>

                    <div class="align-self-center text-center">
                        <i class="ri-history-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="card o-hidden card-hover">
            <div style="min-width: 100%; height: 380px; overflow-x: auto; overflow-y:hidden; position: relative;">
                <livewire:livewire-line-chart key="{{ $chartMediaOrder->reactiveKey() }}" style="width:100%"
                    :line-chart-model="$chartMediaOrder" />
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="card o-hidden card-hover">
            <div class="card-header border-0 pb-1">
                <div class="card-header-title p-0 d-flex justify-content-between align-items-center">
                    <h4>
                        Agenda Hari ini - Timeline
                    </h4>
                    <h4 class="">
                        @if(count($timelines) > 0)
                        {{ count($timelines) }}
                        Agenda
                        @endif
                    </h4>
                </div>
            </div>
            <div class="">

                @if(count($timelines) > 0)
                <ol class="progtrckr" style="flex-wrap: wrap">
                    @foreach($timelines as $tl)
                    <li
                        class="@if($tl['waktu_pelaksanaan'] < Carbon::now()->isoFormat('HH:mm:ss')) progtrckr-done @else progtrckr-todo @endif">
                        <h5>
                            {{ $tl['nama_acara'] }}
                        </h5>
                        <div class="content d-flex align-items-center gap-2">
                            <i class="ri-map-pin-line"></i>
                            <span>
                                {{ $tl['lokasi'] }}
                            </span>
                        </div>
                        <div class="content d-flex align-items-center gap-2">
                            <i class="ri-calendar-todo-fill"></i>
                            <span>
                                {{ Carbon::parse($tl['tanggal_pelaksanaan'])->isoFormat('DD MMM Y') }} |
                                {{ Carbon::parse($tl['waktu_pelaksanaan'])->isoFormat('HH:mm [WIB]') }}
                            </span>
                        </div>
                        <div class="content d-flex align-items-center gap-2">
                            <i class="ri-government-line"></i>
                            <span>
                                {{ $tl['leading_sector'] }}
                            </span>
                        </div>
                        <div class="content d-flex align-items-center gap-2">
                            <i class="ri-todo-line"></i>
                            <span>
                                <a href="#">
                                    {{ count($tl['orders']) }} Media Order
                                </a>
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ol>
                @else
                <div class="alert alert-primary" role="alert">
                    <h4 class="alert-heading">
                        Hhhmmm!
                    </h4>
                    <p>
                        Sepertinya Tidak Ada Media Order untuk Hari Ini.
                    </p>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
