<?php
use Carbon\Carbon;
?>
<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title flex-wrap align-items-center justify-content-between gap-2">
                        <h5>
                            Agenda Manual <br>
                        </h5>

                        <a class="align-items-center btn btn-outline d-flex" href="javascript:void(0)"
                            data-bs-toggle="modal" data-bs-target="#exampleModalToggle" wire:click="addData()" wire:ignore>
                            Buat Agenda Manual
                        </a>
                    </div>

                    <form class="mb-2 d-flex align-items-center justify-content-between flex-grow-1 flex-wrap gap-1"
                        wire:submit.prevent="goSearch">
                        <select class="form-control" style="max-width:32%" wire:model.live="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="sent">Dikirim</option>
                            <option value="review">Menunggu Review</option>
                            <option value="rejected">Dikembalikan</option>
                            <option value="verified">Terverifikasi</option>
                            <option value="done">Selesai</option>
                        </select>
                        <input type="date" class="form-control" style="max-width:32%" wire:model.live="filterDate" />
                        <input type="search" class="form-control" style="max-width:32%" placeholder="Pencarian..."
                            wire:model="search" />
                        <div class="" wire:click.prevent="resetSearch" style="cursor: pointer">
                            <i class="ri-close-circle-line"></i>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="col-12">
                        @if(count($agendas) > 0)
                        <ol class="progtrckr ps-0" style="flex-wrap: wrap">
                            @foreach($agendas as $agenda)
                            <li class="@if($agenda['waktu_pelaksanaan'] < Carbon::now()->isoFormat('HH:mm:ss')) progtrckr-done @else progtrckr-todo @endif border-bottom pt-2 pb-3 ms-2"
                                style="cursor: pointer">
                                <div class=" position-relative">
                                    <div class="position-absolute d-flex align-items-center gap-1"
                                        style="bottom:0px; right:5px" data-bs-toggle="modal"
                                        data-bs-target="#modalAddOrder"
                                        wire:click.prevent="openWizardAdd({{ $agenda['id'] }})">
                                        <i class="ri-add-circle-line text-success" style="font-size: 20px"></i>
                                        <span class="text-success">
                                            Media Order
                                        </span>
                                    </div>
                                    <h5>
                                        {{ $agenda['nama_acara'] }}
                                    </h5>
                                    <div class="content d-flex align-items-center gap-2">
                                        <i class="ri-map-pin-line"></i>
                                        <span>
                                            {{ $agenda['lokasi'] ?? '' }}
                                        </span>
                                    </div>
                                    <div class="content d-flex align-items-center gap-2">
                                        <i class="ri-calendar-todo-fill"></i>
                                        <span>
                                            {{ Carbon::parse($agenda['tanggal_pelaksanaan'])->isoFormat('DD MMMM Y') }} |
                                            {{ Carbon::parse($agenda['waktu_pelaksanaan'])->isoFormat('HH:mm [WIB]') }}
                                        </span>
                                    </div>
                                    <div class="content d-flex align-items-center gap-2">
                                        <i class="ri-government-line"></i>
                                        <span>
                                            {{ $agenda['leading_sector'] ?? '' }}
                                        </span>
                                    </div>
                                    <div class="content d-flex align-items-center gap-2">
                                        <span>
                                            Media Order :
                                        </span>
                                        <span>
                                            {{ $agenda['order_count'] ?? '' }}
                                        </span>
                                    </div>
                                </div>
                                @if(count($agenda['orders']) > 0)
                                <div class="content">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="bg-primary">
                                                <tr>
                                                    <th style="font-size: 13px; width:200px" class="p-2 text-center">
                                                        Nama Media
                                                    </th>
                                                    <th style="font-size: 13px; width:200px" class="p-2 text-center">
                                                        Kode Order
                                                    </th>
                                                    <th style="font-size: 13px; width:150px" class="p-2 text-center">
                                                        Status
                                                    </th>
                                                    <th style="font-size: 13px; width:1px" class="p-2 text-center">

                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($agenda['orders'] as $ord)
                                                <tr>
                                                    <td style="font-size: 13px" class="p-2">
                                                        {{ $ord['nama_media'] }}
                                                    </td>
                                                    <td style="font-size: 13px" class="p-2 text-center">
                                                        {{ $ord['order_code'] }}
                                                    </td>
                                                    <td style="font-size: 13px" class="p-2 text-center">
                                                        @if($ord['status'] == 'sent')
                                                        <span class="badge badge-success">
                                                            Dikirim
                                                        </span>
                                                        <div>
                                                            @if($ord['deadline'])
                                                            {{ Carbon::parse($ord['deadline'])->isoFormat('DD MMMM Y,
                                                            HH:mm
                                                            [WIB]') }}
                                                            @else
                                                            <span class="text-danger" style="font-size: 11px">
                                                                Sudah Lewat Batas
                                                            </span>
                                                            @endif
                                                        </div>
                                                        @elseif($ord['status'] == 'review')
                                                        <span class="badge badge-warning">
                                                            Menunggu Review
                                                        </span>
                                                        @elseif($ord['status'] == 'verified')
                                                        <span class="badge badge-primary">
                                                            Terverifikasi
                                                        </span>
                                                        @elseif($ord['status'] == 'done')
                                                        <span class="badge badge-primary">
                                                            Selesai
                                                        </span>
                                                        @endif
                                                    </td>
                                                    <td style="font-size: 13px" class="p-2 text-center">
                                                        <a
                                                            href="{{ route('a.media-order.detail', $ord['order_code']) }}">
                                                            Lihat Media Order
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                            </li>
                            @endforeach
                        </ol>
                        @else
                        <div class="">
                            <div class="alert alert-success" role="alert">
                                <h4 class="alert-heading">Hmmm!</h4>
                                <p>
                                    Tidak ada jadwal untuk hari ini.
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade theme-modal remove-coupon" id="exampleModalToggle" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header d-block text-start">
                    <h5 class="modal-title w-100" id="exampleModalLabel22">
                        @if($inputType == 'create')
                        Tambah Agenda Manual
                        @elseif($inputType == 'update')
                        Edit Agenda Manual
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    @if($detail)
                    <form class="row" wire:submit.prevent="saveData">
                        <div class="col-12 col-xl-12">
                            <label for="recipient-name" class="col-form-label">
                                Nama Acara
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="recipient-name" autocomplete="off"
                                placeholder="Nama Acara" wire:model="detail.nama_acara">

                            @error('detail.nama_acara')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-xl-12">
                            <label for="recipient-name" class="col-form-label">
                                Lokasi
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="recipient-name" autocomplete="off"
                                placeholder="Lokasi" wire:model="detail.lokasi">

                            @error('detail.lokasi')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12 col-xl-6">
                            <label for="recipient-name" class="col-form-label">
                                Tanggal Pelaksanaan Awal
                            </label>
                            <input type="date" class="form-control" wire:model="detail.tanggal_pelaksanaan">

                            @error('detail.tanggal_pelaksanaan')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12 col-xl-6">
                            <label for="recipient-name" class="col-form-label">
                                Tanggal Pelaksanaan Akhir
                            </label>
                            <input type="date" class="form-control" wire:model="detail.tanggal_pelaksanaan_akhir">

                            @error('detail.tanggal_pelaksanaan_akhir')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12 col-xl-12">
                            <label for="recipient-name" class="col-form-label">
                                Waktu Pelaksanaan
                            </label>
                            <input type="time" class="form-control" wire:model="detail.waktu_pelaksanaan">

                            @error('detail.waktu_pelaksanaan')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12 col-xl-12">
                            <label for="recipient-name" class="col-form-label">
                                Leading Sector
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="recipient-name" autocomplete="off"
                                placeholder="Leading Sector" wire:model="detail.leading_sector">

                            @error('detail.leading_sector')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                    </form>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-animation btn-md fw-bold" data-bs-dismiss="modal"
                        wire:click="closeModal">
                        Tutup
                    </button>
                    <button type="button" class="btn btn-animation btn-md fw-bold" wire:click="saveData">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade theme-modal remove-coupon" id="modalAddOrder" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl  modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-block text-start">
                    <h5 class="modal-title w-100" id="exampleModalLabel22">
                        @if($selectedAgenda)
                        Tambah Media Order
                        @endif
                    </h5>
                    <button type="button" class="btn-close" style="top:10px; right:10px;" data-bs-dismiss="modal"
                        aria-label="Close" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <form class="d-flex align-items-center" wire:submit='goSearch'>
                            <input type="search" class="form-control rounded-0 rounded-start"
                                placeholder="Pencarian Media Pers..." wire:model.live="searchMedia">
                            <button type="submit" class="btn btn-primary rounded-0 rounded-end"
                                style="height: 42px !important;">
                                <i class="ri-search-line"></i>
                            </button>
                        </form>
                    </div>
                    <div class="mb-1">
                        Pilih Media Pers untuk ditambahkan ke Media Order
                    </div>
                    <div class="row">
                        @foreach($arrMediaPers as $pers)
                        <div wire:key='{{ $pers->id }}' class="col-12 col-md-6">
                            <div class="card border o-hidden card-hover @if(in_array($pers['id'], $selectedMediaPers)) border-success @endif"
                                style="cursor: pointer" wire:click.prevent="addMedia({{ $pers->id }})">
                                @if(in_array($pers['id'], $selectedMediaPers))
                                <div class="position-absolute top-0 end-0 p-2" style="z-index: 1">
                                    <i class="ri-check-line text-success" style="font-size: 25px"></i>
                                </div>
                                @endif
                                <div class="card-header border-0">
                                    <div class="card-header-title">
                                        <h4>
                                            {{ $pers->nama_media }} (#{{ $pers->unique_id }})
                                        </h4>
                                    </div>
                                </div>

                                <div class="card-body pt-0">
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ri-building-2-line"></i>
                                            {{ $pers->nama_perusahaan }}
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ri-newspaper-line"></i>
                                            {{ $pers->jenis_media }}
                                        </div>
                                        {{-- <div class="d-flex align-items-center gap-2">
                                            <i class="ri-award-line"></i>
                                            Tier {{ $pers->tier }}
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="">

                    </div>
                    <div class="d-flex justify-content-end align-items-center gap-2">
                        @if(count($this->selectedMediaPers) > 0)
                        {{ count($this->selectedMediaPers) }} Media Pers dipilih
                        <button type="button" class="btn btn-primary btn-animation btn-md fw-bold"
                            wire:click="confirmApplyOrder">
                            Terapkan
                        </button>
                        @endif
                        <button type="button" class="btn btn-success btn-animation btn-md fw-bold"
                            data-bs-dismiss="modal" wire:click="closeModal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
