<div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title">
                        <h5>
                            Daftar Akun Klien
                        </h5>
                        <form class="d-inline-flex" wire:submit.prevent="goSearch">
                            <input type="search" class="form-control" placeholder="Pencarian..." wire:model="search" />
                            @if($search)
                            <div class="" wire:click.prevent="resetSearch">
                                <i class="ri-close-circle-line"></i>
                            </div>
                            @endif
                        </form>
                    </div>

                    <div class="table-responsive table-product">
                        <table class="table all-package theme-table" id="table_id">
                            <thead>
                                <tr>
                                    <th>
                                        Photo
                                    </th>
                                    <th>
                                        Nama Lengkap
                                    </th>
                                    <th>
                                        Whatsapp
                                    </th>
                                    <th>
                                        Email
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Opsi
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($datas as $data)
                                <tr wire:key="{{ $data->id }}">
                                    <td>
                                        <div class="table-image">
                                            <img src="{{ asset($data->photo) }}" class="img-fluid" alt="">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="user-name">
                                            <span>
                                                {{ $data->fullname }}
                                            </span>
                                            <span style="cursor: pointer">
                                                {{ $data->username }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        {{ $data->whatsapp }}
                                    </td>

                                    <td>
                                        {{ $data->email }}
                                    </td>

                                    <td>
                                        @if($data->status == 'pending')
                                        <span class="badge badge-success">
                                            Menunggu Persetujuan
                                        </span>
                                        @elseif($data->status == 'active')
                                        <span class="badge badge-primary">
                                            Aktif
                                        </span>
                                        @elseif($data->status == 'banned')
                                        <span class="badge badge-danger">
                                            Diblokir
                                        </span>
                                        @elseif($data->status == 'suspend')
                                        <span class="badge badge-warning">
                                            Ditangguhkan
                                        </span>
                                        @endif
                                    </td>

                                    <td>
                                        <ul>
                                            @if(auth()->user()->role_id == 1)
                                            {{-- impersonate button --}}
                                            <li>
                                                <a href="#"
                                                    {{-- wire:click="confirmImpersonate({{ $data->id }})"> --}}
                                                    wire:click="goImpersonate({{ $data->id }})">
                                                    <i class="ri-user-shared-line"></i>
                                                </a>
                                            </li>
                                            @endif

                                            <li>
                                                <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModalToggle"
                                                    wire:click="getDetail({{ $data->id }})">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </li>

                                            {{-- reset password --}}
                                            <li>
                                                <a href="javascript:void(0)"
                                                    wire:click="confirmResetPassword({{ $data->id }})">
                                                    <i class="ri-lock-password-line"></i>
                                                </a>
                                            </li>

                                            @if($data->status == 'active')
                                            <li>
                                                <a href="#" class="text-danger d-flex align-items-center"
                                                    wire:click="confirmBanned({{ $data->id }})">
                                                    <i class="ri-close-line text-danger"></i>
                                                    <div style="font-size: 12px">
                                                        Blokir
                                                    </div>
                                                </a>
                                            </li>
                                            @elseif($data->status != 'active')
                                            <li>
                                                <a href="#" class="text-success d-flex align-items-center"
                                                    wire:click="confirmActivated({{ $data->id }})">
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

                            @if($datas->hasPages())
                            <tfoot>
                                <tr>
                                    <td colspan="100">
                                        {{ $datas->links() }}
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
                        Detail Akun Klien
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    @if($detail)
                    <div class="row">
                        <div class="col-12 col-xl-6">
                            <label for="recipient-name" class="col-form-label">Nama Lengkap:</label>
                            <input type="text" class="form-control" id="recipient-name" readonly
                                wire:model="detail.fullname">
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">N.I.K:</label>
                            <input type="text" class="form-control" id="message-text" readonly
                                wire:model="detail.username">
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">Email:</label>
                            <input type="email" class="form-control" id="message-text" readonly
                                wire:model="detail.email">
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">Whatsapp:</label>
                            <input type="text" class="form-control" id="message-text" readonly
                                wire:model="detail.whatsapp">
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">Status:</label>
                            <input type="text" class="form-control" id="message-text" readonly
                                wire:model="detail.status">
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">Registrasi ID:</label>
                            <input type="text" class="form-control" id="message-text" readonly
                                wire:model="detail.get_media.unique_id">
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">Nama Media:</label>
                            <input type="text" class="form-control" id="message-text" readonly
                                wire:model="detail.get_media.nama_media">
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">Jenis Media:</label>
                            <input type="text" class="form-control" id="message-text" readonly
                                wire:model="detail.get_media.jenis_media">
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-animation btn-md fw-bold" data-bs-dismiss="modal"
                        wire:click="closeModal">
                        Lihat Media
                    </button>
                    <button type="button" class="btn btn-animation btn-md fw-bold" data-bs-dismiss="modal"
                        wire:click="closeModal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
