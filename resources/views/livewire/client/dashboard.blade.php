<?php

use Carbon\Carbon;

?>
<div class="row">
    <div class="col-12">
        <div class="title-header option-title d-sm-flex d-block">
            <h5>
                Hi, {{ auth()->user()->first_name }}. Selamat Datang di Dashboard {{ env('APP_NAME') }}
            </h5>
            <div>
                @if($isMediaUnverified == false)
                <div class="alert alert-success d-flex align-items-center rounded-pill py-2" role="alert">
                    <i class="ri-error-warning-line"></i>
                    <div style="font-size:14px">
                        {{ $isMediaUnverified == false ? 'Media Pers Belum Terverifikasi' : '' }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- chart caard section start -->
    <div class="col-sm-6 col-xxl-3 col-lg-6">
        <div class="main-tiles border-5 border-0  card-hover card o-hidden">
            <a href="{{ route('media-order') }}" class="custome-1-bg b-r-4 card-body">
                <div class="media align-items-center static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">
                            Media Order
                        </span>
                        <h4 class="mb-0 counter">
                            {{ count($mediaOrders) }}
                        </h4>
                    </div>
                    <div class="align-self-center text-center">
                        <i class="ri-database-2-line"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xxl-3 col-lg-6">
        <div class="main-tiles border-5 card-hover border-0 card o-hidden">
            <a href="{{ route('media-order') }}" class="custome-2-bg b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">
                            Proses
                        </span>
                        <h4 class="mb-0 counter">
                            {{ count($mediaOrders->where('status','sent')) }}
                        </h4>
                    </div>
                    <div class="align-self-center text-center">
                        <i class="ri-history-line"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xxl-3 col-lg-6">
        <div class="main-tiles border-5 card-hover border-0  card o-hidden">
            <a href="{{ route('media-order') }}" class="custome-3-bg b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">
                            Verifikasi
                        </span>
                        <h4 class="mb-0 counter">
                            {{ count($mediaOrders->whereIn('status',['review'])) }}
                        </h4>
                    </div>

                    <div class="align-self-center text-center">
                        <i class="ri-chat-3-line"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-sm-6 col-xxl-3 col-lg-6">
        <div class="main-tiles border-5 card-hover border-0 card o-hidden">
            <a href="{{ route('media-order') }}" class="custome-4-bg b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="media-body p-0">
                        <span class="m-0">
                            Selesai
                        </span>
                        <h4 class="mb-0 counter">
                            {{ count($mediaOrders->whereIn('status',['verified','done'])) }}
                        </h4>
                    </div>

                    <div class="align-self-center text-center">
                        <i class="ri-check-double-line"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="card o-hidden card-hover">
            <div class="card-header border-0 pb-1">
                <div class="card-header-title p-0 d-flex justify-content-between align-items-center">
                    <h4>
                        Media Order Hari ini - Timeline
                    </h4>
                    <h4 class="">
                        @if(count($timelines) > 0)
                        {{ count($timelines) }}
                        Media Order
                        @endif
                    </h4>
                </div>
            </div>
            <div class="">

                @if(count($timelines) > 0)
                <div class="table-responsive table-product">
                    <table class="table all-package theme-table" id="table_id">
                        <thead>
                            <tr>
                                <th style="width:10px">Nama Acara</th>
                                <th style="width:20%">Lokasi</th>
                                <th style="width:20%">Waktu</th>
                                <th style="width:20%">Leading Sector</th>
                                <th style="width:20%">
                                    Jumlah & Satuan
                                </th>
                                <th style="width:20%">Media Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timelines as $tl)
                            <tr>
                                <td style="white-space:normal;">{{ $tl['nama_acara'] }}</td>
                                <td>{{ $tl['lokasi'] }}</td>
                                <td>
                                    {{ Carbon::parse($tl['tanggal_pelaksanaan'])->isoFormat('DD MMM Y') }} |
                                    {{ Carbon::parse($tl['waktu_pelaksanaan'])->isoFormat('HH:mm [WIB]') }}
                                </td>
                                <td>{{ $tl['leading_sector'] }}</td>
                                <td>
                                    {{ $tl['jumlah'] }} x
                                    {{ $tl['satuan'] }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('media-order.detail', $tl['order_code']) }}">
                                        Buka Media Order
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-primary" role="alert">
                    <h4 class="alert-heading">Hhhmmm!</h4>
                    <p>Sepertinya Tidak Ada Media Order untuk Hari Ini.</p>
                </div>
                @endif

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

</div>
