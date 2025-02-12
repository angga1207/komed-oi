<?php

use Carbon\Carbon;

?>
<div>
    <div wire:init="_initGetMedia"></div>
    <div class="row">
        <div class="col-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title flex-wrap align-items-center justify-content-between gap-2">
                        <h5>
                            Daftar Agenda Jadwalin Bae <br>
                            {{ Carbon::parse($filterDate)->isoFormat('dddd, DD MMMM Y') }}
                        </h5>

                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <input type="date" class="form-control" wire:model.live="filterDate">
                            </div>
                            <a href="{{ route('a.media-order') }}" class="btn btn-outline">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        @if($isLoading == false)
                        @if(count($datas) > 0)
                        <ol class="progtrckr ps-0" style="flex-wrap: wrap">
                            @foreach($datas as $data)
                            <li class="@if($data['waktu_pelaksanaan'] < Carbon::now()->isoFormat('HH:mm:ss')) progtrckr-done @else progtrckr-todo @endif border-bottom pt-2 pb-3 ms-2"
                                style="cursor: pointer">
                                <div class=" position-relative">
                                    <div class="position-absolute d-flex align-items-center gap-1"
                                        style="bottom:0px; right:5px" data-bs-toggle="modal"
                                        data-bs-target="#modalAddOrder"
                                        wire:click.prevent="openWizardAdd({{ $data['jadwalin_bae_id'] }})">
                                        <i class="ri-add-circle-line text-success" style="font-size: 20px"></i>
                                        <span class="text-success">
                                            Media Order
                                        </span>
                                    </div>
                                    <h5>
                                        {{ $data['nama_acara'] }}
                                    </h5>
                                    <div class="content d-flex align-items-center gap-2">
                                        <i class="ri-map-pin-line"></i>
                                        <span>
                                            {{ $data['lokasi'] ?? '' }}
                                        </span>
                                    </div>
                                    <div class="content d-flex align-items-center gap-2">
                                        <i class="ri-calendar-todo-fill"></i>
                                        <span>
                                            {{ Carbon::parse($data['tanggal_pelaksanaan'])->isoFormat('DD MMMM Y') }} |
                                            {{ Carbon::parse($data['waktu_pelaksanaan'])->isoFormat('HH:mm [WIB]') }}
                                        </span>
                                    </div>
                                    <div class="content d-flex align-items-center gap-2">
                                        <i class="ri-government-line"></i>
                                        <span>
                                            {{ $data['leading_sector'] ?? '' }}
                                        </span>
                                    </div>
                                    <div class="content d-flex align-items-center gap-2">
                                        <span>
                                            Media Order :
                                        </span>
                                        <span>
                                            {{ $data['order_count'] ?? '' }}
                                        </span>
                                    </div>
                                </div>
                                @if(count($data['orders']) > 0)
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
                                                @foreach($data['orders'] as $ord)
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
                        @else
                        <div class="">
                            Loading...
                        </div>
                        @endif
                    </div>
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
                        @if($selectedJadwalinBae)
                        Buat Agenda ke Media Order
                        <br>
                        "{{ $selectedJadwalinBae['nama_acara'] }}"
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
                                            #{{ $pers->unique_id }} - {{ $pers->nama_media }}
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
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ri-award-line"></i>
                                            Tier {{ $pers->tier }}
                                        </div>
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
