<?php

use Carbon\Carbon;

?>
<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="title-header title-header-block package-card">
                        <div>
                            <h5>
                                {{ $pers->nama_media }}
                            </h5>
                        </div>
                        <div class="card-order-section">
                            <ul>
                                <li>
                                    #{{ $pers->unique_id }}
                                </li>
                                <li>
                                    {{ $pers->jenis_media }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="bg-inner cart-section order-details-table">
                        <div class="row g-4">
                            <div class="col-xl-7">
                                <div class="table-responsive table-details">
                                    <table class="table cart-table table-borderless">
                                        <thead>
                                            <tr>
                                                <th colspan="3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>
                                                            Media Order
                                                        </div>
                                                        <div class="">
                                                            <input type="search" class="form-control"
                                                                placeholder="Pencarian..." wire:model.live="search" />
                                                        </div>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($mediaOrders as $mo)
                                            <tr class="table-order border-bottom">
                                                <td style="min-width:200px;">
                                                    <a href="{{ route('a.media-order.detail', $mo->order_code) }}">
                                                        <h5 class="fw-semibold"
                                                            style="white-space: normal; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">
                                                            {{ $mo->nama_acara }}
                                                        </h5>
                                                    </a>
                                                    <div class="content d-flex align-items-center gap-2 mt-1">
                                                        <i class="ri-links-line"></i>
                                                        <span style="font-size: 12px">
                                                            <a
                                                                href="{{ route('a.media-order.detail', $mo->order_code) }}">
                                                                Buka Media Order
                                                            </a>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td style="min-width:200px">
                                                    <div class="mb-1">
                                                        <p>Kode Order</p>
                                                        <h5 class="mt-0">
                                                            {{ $mo->order_code }}
                                                        </h5>
                                                    </div>
                                                    <div class="mb-1">
                                                        <p>Lokasi</p>
                                                        <h5 class="mt-0" style="white-space: normal">
                                                            {{ $mo->lokasi }}
                                                        </h5>
                                                    </div>
                                                </td>
                                                <td style="min-width:50px">
                                                    <div class="mb-1">
                                                        <p>Waktu</p>
                                                        <h5 class="mt-0" style="white-space: normal">
                                                            {{ Carbon::parse($mo->tanggal_pelaksanaan)->isoFormat('DD
                                                            MMM Y') }}, <br>
                                                            {{ Carbon::parse($mo->waktu_pelaksanaan)->isoFormat('HH:mm
                                                            [WIB]') }}
                                                        </h5>
                                                    </div>
                                                    <div class="mb-1">
                                                        <p>Status</p>
                                                        <h5 class="mt-0" style="white-space: normal">
                                                            @if($mo->status == 'sent')
                                                            <span class="badge badge-success">
                                                                Dikirim
                                                            </span>
                                                            <div x-init="startTimer('{{ Carbon::parse($mo->deadline) }}','timer{{ $mo->id }}')"
                                                                id="timer{{ $mo->id }}">
                                                            </div>
                                                            @elseif($mo->status == 'review')
                                                            <span class="badge badge-warning">
                                                                Menunggu Review
                                                            </span>
                                                            @elseif($mo->status == 'rejected')
                                                            <span class="badge badge-warning">
                                                                Dikembalikan
                                                            </span>
                                                            @elseif($mo->status == 'verified')
                                                            <span class="badge badge-primary">
                                                                Terverifikasi
                                                            </span>
                                                            @elseif($mo->status == 'done')
                                                            <span class="badge badge-primary">
                                                                Selesai
                                                            </span>
                                                            @endif
                                                        </h5>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="100">
                                                    <h3 class="text-center">
                                                        Tidak Ada Media Order
                                                    </h3>
                                                </td>
                                            </tr>
                                            @endforelse

                                        </tbody>

                                        @if($mediaOrders->hasPages())
                                        <tfoot>
                                            <tr>
                                                <td colspan="100">
                                                    {{ $mediaOrders->links() }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                        @endif

                                    </table>
                                </div>
                            </div>

                            <div class="col-xl-5">
                                <div class="order-success"
                                    style="height:calc(100vh - 300px); overflow-x:hidden; overflow-y:auto">
                                    <div class="row g-4">
                                        <h4>Informasi Perusahaan</h4>
                                        <ul class="order-details">
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Nama Perusahaan
                                                    </div>
                                                    <div>
                                                        : {{ $pers->nama_perusahaan }}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Nama Media
                                                    </div>
                                                    <div>
                                                        : {{ $pers->nama_media }}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Jenis Media
                                                    </div>
                                                    <div>
                                                        : {{ $pers->jenis_media }}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Alias
                                                    </div>
                                                    <div>
                                                        : {{ $pers->alias }}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Email Media
                                                    </div>
                                                    <div>
                                                        : {{ $pers->email }}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Website
                                                    </div>
                                                    <div>
                                                        : {{ $pers->website }}
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>

                                        <h4>Profil Media</h4>
                                        <ul class="order-details">
                                            <li>{{ $pers->profil_perusahaan }}</li>
                                        </ul>

                                        <h4>Alamat Media</h4>
                                        <ul class="order-details">
                                            <li>{{ $pers->alamat_media }}</li>
                                        </ul>

                                        <h4>Informasi Lanjutan</h4>
                                        <ul class="order-details">
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Nomor NPWP
                                                    </div>
                                                    <div>
                                                        : {{ $pers->no_npwp }}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Nomor Referensi Bank
                                                    </div>
                                                    <div>
                                                        : {{ $pers->no_ref_bank }}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Nomor Giro Perusahaan
                                                    </div>
                                                    <div>
                                                        : {{ $pers->no_giro_perusahaan }}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Cakupan Media
                                                    </div>
                                                    <div>
                                                        : {{ $pers->cakupan_media }}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Jumlah Oplah
                                                    </div>
                                                    <div>
                                                        : {{ $pers->jumlah_oplah }}
                                                    </div>
                                                    @if($pers->file_jumlah_oplah)
                                                    <a href="{{ asset($pers->file_jumlah_oplah) }}"
                                                        style="font-size: 12px" class="d-flex align-items-center gap-1"
                                                        target="_blank">
                                                        <i class="ri-links-line"></i>
                                                        <span>
                                                            Lampiran
                                                        </span>
                                                    </a>
                                                    @endif
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Sebaran Oplah
                                                    </div>
                                                    <div>
                                                        : {{ $pers->sebaran_oplah }}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Status Wartawan
                                                    </div>
                                                    <div>
                                                        : {{ $pers->status_wartawan }}
                                                    </div>
                                                    @if($pers->file_status_wartawan)
                                                    <a href="{{ asset($pers->file_status_wartawan) }}"
                                                        style="font-size: 12px" class="d-flex align-items-center gap-1"
                                                        target="_blank">
                                                        <i class="ri-links-line"></i>
                                                        <span>
                                                            Lampiran
                                                        </span>
                                                    </a>
                                                    @endif
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Kompetensi Wartawan
                                                    </div>
                                                    <div>
                                                        : {{ $pers->kompetensi_wartawan }}
                                                    </div>
                                                    @if($pers->file_kompetensi_wartawan)
                                                    <a href="{{ asset($pers->file_kompetensi_wartawan) }}"
                                                        style="font-size: 12px" class="d-flex align-items-center gap-1"
                                                        target="_blank">
                                                        <i class="ri-links-line"></i>
                                                        <span>
                                                            Lampiran
                                                        </span>
                                                    </a>
                                                    @endif
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Status Dewan Pers
                                                    </div>
                                                    <div>
                                                        : {{ $pers->status_dewan_pers }}
                                                    </div>
                                                    @if($pers->file_status_dewan_pers)
                                                    <a href="{{ asset($pers->file_status_dewan_pers) }}"
                                                        style="font-size: 12px" class="d-flex align-items-center gap-1"
                                                        target="_blank">
                                                        <i class="ri-links-line"></i>
                                                        <span>
                                                            Lampiran
                                                        </span>
                                                    </a>
                                                    @endif
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Kantor
                                                    </div>
                                                    <div>
                                                        : {{ $pers->kantor }}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Frekuensi Terbitan
                                                    </div>
                                                    <div>
                                                        : {{ $pers->frekuensi_terbitan }}
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        Terbitan 3 Edisi Terakhir
                                                    </div>
                                                    <div>
                                                        : {{ $pers->terbitan_3_edisi_terakhir }}
                                                    </div>
                                                    @if($pers->file_terbitan_3_edisi_terakhir)
                                                    <a href="{{ asset($pers->file_terbitan_3_edisi_terakhir) }}"
                                                        style="font-size: 12px" class="d-flex align-items-center gap-1"
                                                        target="_blank">
                                                        <i class="ri-links-line"></i>
                                                        <span>
                                                            Lampiran
                                                        </span>
                                                    </a>
                                                    @endif
                                                </div>
                                            </li>
                                            @foreach($arrRegFiles as $fl)
                                            <li>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <div class="" style="width:110px">
                                                        {{ str()->headline($fl['file_type']) }}
                                                    </div>
                                                    <div>
                                                        <a href="{{ $fl['file_path'] }}" style="font-size: 12px"
                                                            class="d-flex align-items-center gap-1" target="_blank">
                                                            :
                                                            <i class="ri-links-line"></i>
                                                            <span>
                                                                {{-- {{ $fl['file_name'] }} --}}
                                                                Lampiran
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- section end -->
                </div>
            </div>
        </div>
    </div>


    @push('styles')
    <style>
        .countdown {
            white-space: nowrap;
            cursor: pointer;
            margin-top: 5px;
            font-size: 12px;
            user-select: none;
        }

        .countdown-end {
            white-space: nowrap;
            cursor: pointer;
            margin-top: 5px;
            font-size: 10px;
            user-select: none;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function startTimer(date, elemID) {
            // Set the date we're counting down to
            // var countDownDate = new Date("Jan 5, 2021 15:37:25").getTime();
            var countDownDate = new Date(date).getTime();

            // Update the count down every 1 second
            var x = setInterval(function() {

            // Get todays date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"


            document.getElementById(elemID).innerHTML = "<div class='countdown'>" + days + " <span> hr </span> " + hours + " <span>jam</span> " + minutes + " <span>mnt </span> " + seconds + "<span> dtk</span> </div>";

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById(elemID).innerHTML = "<div class='countdown-end text-danger'>Sudah Lewat Batas</div>";
            }
            }, 1000);
        }
    </script>
    @endpush
</div>
