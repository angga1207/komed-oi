<?php

?>
<div>
    <div class="row">
        <div class="col-12">
            <div class="card py-0" style="height: calc(100vh - 180px); overflow-x:auto">
                <div class="card-header-2 pt-4">
                    @if (!$jenisMedia)
                        <h5>
                            Pilih Jenis Media
                        </h5>
                    @elseif($jenisMedia)
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Informasi Media Pers</h5>
                            <h5>
                                {{ $jenisMedia }}
                            </h5>
                        </div>
                    @endif
                </div>
                @if ($this->step == 1)
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="main-tiles border card-hover card o-hidden" style="cursor: pointer"
                                wire:click.prevent='changeJenisMedia(1)'>
                                <div class="custome-1-bg b-r-4 card-body">
                                    <div class="media align-items-center static-top-widget">
                                        <div class="media-body p-0">
                                            <h4 class="mb-0 counter">
                                                Media Cetak
                                            </h4>
                                        </div>
                                        <div class="align-self-center text-center">
                                            <i class="ri-newspaper-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="main-tiles border card-hover card o-hidden" style="cursor: pointer"
                                wire:click.prevent='changeJenisMedia(2)'>
                                <div class="custome-2-bg b-r-4 card-body">
                                    <div class="media align-items-center static-top-widget">
                                        <div class="media-body p-0">
                                            <h4 class="mb-0 counter">
                                                Media Elektronik
                                            </h4>
                                        </div>
                                        <div class="align-self-center text-center">
                                            <i class="ri-tv-2-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="main-tiles border card-hover card o-hidden" style="cursor: pointer"
                                wire:click.prevent='changeJenisMedia(3)'>
                                <div class="custome-3-bg b-r-4 card-body">
                                    <div class="media align-items-center static-top-widget">
                                        <div class="media-body p-0">
                                            <h4 class="mb-0 counter">
                                                Media Siber
                                            </h4>
                                        </div>
                                        <div class="align-self-center text-center">
                                            <i class="ri-camera-lens-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="main-tiles border card-hover card o-hidden" style="cursor: pointer"
                                wire:click.prevent='changeJenisMedia(4)'>
                                <div class="custome-4-bg b-r-4 card-body">
                                    <div class="media align-items-center static-top-widget">
                                        <div class="media-body p-0">
                                            <h4 class="mb-0 counter">
                                                Media Sosial
                                            </h4>
                                        </div>
                                        <div class="align-self-center text-center">
                                            <i class="ri-group-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($this->step == 2)
                    <div class="">
                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Nama Perusahaan
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" placeholder="Nama Perusahaan"
                                    wire:model='input.nama_perusahaan' wire:loading.attr='disabled'>
                                @error('input.nama_perusahaan')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Nama Media
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" placeholder="Nama Media"
                                    wire:model='input.nama_media' wire:loading.attr='disabled'>
                                @error('input.nama_media')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Alamat Media
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control" type="text" placeholder="Alamat Media" style="max-height: 150px"
                                    wire:model='input.alamat_media' wire:loading.attr='disabled'></textarea>
                                @error('input.alamat_media')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Email Media
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input class="form-control" type="email" placeholder="Email Media"
                                    wire:model='input.email' wire:loading.attr='disabled'>
                                @error('input.email')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Website Media
                            </label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" placeholder="Website Media"
                                    wire:model='input.website' wire:loading.attr='disabled'>
                                @error('input.website')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Logo Media
                            </label>
                            <div class="col-sm-9">
                                <input type="file" accept="image/png, image/jpeg, image/jpg" class="form-control"
                                    wire:model.live='input.logo_media' wire:loading.attr='disabled'>
                                <!-- info format png,jpeg,jpg & max size 10MB -->
                                <div class="form-text" style="font-size: 0.75rem;">
                                    Format file: png, jpeg, jpg. Maksimal ukuran file: 10MB.
                                </div>
                                @error('input.logo_media')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                NPWP
                            </label>
                            <div class="col-sm-9">
                                <input class="form-control" type="number" placeholder="NPWP"
                                    wire:model='input.no_npwp' wire:loading.attr='disabled'>
                                @error('input.no_npwp')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Nama Bank
                            </label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" placeholder="Nama Bank"
                                    wire:model='input.nama_bank' wire:loading.attr='disabled'>
                                @error('input.nama_bank')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Nomor Ref Bank
                            </label>
                            <div class="col-sm-9">
                                <input class="form-control" type="number" placeholder="Nomor Ref Bank"
                                    wire:model='input.no_ref_bank' wire:loading.attr='disabled'>
                                @error('input.no_ref_bank')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Nomor Giro Perusahaan
                            </label>
                            <div class="col-sm-9">
                                <input class="form-control" type="number" placeholder="Nomor Giro Perusahaan"
                                    wire:model='input.no_giro_perusahaan' wire:loading.attr='disabled'>
                                @error('input.no_giro_perusahaan')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Jabatan yang Mendaftarkan
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" placeholder="Jabatan yang Mendaftarkan"
                                    wire:model='input.jabatan' wire:loading.attr='disabled'>
                                @error('input.jabatan')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Cakupan Media
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select class="form-control" wire:model='input.cakupan_media'
                                    wire:loading.attr='disabled'>
                                    <option value="" hidden>Pilih Cakupan Media</option>
                                    <option value="Nasional/Regional">
                                        Nasional/Regional
                                    </option>
                                    <option value="Regional">
                                        Regional
                                    </option>
                                    <option value="Kabupaten">
                                        Kabupaten
                                    </option>
                                </select>
                                @error('input.cakupan_media')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        @if ($input['jenis_media'] == 'Media Cetak')
                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Jumlah Oplah
                                    @if ($input['jenis_media'] == 'Media Cetak')
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control" wire:model='input.jumlah_oplah'
                                        wire:loading.attr='disabled'>
                                        <option value="" hidden>Pilih Jumlah Oplah</option>
                                        <option value="> 100.000">
                                            > 100.000
                                        </option>
                                        <option value="25.001-100.000">
                                            25.001-100.000
                                        </option>
                                        <option value="10.001-25.000">
                                            10.001-25.000
                                        </option>
                                        <option value="1.001-10.000">
                                            1.001-10.000
                                        </option>
                                        <option value="< 1.000">
                                            < 1.000 </option>
                                    </select>
                                    @error('input.jumlah_oplah')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Sebaran Oplah
                                    @if ($input['jenis_media'] == 'Media Cetak')
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control" wire:model='input.sebaran_oplah'
                                        wire:loading.attr='disabled'>
                                        <option value="" hidden>Pilih Sebaran Oplah</option>
                                        <option value="11-16 Kecamatan">
                                            11-16 Kecamatan
                                        </option>
                                        <option value="6-10 Kecamatan">
                                            6-10 Kecamatan
                                        </option>
                                        <option value="< 5 Kecamatan">
                                            < 5 Kecamatan </option>
                                    </select>
                                    @error('input.sebaran_oplah')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Lampiran Jumlah Oplah
                                    @if ($input['jenis_media'] == 'Media Cetak')
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model.live='input.file_jumlah_oplah'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.file_jumlah_oplah')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endif


                        @if (in_array($input['jenis_media'], ['Media Cetak', 'Media Elektronik', 'Media Siber']))
                            <!-- Media Cetak, Elektronik, Siber Only -->
                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Status Wartawan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control" wire:model.live='input.status_wartawan'
                                        wire:loading.attr='disabled'>
                                        <option value="" hidden>Pilih Status Wartawan</option>
                                        <option value="Ada Khusus">
                                            Ada Khusus
                                        </option>
                                        <option value="Ada Merangkap Kabupaten Lain">
                                            Ada Merangkap Kabupaten Lain
                                        </option>
                                        <option value="Tidak Ada">
                                            Tidak Ada
                                        </option>
                                    </select>
                                    @error('input.status_wartawan')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            @if (in_array($input['status_wartawan'], ['Ada Khusus', 'Ada Merangkap Kabupaten Lain']))
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">
                                        Lampiran Status Wartawan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="file" accept="*" class="form-control"
                                            wire:model.live='input.file_status_wartawan' wire:loading.attr='disabled'>
                                        @error('input.file_status_wartawan')
                                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Kompetensi Wartawan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control" wire:model.live='input.kompetensi_wartawan'
                                        wire:loading.attr='disabled'>
                                        <option value="" hidden>Pilih Kompetensi Wartawan</option>
                                        <option value="Memiliki Sertifikat Kompetensi">
                                            Memiliki Sertifikat Kompetensi
                                        </option>
                                        <option value="Tidak Memiliki">
                                            Tidak Memiliki
                                        </option>
                                    </select>
                                    @error('input.kompetensi_wartawan')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            @if ($input['kompetensi_wartawan'] == 'Memiliki Sertifikat Kompetensi')
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">
                                        Lampiran Kompetensi Wartawan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="file" accept="*" class="form-control"
                                            wire:model.live='input.file_kompetensi_wartawan'
                                            wire:loading.attr='disabled'>
                                        @error('input.file_kompetensi_wartawan')
                                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Status Dewan Pers
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control" wire:model.live='input.status_dewan_pers'
                                        wire:loading.attr='disabled'>
                                        <option value="" hidden>Pilih Status Dewan Pers</option>
                                        <option value="Terdaftar">
                                            Terverifikasi
                                        </option>
                                        <option value="Tidak Terdaftar">
                                            Belum Terverifikasi
                                        </option>
                                    </select>
                                    @error('input.status_dewan_pers')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            @if ($input['status_dewan_pers'] == 'Terdaftar')
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">
                                        Lampiran Status Dewan Pers
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="file" accept="*" class="form-control"
                                            wire:model.live='input.file_status_dewan_pers'
                                            wire:loading.attr='disabled'>
                                        @error('input.file_status_dewan_pers')
                                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                        @endif

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Kantor
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select class="form-control" wire:model='input.kantor' wire:loading.attr='disabled'>
                                    <option value="" hidden>Pilih Kantor</option>
                                    <option value="Ada">
                                        Ada
                                    </option>
                                    <option value="Tidak Ada">
                                        Tidak Ada
                                    </option>
                                </select>
                                @error('input.kantor')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">
                                Frekuensi Terbitan
                                @if ($input['jenis_media'] == 'Media Cetak')
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <div class="col-sm-9">
                                <select class="form-control" wire:model='input.frekuensi_terbitan'
                                    wire:loading.attr='disabled'>
                                    <option value="" hidden>Pilih Frekuensi Terbitan</option>
                                    <option value="1 kali sehari">
                                        1 kali sehari
                                    </option>
                                    <option value="1 kali seminggu">
                                        1 kali seminggu
                                    </option>
                                    <option value="2 mingguan">
                                        2 mingguan
                                    </option>
                                    <option value="1 kali sebulan">
                                        1 kali sebulan
                                    </option>
                                </select>
                                @error('input.frekuensi_terbitan')
                                    <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        @if ($input['jenis_media'] == 'Media Cetak')
                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Terbitan 3 Edisi Terakhir
                                    @if ($input['jenis_media'] == 'Media Cetak')
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control" wire:model.live='input.terbitan_3_edisi_terakhir'
                                        wire:loading.attr='disabled'>
                                        <option value="" hidden>Pilih Terbitan 3 Edisi Terakhir</option>
                                        <option value="Ada">
                                            Ada
                                        </option>
                                        <option value="Tidak Ada">
                                            Tidak Ada
                                        </option>
                                    </select>
                                    @error('input.terbitan_3_edisi_terakhir')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            @if ($input['terbitan_3_edisi_terakhir'] == 'Ada')
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">
                                        Lampiran Terbitan 3 Edisi Terakhir <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="file" accept="*" class="form-control"
                                            wire:model.live='input.file_terbitan_3_edisi_terakhir'
                                            wire:loading.attr='disabled'>
                                        @error('input.file_terbitan_3_edisi_terakhir')
                                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if ($input['jenis_media'] == 'Media Sosial')
                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Memiliki Follower Minimal 10.000?
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control" wire:model='input.follower_required'
                                        wire:loading.attr='disabled'>
                                        <option value="" hidden>Pilih Jawaban</option>
                                        <option value="1">
                                            Ya
                                        </option>
                                        <option value="0">
                                            Tidak
                                        </option>
                                    </select>
                                    @error('input.follower_required')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Latest Follower Count -->
                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Rata-rata Jumlah Viewer
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="number" placeholder="Rata-rata Jumlah Viewer"
                                        wire:model='input.latest_viewer' wire:loading.attr='disabled'>
                                    @error('input.latest_viewer')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Multiple Link Media Sosial -->
                            <div class="mb-4 row align-items-start">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Link Media Sosial
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    @if (isset($input['social_media']) && count($input['social_media']) > 0)
                                        @foreach ($input['social_media'] as $index => $social)
                                            <div class="mb-3 border rounded p-3"
                                                wire:key="social-{{ $index }}">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0">Media Sosial {{ $index + 1 }}</h6>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        wire:click="removeSocialMedia({{ $index }})"
                                                        wire:loading.attr='disabled'>
                                                        <i class="fa fa-trash me-2"></i> Hapus
                                                    </button>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label"
                                                        style="font-size: 0.85rem;">Platform</label>
                                                    <select class="form-control"
                                                        wire:model='input.social_media.{{ $index }}.platform'
                                                        wire:loading.attr='disabled'>
                                                        <option value="" hidden>Pilih Platform</option>
                                                        <option value="Facebook">Facebook</option>
                                                        <option value="Instagram">Instagram</option>
                                                        <option value="Twitter/X">Twitter/X</option>
                                                        <option value="YouTube">YouTube</option>
                                                        <option value="TikTok">TikTok</option>
                                                        <option value="LinkedIn">LinkedIn</option>
                                                        <option value="Telegram">Telegram</option>
                                                        <option value="WhatsApp">WhatsApp</option>
                                                        <option value="Line">Line</option>
                                                        <option value="Lainnya">Lainnya</option>
                                                    </select>
                                                    @error('input.social_media.' . $index . '.platform')
                                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="form-label"
                                                        style="font-size: 0.85rem;">URL/Link</label>
                                                    <input type="url" class="form-control"
                                                        placeholder="https://..."
                                                        wire:model='input.social_media.{{ $index }}.url'
                                                        wire:loading.attr='disabled'>
                                                    @error('input.social_media.' . $index . '.url')
                                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted mb-3" style="font-size: 0.9rem;">
                                            Belum ada link media sosial yang ditambahkan.
                                        </p>
                                    @endif

                                    @if (!isset($input['social_media']) || count($input['social_media']) < 10)
                                        <button type="button" class="btn btn-sm btn-primary"
                                            wire:click="addSocialMedia" wire:loading.attr='disabled'>
                                            <i class="fa fa-plus me-2"></i> Tambah Media Sosial
                                        </button>
                                    @endif

                                    @if (isset($input['social_media']) && count($input['social_media']) >= 10)
                                        <div class="alert alert-info mb-0" style="font-size: 0.85rem;">
                                            Maksimal 10 media sosial yang dapat ditambahkan.
                                        </div>
                                    @endif

                                    @error('input.social_media')
                                        <div class="text-danger mt-2" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endif

                    </div>
                @elseif($this->step == 3)
                    <div class="">
                        @if ($jenisMedia == 'Media Cetak')
                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Akta Pendirian
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.akta_pendirian'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.akta_pendirian')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SK Kemenkumham
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sk_kemenkumham'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sk_kemenkumham')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SIUP
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.siup' wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.siup')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    TDP Penerbitan 58130
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.tdp_penerbitan_58130'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.tdp_penerbitan_58130')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SPT Terakhir
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.spt_terakhir'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.spt_terakhir')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SP Cakupan Wilayah
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sp_cakupan_wilayah'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sp_cakupan_wilayah')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SP Pimpinan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sp_pimpinan'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sp_pimpinan')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Surat Tugas Wartawan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.surat_tugas_wartawan'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.surat_tugas_wartawan')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}
                        @elseif($jenisMedia == 'Media Elektronik')
                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Akta Pendirian
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.akta_pendirian'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.akta_pendirian')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SK Kemenkumham
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sk_kemenkumham'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sk_kemenkumham')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Izin IPP
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.izin_ipp'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.izin_ipp')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Izin ISR
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.izin_isr'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.izin_isr')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SIUP
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.siup' wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.siup')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    TDP Penyiaran 60102
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.tdp_penyiaran_60102'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.tdp_penyiaran_60102')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SPT Terakhir
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.spt_terakhir'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.spt_terakhir')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SP Cakupan Wilayah
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sp_cakupan_wilayah'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sp_cakupan_wilayah')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SP Pimpinan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sp_pimpinan'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sp_pimpinan')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SK Biro Iklan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sk_biro_iklan'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sk_biro_iklan')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Surat Tugas Wartawan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.surat_tugas_wartawan'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.surat_tugas_wartawan')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}
                        @elseif($jenisMedia == 'Media Siber')
                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Akta Pendirian
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.akta_pendirian'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.akta_pendirian')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SK Kemenkumham
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sk_kemenkumham'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sk_kemenkumham')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SIUP
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.siup' wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.siup')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    TDP Penerbitan 63122
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf" class="form-control"
                                        wire:model='input.tdp_penerbitan_63122' wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.tdp_penerbitan_63122')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SPT Terakhir
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.spt_terakhir'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.spt_terakhir')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SITU
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.situ' wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.situ')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Domisili
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sk_domisili'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sk_domisili')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Surat Tugas Wartawan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.surat_tugas_wartawan'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.surat_tugas_wartawan')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}
                        @elseif($jenisMedia == 'Media Sosial')
                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Akta Pendirian
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.akta_pendirian'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.akta_pendirian')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SK Kemenkumham
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sk_kemenkumham'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sk_kemenkumham')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SIUP / NIB
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.siup' wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.siup')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    TDP Penerbitan 63122
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf" class="form-control"
                                        wire:model='input.tdp_penerbitan_63122' wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.tdp_penerbitan_63122')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SPT Terakhir
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.spt_terakhir'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.spt_terakhir')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SITU
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.situ' wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.situ')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Domisili
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sk_domisili'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sk_domisili')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Surat Tugas Wartawan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.surat_tugas_wartawan'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.surat_tugas_wartawan')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}
                        @elseif($jenisMedia == 'Multimedia')
                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Akta Pendirian
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.akta_pendirian'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.akta_pendirian')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SK Kemenkumham
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sk_kemenkumham'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sk_kemenkumham')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SIUP
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.siup' wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.siup')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    TDP Penerbitan 63122
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf" class="form-control"
                                        wire:model='input.tdp_penerbitan_63122' wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.tdp_penerbitan_63122')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SPT Terakhir
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.spt_terakhir'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.spt_terakhir')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    SITU
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.situ' wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.situ')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Domisili
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.sk_domisili'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.sk_domisili')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">
                                    Surat Tugas Wartawan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf"
                                        class="form-control" wire:model='input.surat_tugas_wartawan'
                                        wire:loading.attr='disabled'>
                                    <!-- info format png,jpeg,jpg,pdf & max size 10MB -->
                                    <div class="form-text" style="font-size: 0.75rem;">
                                        Format file: png, jpeg, jpg, pdf. Maksimal ukuran file: 10MB.
                                    </div>
                                    @error('input.surat_tugas_wartawan')
                                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}
                        @endif
                    </div>
                @endif

                @if ($step > 1)
                    <div class="pb-4" style="position: sticky; bottom:0; left:0; width:100%; background:#fff">
                        <div class="card-submit-button mt-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="">
                                    <button class="btn btn-danger btn-animation ms-auto" type="button"
                                        wire:click.prevent='prevStep'>
                                        <i class="ri-arrow-drop-left-line"></i>
                                        Sebelumnya
                                    </button>
                                </div>
                                <div class="">
                                    @if ($step < 3)
                                        <button class="btn btn-animation ms-auto" type="button"
                                            wire:click.prevent='nextStep'>
                                            Selanjutnya
                                            <i class="ri-arrow-drop-right-line"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-animation ms-auto" type="button"
                                            wire:click.prevent='confirmSave'>
                                            Simpan
                                            <i class="ri-save-line ms-2"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
