<?php
use Carbon\Carbon;
?>
<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title">
                        <h5>
                            Pengumuman
                        </h5>
                        <form class="d-inline-flex">
                            <a class="align-items-center btn btn-theme d-flex" href="javascript:void(0)"
                                data-bs-toggle="modal" data-bs-target="#exampleModalToggle" wire:click="addData()" wire:ignore>
                                <i data-feather="plus"></i>
                                Tambah Pengumuman
                            </a>
                        </form>
                    </div>

                    <div class="table-responsive table-product">
                        <table class="table all-package theme-table" id="table_id">
                            <thead>
                                <tr>
                                    <th>
                                        Gambar
                                    </th>
                                    <th>
                                        Judul
                                    </th>
                                    <th>
                                        Isi
                                    </th>
                                    <th style="width: 100px !important">
                                        Tanggal Publish
                                    </th>
                                    <th class="text-center" style="width: 100px !important">
                                        Status
                                    </th>
                                    <th class="text-center" style="width: 100px">
                                        Opsi
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($announcements as $announcement)
                                <tr wire:key="{{ $announcement->id }}">
                                    <td>
                                        <div class="table-image">
                                            <img src="{{ asset($announcement->image) }}" class="img-fluid" alt="">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="user-name">
                                            <span>
                                                {{ $announcement->title }}
                                            </span>
                                            <span style="cursor: pointer">
                                                {{ '@'.$announcement->link }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        {{ $announcement->content }}
                                    </td>

                                    <td>
                                        {{ Carbon::parse($announcement->published_at)->isoFormat('DD MMM Y') }}
                                    </td>

                                    <td>
                                        <div class="text-center">
                                            @if($announcement->is_active == 1)
                                            <span class="badge badge-success">
                                                Aktif
                                            </span>
                                            @elseif($announcement->is_active == 0)
                                            <span class="badge badge-danger">
                                                Tidak Aktif
                                            </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <ul>
                                            <li>
                                                <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModalToggle"
                                                    wire:click="getDetail({{ $announcement->id }})">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </li>

                                            @if($announcement->is_active == 1)
                                            <li>
                                                <a href="#" class="text-danger d-flex align-items-center"
                                                    wire:click="confirmInactive({{ $announcement->id }})">
                                                    <i class="ri-close-line text-danger"></i>
                                                    <div style="font-size: 12px">
                                                        Nonaktifkan
                                                    </div>
                                                </a>
                                            </li>
                                            @elseif($announcement->is_active == 0)
                                            <li>
                                                <a href="#" class="text-success d-flex align-items-center"
                                                    wire:click="confirmActivated({{ $announcement->id }})">
                                                    <i class="ri-check-line text-success"></i>
                                                    <div style="font-size: 12px">
                                                        Aktifkan
                                                    </div>
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="100">
                                        <div class="text-center">
                                            <h5>
                                                Tidak ada data
                                            </h5>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                            @if($announcements->hasPages())
                            <tfoot>
                                <tr>
                                    <td colspan="100">
                                        {{ $announcements->links() }}
                                    </td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
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
                        Tambah Pengumuman
                        @elseif($inputType == 'update')
                        Edit Pengumuman
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
                                Judul
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="recipient-name" autocomplete="off"
                                placeholder="Judul" wire:model="detail.title">

                            @error('detail.title')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-xl-12">
                            <label for="content" class="col-form-label">
                                Isi
                                <span class="text-danger">*</span>
                            </label>
                            <textarea style="min-height:100px; max-height:100px" class="form-control" id="content" wire:model="detail.content"></textarea>

                            @error('detail.content')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-xl-12">
                            <label class="col-form-label">
                                Gambar
                                <span class="text-danger">*</span>
                            </label>
                            <input type="file" accept="*" class="form-control" wire:model.live='detail.image' wire:loading.attr='disabled'>
                            @error('detail.image')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">Link:</label>
                            <input type="text" class="form-control" id="message-text" autocomplete="off"
                                placeholder="Link" wire:model="detail.link">

                            @error('detail.link')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="recipient-name" class="col-form-label">
                                Tanggal Publish
                            </label>
                            <input type="date" class="form-control" wire:model="detail.published_at">

                            @error('detail.published_at')
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
                    <button type="button" class="btn btn-animation btn-md fw-bold" wire:click="saveData">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
