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
                                                @if($mediaOrder->jumlah && $mediaOrder->satuan)
                                                <p>
                                                    Jumlah & Satuan yang Diorder :
                                                    <span class="badge badge-info">
                                                        {{ $mediaOrder->jumlah }} {{ $mediaOrder->satuan }}
                                                    </span>
                                                </p>
                                                @endif
                                                <p>
                                                    Status :

                                                    @if($mediaOrder->status == 'sent')
                                                    <span class="badge badge-success text-white">
                                                        Belum Dikerjakan
                                                    </span>
                                                    @elseif($mediaOrder->status == 'review')
                                                    <span class="badge badge-warning text-white">
                                                        Laporan Dikirim
                                                    </span>
                                                    @elseif($mediaOrder->status == 'rejected')
                                                    <span class="badge badge-warning text-white">
                                                        Dikembalikan Admin
                                                    </span>
                                                    @elseif($mediaOrder->status == 'verified')
                                                    <span class="badge badge-primary text-white">
                                                        Terverifikasi
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
                                                    Dikirim oleh Admin
                                                </span>
                                                @elseif($log->status == 'review')
                                                <span class="fw-bold">
                                                    Laporan Dikirim
                                                </span>
                                                @elseif($log->status == 'rejected')
                                                <span class="fw-bold">
                                                    Dikembalikan Admin
                                                </span>
                                                @elseif($log->status == 'verified')
                                                <span class="fw-bold">
                                                    Terverifikasi
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
                                                            Jenis Bukti
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
                                                                <img src="{{ $evi->url }}" class="img-fluid"
                                                                    style="width:100%; height:50px; object-fit:contain">
                                                                @elseif($evi->type == 'link')
                                                                <a href="{{ $evi->url }}" target="_blank"
                                                                    style="width:250px" class="text-truncate">
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
                                                                @if($mediaOrder->status == 'sent' || $mediaOrder->status
                                                                == 'rejected')
                                                                @if($evi->type == 'image')
                                                                <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#modalEvidence"
                                                                    wire:click="editEvidence({{ $evi->id }})">
                                                                    <i class="ri-edit-2-line text-primary"></i>
                                                                </a>
                                                                @endif
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
                            @if(($mediaOrder->status == 'sent' || $mediaOrder->status == 'rejected') &&
                            $mediaOrder->deadline >= now())
                            <button class="btn btn-primary me-3" data-bs-toggle="modal"
                                data-bs-target="#exampleModalToggle" wire:click="addEvidence()">
                                Unggah Bukti
                            </button>
                            @elseif(($mediaOrder->status == 'sent' || $mediaOrder->status == 'rejected') &&
                            $mediaOrder->deadline <= now()) <button class="btn btn-secondary me-3">
                                Sudah Lewat Batas
                                </button>
                                @endif

                                @if(count($evidences) > 0 && ($mediaOrder->status == 'sent' || $mediaOrder->status ==
                                'rejected'))
                                <button class="btn btn-primary me-3" wire:click="comfirmSendEvidence()">
                                    Kirim Laporan
                                </button>
                                @endif
                                <a href="{{ route('media-order') }}" class="btn btn-outline">
                                    Kembali
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($mediaOrder->status == 'sent' || $mediaOrder->status == 'rejected')
    <div wire:ignore.self class="modal fade theme-modal remove-coupon" id="exampleModalToggle" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header d-block text-start">
                    <h5 class="modal-title w-100" id="exampleModalLabel22">
                        Tambah Bukti
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="row" wire:submit.prevent="uploadEvidence">
                        <div class="col-12">
                            <label class="col-form-label">Jenis Bukti:</label>
                            <select class="form-select" wire:model.live="input.type">
                                <option value="" hidden>
                                    Pilih Jenis Bukti
                                </option>
                                <option value="image">
                                    Gambar
                                </option>
                                <option value="link">
                                    Link / Tautan
                                </option>
                            </select>

                            @error('input.type')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        @if($input['type'] == 'link')
                        <div class="col-12">
                            <label for="link" class="col-form-label">Link / Tautan:</label>
                            <input type="text" class="form-control" id="link" autocomplete="off"
                                placeholder="Link / Tautan" wire:model="input.link">
                            <small class="text-muted">
                                Contoh: https://oganilirkab.go.id
                            </small>

                            @error('input.link')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        @elseif($input['type'] == 'image')
                            @foreach($imageInputs as $index => $imageInput)
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="card-title mb-0">Bukti {{ $index + 1 }}</h6>
                                    @if(count($imageInputs) > 1)
                                    <button type="button" class="btn btn-danger btn-sm" wire:click="removeImageInput({{ $index }})">
                                        <i class="ri-delete-bin-line"></i> Hapus
                                    </button>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="files_{{ $index }}" class="form-label">File Gambar:</label>
                                    <input type="file" class="form-control" id="files_{{ $index }}"
                                        accept=".jpeg,.jpg,.png" wire:model="imageInputs.{{ $index }}.file">
                                    <small class="text-muted">
                                        <i class="ri-information-line"></i>
                                        <strong>Jenis file yang diterima:</strong> JPEG, JPG, PNG |
                                        <strong>Ukuran maksimal:</strong> 2MB
                                    </small>
                                    @error("imageInputs.{$index}.file")
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label for="description_{{ $index }}" class="form-label">Deskripsi:</label>
                                    <textarea class="form-control" id="description_{{ $index }}"
                                        wire:model="imageInputs.{{ $index }}.description"
                                        rows="3"
                                        placeholder="Masukkan deskripsi gambar..."></textarea>
                                    @error("imageInputs.{$index}.description")
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            @endforeach

                            <div class="col-12">
                                <button type="button" class="btn btn-primary mt-3 w-100" wire:click="addImageInput">
                                    <i class="ri-add-line"></i> Tambah Bukti Baru
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-animation btn-md fw-bold" data-bs-dismiss="modal"
                        wire:click="closeModal">
                        Tutup
                    </button>
                    <button type="button" class="btn btn-animation btn-md fw-bold" wire:click="uploadEvidence">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade theme-modal remove-coupon" id="modalEvidence" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header d-block text-start">
                    <h5 class="modal-title w-100" id="exampleModalLabel22">
                        Edit Bukti
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    @if($detailEvidence)
                    <form class="row gap-2" wire:submit.prevent="saveEditedEvidence">
                        <div class="col-12">
                            <a href="{{ asset($detailEvidence->url) }}" class="text-center w-100" target="_blank">
                                <img src="{{ asset($detailEvidence->url) }}" class="img-thumbnail w-100"
                                    style="height:300px; object-fit:contain">
                            </a>
                        </div>
                        <div class="col-12">
                            <textarea class="form-control" wire:model="detailEvidence.description"
                                placeholder="Deskripsi" style="min-height: 200px;"></textarea>
                            @error('detailEvidence.description')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </form>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-animation btn-md fw-bold" data-bs-dismiss="modal"
                        wire:click="closeModal">
                        Tutup
                    </button>
                    <button type="button" class="btn btn-animation btn-md fw-bold" wire:click="saveEditedEvidence">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

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
