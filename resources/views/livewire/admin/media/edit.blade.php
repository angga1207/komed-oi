<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center pb-2">
            <h5>Edit Profil Media</h5>
            <a href="{{ route('media') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="mb-3">
                        <label for="unique_id" class="form-label">
                            Nomor Registrasi
                        </label>
                        <input type="text" class="form-control" id="unique_id" wire:model="detail.unique_id" disabled>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="nama_perusahaan" class="form-label">
                            Nama Perusahaan
                        </label>
                        <input type="text" class="form-control" id="nama_perusahaan"
                            placeholder="Masukkan Nama Perusahaan" wire:model="detail.nama_perusahaan">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="nama_media" class="form-label">
                            Nama Media
                        </label>
                        <input type="text" class="form-control" id="nama_media" placeholder="Masukkan Nama Media"
                            wire:model="detail.nama_media">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="alias" class="form-label">
                            Alias
                        </label>
                        <input type="text" class="form-control" id="alias" placeholder="Masukkan Alias"
                            wire:model="detail.alias">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="jenis_media" class="form-label">
                            Jenis Media
                        </label>
                        <input type="text" class="form-control" id="jenis_media" placeholder="Masukkan Jenis Media"
                            wire:model="detail.jenis_media" disabled>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="whatsapp" class="form-label">
                            No. WhatsApp
                        </label>
                        <input type="text" class="form-control" id="whatsapp" placeholder="Masukkan No. WhatsApp"
                            wire:model="detail.whatsapp">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email
                        </label>
                        <input type="text" class="form-control" id="email" placeholder="Masukkan Email"
                            wire:model="detail.email">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="no_npwp" class="form-label">
                            No. NPWP
                        </label>
                        <input type="text" class="form-control" id="no_npwp" placeholder="Masukkan No. NPWP"
                            wire:model="detail.no_npwp">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="no_ref_bank" class="form-label">
                            No. Referensi Bank
                        </label>
                        <input type="text" class="form-control" id="no_ref_bank"
                            placeholder="Masukkan No. Referensi Bank" wire:model="detail.no_ref_bank">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="no_giro_perusahaan" class="form-label">
                            No. Giro Perusahaan
                        </label>
                        <input type="text" class="form-control" id="no_giro_perusahaan"
                            placeholder="Masukkan No. Giro Perusahaan" wire:model="detail.no_giro_perusahaan">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="website" class="form-label">
                            Website
                        </label>
                        <input type="text" class="form-control" id="website" placeholder="Masukkan Website"
                            wire:model="detail.website">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="profil_perusahaan" class="form-label">
                            Profil Perusahaan
                        </label>
                        <textarea style="min-height:100px; max-height:100px" class="form-control"
                            placeholder="Masukkan Profil Perusahaan" id="profil_perusahaan"
                            wire:model="detail.profil_perusahaan"></textarea>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="alamat_media" class="form-label">
                            Alamat Media
                        </label>
                        <textarea style="min-height:100px; max-height:100px" class="form-control"
                            placeholder="Masukkan Alamat Media" id="alamat_media"
                            wire:model="detail.alamat_media"></textarea>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="cakupan_media" class="form-label">
                            Cakupan Media
                        </label>
                        <select class="form-control" wire:model='detail.cakupan_media' wire:loading.attr='disabled'>
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
                        @error('detail.cakupan_media')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="jumlah_oplah" class="form-label">
                            Jumlah Oplah
                        </label>

                        <select class="form-control" wire:model.live='detail.jumlah_oplah' wire:loading.attr='disabled'>
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
                        @error('detail.jumlah_oplah')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror

                        @if($detail['jumlah_oplah'])
                        <input type="file" class="mt-2 form-control" id="file_jumlah_oplah"
                            wire:model="detail.new_file_jumlah_oplah">
                        @error('detail.new_file_jumlah_oplah')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                        @endif

                        @if($detail['file_jumlah_oplah'])
                        <a href="{{ asset($detail['file_jumlah_oplah']) }}" target="_blank"
                            class="badge badge-primary mt-1">
                            Lihat Berkas Jumlah Oplah
                        </a>
                        @else
                        <span class="badge badge-danger mt-1">
                            Berkas tidak diunggah
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="sebaran_oplah" class="form-label">
                            Sebaran Oplah
                        </label>
                        <select class="form-control" wire:model='detail.sebaran_oplah' wire:loading.attr='disabled'>
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
                        @error('detail.sebaran_oplah')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="status_wartawan" class="form-label">
                            Status Wartawan
                        </label>
                        <select class="form-control" wire:model.live='detail.status_wartawan'
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
                        @error('detail.status_wartawan')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror

                        @if(in_array($detail['status_wartawan'], ['Ada Khusus', 'Ada Merangkap Kabupaten
                        Lain']))
                        <input type="file" class="mt-2 form-control" id="file_status_wartawan"
                            wire:model="detail.new_file_status_wartawan">
                        @error('detail.new_file_status_wartawan')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                        @endif

                        @if($detail['file_status_wartawan'])
                        <a href="{{ asset($detail['file_status_wartawan']) }}" target="_blank"
                            class="badge badge-primary mt-1">
                            Lihat Berkas Status Wartawan
                        </a>
                        @else
                        <span class="badge badge-danger mt-1">
                            Berkas tidak diunggah
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="kompetensi_wartawan" class="form-label">
                            Kompetensi Wartawan
                        </label>

                        <select class="form-control" wire:model.live='detail.kompetensi_wartawan'
                            wire:loading.attr='disabled'>
                            <option value="" hidden>Pilih Kompetensi Wartawan</option>
                            <option value="Memiliki Sertifikat Kompetensi">
                                Memiliki Sertifikat Kompetensi
                            </option>
                            <option value="Tidak Memiliki">
                                Tidak Memiliki
                            </option>
                        </select>
                        @error('detail.kompetensi_wartawan')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror


                        @if(in_array($detail['kompetensi_wartawan'], ['Memiliki Sertifikat Kompetensi']))
                        <input type="file" class="mt-2 form-control" id="file_kompetensi_wartawan"
                            wire:model="detail.new_file_kompetensi_wartawan">
                        @error('detail.new_file_kompetensi_wartawan')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                        @endif

                        @if($detail['file_kompetensi_wartawan'])
                        <a href="{{ asset($detail['file_kompetensi_wartawan']) }}" target="_blank"
                            class="badge badge-primary mt-1">
                            Lihat Berkas Kompetensi Wartawan
                        </a>
                        @else
                        <span class="badge badge-danger mt-1">
                            Berkas tidak diunggah
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="status_dewan_pers" class="form-label">
                            Status Dewan Pers
                        </label>

                        <select class="form-control" wire:model.live='detail.status_dewan_pers'
                            wire:loading.attr='disabled'>
                            <option value="" hidden>Pilih Status Dewan Pers</option>
                            <option value="Terdaftar">
                                Terverifikasi
                            </option>
                            <option value="Tidak Terdaftar">
                                Belum Terverifikasi
                            </option>
                        </select>
                        @error('detail.status_dewan_pers')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror

                        @if(in_array($detail['status_dewan_pers'], ['Terdaftar']))
                        <input type="file" class="mt-2 form-control" id="file_status_dewan_pers"
                            wire:model="detail.new_file_status_dewan_pers">
                        @error('detail.new_file_status_dewan_pers')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                        @endif

                        @if($detail['file_status_dewan_pers'])
                        <a href="{{ asset($detail['file_status_dewan_pers']) }}" target="_blank"
                            class="badge badge-primary mt-1">
                            Lihat Berkas Status Dewan Pers
                        </a>
                        @else
                        <span class="badge badge-danger mt-1">
                            Berkas tidak diunggah
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="kantor" class="form-label">
                            Kantor
                        </label>
                        <select class="form-control" wire:model='detail.kantor' wire:loading.attr='disabled'>
                            <option value="" hidden>Pilih Kantor</option>
                            <option value="Ada">
                                Ada
                            </option>
                            <option value="Tidak Ada">
                                Tidak Ada
                            </option>
                        </select>
                        @error('detail.kantor')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="frekuensi_terbitan" class="form-label">
                            Frekuensi Terbitan
                        </label>
                        <select class="form-control" wire:model='detail.frekuensi_terbitan'
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
                        @error('detail.frekuensi_terbitan')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="terbitan_3_edisi_terakhir" class="form-label">
                            Terbitan 3 Edisi Terakhir
                        </label>
                        <select class="form-control" wire:model.live='detail.terbitan_3_edisi_terakhir'
                            wire:loading.attr='disabled'>
                            <option value="" hidden>Pilih Terbitan 3 Edisi Terakhir</option>
                            <option value="Ada">
                                Ada
                            </option>
                            <option value="Tidak Ada">
                                Tidak Ada
                            </option>
                        </select>
                        @error('detail.terbitan_3_edisi_terakhir')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror


                        @if(in_array($detail['terbitan_3_edisi_terakhir'], ['Ada']))
                        <input type="file" class="mt-2 form-control" id="file_terbitan_3_edisi_terakhir"
                            wire:model="detail.new_file_terbitan_3_edisi_terakhir">
                        @error('detail.new_file_terbitan_3_edisi_terakhir')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                        @endif


                        @if($detail['file_terbitan_3_edisi_terakhir'])
                        <a href="{{ asset($detail['file_terbitan_3_edisi_terakhir']) }}" target="_blank"
                            class="badge badge-primary mt-1">
                            Lihat Berkas Terbitan 3 Edisi Terakhir
                        </a>
                        @else
                        <span class="badge badge-danger mt-1">
                            Berkas tidak diunggah
                        </span>
                        @endif
                    </div>
                </div>


                {{-- @foreach($detail['register_files'] as $file)
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">
                            {{ $file['title'] }}
                        </label>
                        <div class="form-control">
                            <a href="{{ asset($file['file_path']) }}" target="_blank" class="badge badge-primary">
                                {{ $file['file_name'] }}
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach --}}


                <div class="mt-4 mb-2">
                    <h4 class="text-underline fw-bold">Berkas Persayaratan : </h4>
                </div>

                @if(in_array($detail['jenis_media'], ['Media Cetak']))
                <div>
                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            Akta Pendirian
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.akta_pendirian'
                                wire:loading.attr='disabled'>
                            @error('berkas.akta_pendirian')
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
                            <input type="file" accept="*" class="form-control" wire:model='berkas.sk_kemenkumham'
                                wire:loading.attr='disabled'>
                            @error('berkas.sk_kemenkumham')
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
                            <input type="file" accept="*" class="form-control" wire:model='berkas.siup'
                                wire:loading.attr='disabled'>
                            @error('berkas.siup')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            TDP Penerbitan 58130
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.tdp_penerbitan_58130'
                                wire:loading.attr='disabled'>
                            @error('berkas.tdp_penerbitan_58130')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            SPT Terakhir
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.spt_terakhir'
                                wire:loading.attr='disabled'>
                            @error('berkas.spt_terakhir')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            SP Cakupan Wilayah
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.sp_cakupan_wilayah'
                                wire:loading.attr='disabled'>
                            @error('berkas.sp_cakupan_wilayah')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            SP Pimpinan
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.sp_pimpinan'
                                wire:loading.attr='disabled'>
                            @error('berkas.sp_pimpinan')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            Surat Tugas Wartawan
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.surat_tugas_wartawan'
                                wire:loading.attr='disabled'>
                            @error('berkas.surat_tugas_wartawan')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                @elseif(in_array($detail['jenis_media'], ['Media Elektronik']))
                <div>
                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            Akta Pendirian
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.akta_pendirian'
                                wire:loading.attr='disabled'>
                            @error('berkas.akta_pendirian')
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
                            <input type="file" accept="*" class="form-control" wire:model='berkas.sk_kemenkumham'
                                wire:loading.attr='disabled'>
                            @error('berkas.sk_kemenkumham')
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
                            <input type="file" accept="*" class="form-control" wire:model='berkas.izin_ipp'
                                wire:loading.attr='disabled'>
                            @error('berkas.izin_ipp')
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
                            <input type="file" accept="*" class="form-control" wire:model='berkas.izin_isr'
                                wire:loading.attr='disabled'>
                            @error('berkas.izin_isr')
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
                            <input type="file" accept="*" class="form-control" wire:model='berkas.siup'
                                wire:loading.attr='disabled'>
                            @error('berkas.siup')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            TDP Penyiaran 60102
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.tdp_penyiaran_60102'
                                wire:loading.attr='disabled'>
                            @error('berkas.tdp_penyiaran_60102')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            SPT Terakhir
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.spt_terakhir'
                                wire:loading.attr='disabled'>
                            @error('berkas.spt_terakhir')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            SP Cakupan Wilayah
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.sp_cakupan_wilayah'
                                wire:loading.attr='disabled'>
                            @error('berkas.sp_cakupan_wilayah')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            SP Pimpinan
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.sp_pimpinan'
                                wire:loading.attr='disabled'>
                            @error('berkas.sp_pimpinan')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            SK Biro Iklan
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.sk_biro_iklan'
                                wire:loading.attr='disabled'>
                            @error('berkas.sk_biro_iklan')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            Surat Tugas Wartawan
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.surat_tugas_wartawan'
                                wire:loading.attr='disabled'>
                            @error('berkas.surat_tugas_wartawan')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                @elseif(in_array($detail['jenis_media'], ['Media Siber']))
                <div>
                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            Akta Pendirian
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.akta_pendirian'
                                wire:loading.attr='disabled'>
                            @error('berkas.akta_pendirian')
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
                            <input type="file" accept="*" class="form-control" wire:model='berkas.sk_kemenkumham'
                                wire:loading.attr='disabled'>
                            @error('berkas.sk_kemenkumham')
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
                            <input type="file" accept="*" class="form-control" wire:model='berkas.siup'
                                wire:loading.attr='disabled'>
                            @error('berkas.siup')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            TDP Penerbitan 63122
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.tdp_penerbitan_63122'
                                wire:loading.attr='disabled'>
                            @error('berkas.tdp_penerbitan_63122')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            SPT Terakhir
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.spt_terakhir'
                                wire:loading.attr='disabled'>
                            @error('berkas.spt_terakhir')
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
                            <input type="file" accept="*" class="form-control" wire:model='berkas.situ'
                                wire:loading.attr='disabled'>
                            @error('berkas.situ')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            SK Domisili
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.sk_domisili'
                                wire:loading.attr='disabled'>
                            @error('berkas.sk_domisili')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 row align-items-center">
                        <label class="form-label-title col-sm-3 mb-0">
                            Surat Tugas Wartawan
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="file" accept="*" class="form-control" wire:model='berkas.surat_tugas_wartawan'
                                wire:loading.attr='disabled'>
                            @error('berkas.surat_tugas_wartawan')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                @elseif(in_array($detail['jenis_media'], ['Multimedia', 'Media Sosial']))
                <div class="">
                    <div class="alert alert-info">
                        Tidak ada persyaratan berkas untuk jenis media Multimedia dan Media Sosial.
                    </div>
                </div>
                @endif

                <div class="col-12 d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-primary" wire:click='save' wire:loading.attr='disabled'>
                        <i class="fa fa-save me-2"></i>
                        <span>Simpan</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
