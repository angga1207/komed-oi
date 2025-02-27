<div>
    <div class="row">
        <div class="col-sm-12">
            <!-- Details Start -->
            <div class="card">
                <div class="card-body">
                    <div class="title-header option-title">
                        <h5>Profile</h5>
                    </div>
                    <form class="theme-form theme-form-2 mega-form">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="mb-3">
                                    <label for="unique_id" class="form-label">
                                        Nomor Registrasi
                                    </label>
                                    <input type="text" class="form-control" id="unique_id" wire:model="detail.unique_id"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="nama_perusahaan" class="form-label">
                                        Nama Perusahaan
                                    </label>
                                    <input type="text" class="form-control" id="nama_perusahaan"
                                        wire:model="detail.nama_perusahaan" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="nama_media" class="form-label">
                                        Nama Media
                                    </label>
                                    <input type="text" class="form-control" id="nama_media" wire:model="detail.nama_media"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="alias" class="form-label">
                                        Alias
                                    </label>
                                    <input type="text" class="form-control" id="alias" wire:model="detail.alias" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_media" class="form-label">
                                        Jenis Media
                                    </label>
                                    <input type="text" class="form-control" id="jenis_media" wire:model="detail.jenis_media"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="logo_media" class="form-label">
                                        Logo Media
                                    </label>
                                    <div class="form-control" readonly style="background-color:#f9f9f6 !important;">
                                        <a href="{{ asset($detail['logo_media']) }}" target="_blank"
                                            class="badge badge-primary">
                                            Lihat Logo Media
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="whatsapp" class="form-label">
                                        No. WhatsApp
                                    </label>
                                    <input type="text" class="form-control" id="whatsapp" wire:model="detail.whatsapp"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        Email
                                    </label>
                                    <input type="text" class="form-control" id="email" wire:model="detail.email" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="no_npwp" class="form-label">
                                        No. NPWP
                                    </label>
                                    <input type="text" class="form-control" id="no_npwp" wire:model="detail.no_npwp"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="nama_bank" class="form-label">
                                        Nama Bank
                                    </label>
                                    <input type="text" class="form-control" id="nama_bank" wire:model="detail.nama_bank"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="no_ref_bank" class="form-label">
                                        No. Referensi Bank
                                    </label>
                                    <input type="text" class="form-control" id="no_ref_bank" wire:model="detail.no_ref_bank"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="no_giro_perusahaan" class="form-label">
                                        No. Giro Perusahaan
                                    </label>
                                    <input type="text" class="form-control" id="no_giro_perusahaan"
                                        wire:model="detail.no_giro_perusahaan" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="website" class="form-label">
                                        Website
                                    </label>
                                    <input type="text" class="form-control" id="website" wire:model="detail.website"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="profil_perusahaan" class="form-label">
                                        Profil Perusahaan
                                    </label>
                                    <textarea style="min-height:100px; max-height:100px" class="form-control"
                                        id="profil_perusahaan" wire:model="detail.profil_perusahaan" readonly></textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="alamat_media" class="form-label">
                                        Alamat Media
                                    </label>
                                    <textarea style="min-height:100px; max-height:100px" class="form-control"
                                        id="alamat_media" wire:model="detail.alamat_media" readonly></textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="cakupan_media" class="form-label">
                                        Cakupan Media
                                    </label>
                                    <input type="text" class="form-control" id="cakupan_media"
                                        wire:model="detail.cakupan_media" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah_oplah" class="form-label">
                                        Jumlah Oplah
                                    </label>
                                    <input type="text" class="form-control" id="jumlah_oplah"
                                        wire:model="detail.jumlah_oplah" readonly>

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
                                    <input type="text" class="form-control" id="sebaran_oplah"
                                        wire:model="detail.sebaran_oplah" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="status_wartawan" class="form-label">
                                        Status Wartawan
                                    </label>
                                    <input type="text" class="form-control" id="status_wartawan"
                                        wire:model="detail.status_wartawan" readonly>

                                    @if($detail['file_status_wartawan'])
                                    <a href="{{ asset($detail['file_status_wartawan']) }}" target="_blank"
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
                                    <label for="kompetensi_wartawan" class="form-label">
                                        Kompetensi Wartawan
                                    </label>
                                    <input type="text" class="form-control" id="kompetensi_wartawan"
                                        wire:model="detail.kompetensi_wartawan" readonly>

                                    @if($detail['file_kompetensi_wartawan'])
                                    <a href="{{ asset($detail['file_kompetensi_wartawan']) }}" target="_blank"
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
                                    <label for="status_dewan_pers" class="form-label">
                                        Status Dewan Pers
                                    </label>
                                    <input type="text" class="form-control" id="status_dewan_pers"
                                        wire:model="detail.status_dewan_pers" readonly>

                                    @if($detail['file_status_dewan_pers'])
                                    <a href="{{ asset($detail['file_status_dewan_pers']) }}" target="_blank"
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
                                    <label for="kantor" class="form-label">
                                        Kantor
                                    </label>
                                    <input type="text" class="form-control" id="kantor" wire:model="detail.kantor" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="frekuensi_terbitan" class="form-label">
                                        Frekuensi Terbitan
                                    </label>
                                    <input type="text" class="form-control" id="frekuensi_terbitan"
                                        wire:model="detail.frekuensi_terbitan" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="terbitan_3_edisi_terakhir" class="form-label">
                                        Terbitan 3 Edisi Terakhir
                                    </label>
                                    <input type="text" class="form-control" id="terbitan_3_edisi_terakhir"
                                        wire:model="detail.terbitan_3_edisi_terakhir" readonly>

                                    @if($detail['file_terbitan_3_edisi_terakhir'])
                                    <a href="{{ asset($detail['file_terbitan_3_edisi_terakhir']) }}" target="_blank"
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

                            @foreach($detail['register_files'] as $file)
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        {{ $file['title'] }}
                                    </label>
                                    <div class="form-control" readonly  style="background-color:#f9f9f6 !important;">
                                        <a href="{{ asset($file['file_path']) }}" target="_blank"
                                            class="badge badge-primary">
                                            {{ $file['file_name'] }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            {{-- <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="tier" class="form-label">
                                        Tier
                                    </label>
                                    <input type="text" class="form-control" id="tier" wire:model="detail.tier" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="tier_point" class="form-label">
                                        Point
                                    </label>
                                    <input type="text" class="form-control" id="tier_point" wire:model="detail.tier_point"
                                        readonly>
                                </div>
                            </div> --}}

                            {{-- <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="verification_deadline" class="form-label">
                                        Dapat Diverifikasi Sebelum
                                    </label>
                                    <input type="text" class="form-control" id="verification_deadline"
                                        value="{{ Carbon::parse($detail['verification_deadline'])->isoFormat('D MMM Y, HH:mm [WIB]') }}"
                                        readonly>
                                </div>
                            </div> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
