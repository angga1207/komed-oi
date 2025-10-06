<?php

?>
<div>
    <div class="card">
        <div class="card-body">
            <div class="title-header option-title">
                <h5>
                    Detail Kontrak Media
                </h5>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" class="form-control" value="{{ $pers->nama_perusahaan }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Media</label>
                    <input type="text" class="form-control" value="{{ $pers->nama_media }}" disabled>
                </div>
            </div>

            {{-- select tahun --}}
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-select" wire:model.live='year'>
                        @for ($i = date('Y') + 1; $i >= 2024; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            @if($year)
            <div class="">
                {{-- kontrak induk --}}
                <label class="form-label">Kontrak Induk</label>
                <div class="position-relative mb-2">
                    <div class="position-absolute top-50 translate-middle-y" style="left: 10px;">
                        Rp
                    </div>
                    <input type="text" class="form-control ps-5" wire:model='kontrakInduk.nilai_kontrak'
                        x-mask:dynamic="$money($input, ',', '.', 0)" placeholder="Belum ada kontrak induk">
                </div>
                @error('kontrakInduk.nilai_kontrak') <span class="text-danger">{{ $message }}</span> @enderror

                {{-- kontrak apbdp --}}
                <label class="form-label mt-3">Kontrak APBDP</label>
                <div class="position-relative mb-2">
                    <div class="position-absolute top-50 translate-middle-y" style="left: 10px;">
                        Rp
                    </div>
                    <input type="text" class="form-control ps-5" wire:model='kontrakAPBDP.nilai_kontrak'
                        x-mask:dynamic="$money($input, ',', '.', 0)" placeholder="Belum ada kontrak APBDP">
                </div>
                @error('kontrakAPBDP.nilai_kontrak') <span class="text-danger">{{ $message }}</span> @enderror


                {{-- submit button --}}
                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ route('a.media-kontrak') }}" class="btn btn-secondary me-2">
                        Kembali
                    </a>
                    <button class="btn btn-primary" wire:click='saveKontrak'>
                        Simpan
                    </button>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
