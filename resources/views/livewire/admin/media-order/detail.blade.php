<?php

use Carbon\Carbon;

?>
<div>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="title-header option-title">
                                <h5 style="user-select: none; cursor: pointer;">
                                    #{{ $mediaOrder->order_code }}
                                </h5>
                            </div>
                            <div class="row">
                                <div class="col-12 overflow-hidden mb-4">
                                    <div class="order-left-image">
                                        {{-- <div class="tracking-product-image">
                                            <img src="assets/images/profile/1.jpg"
                                                class="img-fluid w-100 blur-up lazyload" alt="">
                                        </div> --}}

                                        <div class="order-image-contain">
                                            <h4>
                                                {{ $mediaOrder->nama_acara }}
                                            </h4>
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="tracker-number">
                                                        <p>
                                                            Lokasi :
                                                            <span>
                                                                {{ $mediaOrder->lokasi }}
                                                            </span>
                                                        </p>
                                                        <p>
                                                            Waktu :
                                                            <span>
                                                                {{
                                                                Carbon::parse($mediaOrder->tanggal_pelaksanaan)->isoFormat('DD
                                                                MMMM Y') }}
                                                            </span>
                                                            <span>
                                                                {{
                                                                Carbon::parse($mediaOrder->waktu_pelaksanaan)->isoFormat('HH:mm
                                                                [WIB]') }}
                                                            </span>
                                                        </p>
                                                        <p>
                                                            Leading Sector :
                                                            <span>
                                                                {{ $mediaOrder->leading_sector }}
                                                            </span>
                                                        </p>
                                                        <p>
                                                            Status :
                                                            @if($mediaOrder->status == 'sent')
                                                            <span class="badge badge-success text-white">
                                                                Media Order Diterima
                                                            </span>
                                                            @elseif($mediaOrder->status == 'review')
                                                            <span class="badge badge-warning text-white">
                                                                Laporan Diterima
                                                            </span>
                                                            @elseif($mediaOrder->status == 'rejected')
                                                            <span class="badge badge-warning text-white">
                                                                Dikembalikan
                                                            </span>
                                                            @elseif($mediaOrder->status == 'verified')
                                                            <span class="badge badge-primary text-white">
                                                                Laporan Selesai
                                                            </span>
                                                            @elseif($mediaOrder->status == 'done')
                                                            <span class="badge badge-primary text-white">
                                                                Selesai
                                                            </span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    @if($mediaOrder->status == 'sent')
                                                    <h5>
                                                        Harap unggah hasil liputan sebelum
                                                        <span
                                                            x-init="startTimer('{{ Carbon::parse($mediaOrder->deadline) }}','timer{{ $mediaOrder->id }}')"
                                                            id="timer{{ $mediaOrder->id }}">
                                                        </span>.
                                                    </h5>
                                                    @else
                                                    <h5>
                                                        Timeline Media Order
                                                    </h5>
                                                    @endif
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="tracker-number">
                                                        <p>
                                                            Nama Media :
                                                            <span>
                                                                <a
                                                                    href="{{ route('media.detail', $media->unique_id) }}">
                                                                    {{ $media->nama_media }}
                                                                </a>
                                                            </span>
                                                        </p>
                                                        <p>
                                                            Jenis Media :
                                                            <span>
                                                                {{ $media->jenis_media }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <ol class="progtrckr ps-0">
                                        @foreach($logs as $log)
                                        <li
                                            class="@if(count($logs) > 1 && ($loop->last == false || in_array($mediaOrder->status, ['done','verified']))) progtrckr-done @else progtrckr-todo @endif">
                                            <h5 class="text-uppercase">
                                                @if($log->status == 'sent')
                                                <span class="fw-bold">
                                                    Dikirim
                                                </span>
                                                @elseif($log->status == 'review')
                                                <span class="fw-bold">
                                                    Menunggu Review
                                                </span>
                                                @elseif($log->status == 'rejected')
                                                <span class="fw-bold">
                                                    Dikembalikan
                                                </span>
                                                @elseif($log->status == 'verified')
                                                <span class="fw-bold">
                                                    Laporan Selesai
                                                </span>
                                                @elseif($log->status == 'done')
                                                <span class="fw-bold">
                                                    Selesai
                                                </span>
                                                @endif
                                            </h5>
                                            <p @if(count($logs)> 1 && ($loop->last == false ||
                                                in_array($mediaOrder->status, ['done','verified'])))
                                                style="margin-left:30px"
                                                @else
                                                style="margin-left:0px"
                                                @endif>
                                                {{ $log->note }}
                                            </p>
                                            <h6>
                                                {{ Carbon::parse($log->created_at)->isoFormat('dddd, DD MMMM Y, HH:mm
                                                [WIB]') }}
                                            </h6>
                                        </li>
                                        @endforeach
                                    </ol>
                                </div>

                                <div class="col-12 col-md-8 overflow-visible">
                                    <div class="tracker-table">
                                        <div class="table-responsive border">
                                            <table class="table w-100">
                                                <thead>
                                                    <tr class="table-head">
                                                        <th scope="col" class="text-center" style="width:100px">
                                                            Jenis Eviden
                                                        </th>
                                                        <th scope="col" class="text-center" style="width:200px">
                                                            Lampiran
                                                        </th>
                                                        <th scope="col" class="text-center" style="width:200px">
                                                            Deskripsi
                                                        </th>
                                                        <th scope="col" class="text-center" style="width:50px">
                                                            Opsi
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse($evidences as $evi)
                                                    <tr>
                                                        <td>
                                                            <h6>
                                                                <div class="fw-bold">
                                                                    @if($evi->type == 'image')
                                                                    Gambar
                                                                    @elseif($evi->type == 'link')
                                                                    Link / Tautan
                                                                    @endif
                                                                </div>
                                                                <div class="mt-1" style="font-size: 10px">
                                                                    {{ Carbon::parse($evi->created_at)->isoFormat('DD
                                                                    MMM Y, HH:mm [WIB]') }}
                                                                </div>
                                                            </h6>
                                                        </td>
                                                        <td>
                                                            <h6>
                                                                @if($evi->type == 'image')
                                                                <a href="{{ $evi->url }}" target="_blank"
                                                                    class="text-center"
                                                                    style="width:100%; height:50px; object-fit:contain">
                                                                    <img src="{{ $evi->url }}" class="img-fluid"
                                                                        style="width:100%; height:50px; object-fit:contain">
                                                                </a>
                                                                @elseif($evi->type == 'link')
                                                                <a href="{{ $evi->url }}" target="_blank"
                                                                    style="max-width:300px" class="text-truncate">
                                                                    {{ $evi->url }}
                                                                </a>
                                                                @endif
                                                            </h6>
                                                        </td>
                                                        <td>
                                                            <p style="font-size: 12px; white-space: normal">
                                                                {{ $evi->description }}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <div class="text-center">
                                                                @if($mediaOrder->status == 'sent')
                                                                <a href="javascript:void(0)"
                                                                    wire:click="confirmDeleteEvidence({{ $evi->id }})">
                                                                    <i class="ri-delete-bin-5-line"></i>
                                                                </a>
                                                                @else
                                                                -
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="100">
                                                            <h6 class="text-center">
                                                                Belum ada Bukti
                                                            </h6>
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end border-0 pb-0 d-flex justify-content-end">
                            @if($mediaOrder->status == 'review')
                            <button class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#exampleModalToggle" wire:click.prevent='openRespond'>
                                Verifikasi Laporan
                            </button>
                            @endif
                            @if($mediaOrder->status == 'sent' && $mediaOrder->deadline <= now()) <button
                                class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#openAddDuration"
                                wire:click.prevent='openAddDuration'>
                                Tambah Durasi Deadline
                                </button>
                                @endif
                                <a href="{{ route('a.media-order') }}" class="btn btn-outline">
                                    Kembali
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($mediaOrder->status == 'review')
    <div wire:ignore.self class="modal fade theme-modal remove-coupon" id="exampleModalToggle" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header d-block text-start">
                    <h5 class="modal-title w-100" id="exampleModalLabel22">
                        Verifikasi Laporan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="row" wire:submit.prevent="comfirmRespondMediaOrder">
                        <div class="col-12">
                            <label class="col-form-label">Status:</label>
                            <select class="form-select" wire:model.live="input.status">
                                <option value="" hidden>
                                    Pilih Status
                                </option>
                                <option value="rejected">
                                    Tolak
                                </option>
                                <option value="verified">
                                    Verifikasi
                                </option>
                            </select>

                            @error('input.status')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        @if ($showSelect == 'rejected')
                        <div class="col-12">
                            <label for="link" class="col-form-label">Penjelasan:</label>
                            <textarea class="form-control" id="link" autocomplete="off" placeholder="Penjelasan"
                                style="height: 250px" wire:model="input.note"></textarea>

                            @error('input.note')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        @elseif ($showSelect == 'verified')
                        <div class="col-12 mb-4 mt-4">
                            <label class="col-form-label" style="font-size: 1.1rem;">Informasi Media:</label>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover border">
                                    <tbody>
                                        <tr>
                                            <td width="30%" class="fw-bold">Nama Media</td>
                                            <td width="5%">:</td>
                                            <td>{{ $media->nama_media }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Nomor Registrasi</td>
                                            <td>:</td>
                                            <td>{{ $media->unique_id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Jenis Media</td>
                                            <td>:</td>
                                            <td>{{ $media->jenis_media }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="col-form-label" style="font-size: 1.1rem;">Jenis Publikasi:</label>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover border">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="40%">Ukuran</th>
                                            <th width="60%">Harga (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="publikasiType" id="publikasi1" value="1/4" wire:model="input.jenis_publikasi">
                                                    <label class="form-check-label" for="publikasi1">1/4 Halaman</label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model="input.harga_1_4" placeholder="Masukkan harga">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="publikasiType" id="publikasi2" value="1/2" wire:model="input.jenis_publikasi">
                                                    <label class="form-check-label" for="publikasi2">1/2 Halaman</label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model="input.harga_1_2" placeholder="Masukkan harga">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="publikasiType" id="publikasi3" value="1" wire:model="input.jenis_publikasi">
                                                    <label class="form-check-label" for="publikasi3">1 Halaman Penuh</label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model="input.harga_1" placeholder="Masukkan harga">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="publikasiType" id="publikasi4" value="2" wire:model="input.jenis_publikasi">
                                                    <label class="form-check-label" for="publikasi4">2 Halaman</label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model="input.harga_2" placeholder="Masukkan harga">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="border rounded p-3 mt-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Jumlah Harga:</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" wire:model="input.total_harga" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">PPN (11%):</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" wire:model="input.ppn" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Total Pembayaran:</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control fw-bold" wire:model="input.total_pembayaran" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-animation btn-md fw-bold" data-bs-dismiss="modal"
                        wire:click="closeModal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-animation btn-md fw-bold"
                        wire:click="comfirmRespondMediaOrder">
                        Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div>
        <div wire:ignore.self class="modal fade theme-modal remove-coupon" id="openAddDuration" aria-hidden="true"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header d-block text-start">
                        <h5 class="modal-title w-100" id="exampleModalLabel22">
                            Tambah Durasi Deadline
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            wire:click="closeModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="row" wire:submit.prevent="comfirmRespondMediaOrder">
                            <div class="col-12">
                                <label class="col-form-label">Hari:</label>
                                <input class="form-control" type="number" min="1" max="3" autocomplete="off"
                                    placeholder="Pesan" wire:model="addDuration.days" />

                                @error('addDuration.days')
                                <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            {{-- <div class="col-4">
                                <label class="col-form-label">Jam:</label>
                                <input class="form-control" type="number" min="0" max="60" autocomplete="off"
                                    placeholder="Pesan" wire:model="addDuration.hours" />

                                @error('addDuration.hours')
                                <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-4">
                                <label class="col-form-label">Menit:</label>
                                <input class="form-control" type="number" min="0" max="60" autocomplete="off"
                                    placeholder="Pesan" wire:model="addDuration.minutes" />

                                @error('addDuration.minutes')
                                <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div> --}}
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-animation btn-md fw-bold"
                            data-bs-dismiss="modal" wire:click="closeModal">
                            Batal
                        </button>
                        <button type="button" class="btn btn-animation btn-md fw-bold" wire:click="confirmAddDuration">
                            Tambahkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .countdown {
            cursor: pointer;
            margin-top: 5px;
            /* font-size: 12px; */
            user-select: none;
            color: #019289;
        }

        .countdown-end {
            cursor: pointer;
            margin-top: 5px;
            /* font-size: 10px; */
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


            document.getElementById(elemID).innerHTML = "<div class='countdown'>" + days + " <span> hari </span> " + hours + " <span>jam</span> " + minutes + " <span>mnt </span> " + seconds + "<span> dtk</span> </div>";

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
