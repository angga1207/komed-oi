<div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title">
                        <h5>
                            Daftar Akun Admin
                        </h5>
                        <form class="d-inline-flex">
                            <a class="align-items-center btn btn-theme d-flex" href="javascript:void(0)"
                                data-bs-toggle="modal" data-bs-target="#exampleModalToggle" wire:click="addData()">
                                <i data-feather="plus"></i>
                                Tambah Akun
                            </a>
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
                                        Email
                                    </th>
                                    <th class="text-center" style="width: 100px !important">
                                        Role
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
                                                {{ '@'.$data->username }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        {{ $data->email }}
                                    </td>

                                    <td>
                                        <div class="text-center">
                                            @if($data->role_id == 2)
                                            <span class="badge badge-success">
                                                Admin
                                            </span>
                                            @elseif($data->role_id == 3)
                                            <span class="badge badge-primary">
                                                Verifikator
                                            </span>
                                            @endif
                                        </div>
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
                        @if($inputType == 'create')
                        Tambah Akun Admin
                        @elseif($inputType == 'update')
                        Edit Akun Admin
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
                        <div class="col-12 col-xl-6">
                            <label for="recipient-name" class="col-form-label">Nama Lengkap:</label>
                            <input type="text" class="form-control" id="recipient-name" autocomplete="off"
                                placeholder="Nama Lengkap" wire:model="detail.fullname">

                            @error('detail.fullname')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">Username:</label>
                            <input type="text" class="form-control" id="message-text" autocomplete="off"
                                placeholder="Username" wire:model="detail.username">

                            @error('detail.username')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">Email:</label>
                            <input type="email" class="form-control" id="message-text" autocomplete="off"
                                placeholder="Email" wire:model="detail.email">

                            @error('detail.email')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">Jenis Pengguna:</label>
                            <select class="form-select" wire:model="detail.role_id">
                                @foreach($arrRoles as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->name }}
                                </option>
                                @endforeach
                            </select>

                            @error('detail.role_id')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">Password:</label>
                            <div class="position-relative" x-data={showPassword:false}>
                                <input :type="showPassword ? 'text' : 'password'" class="form-control" id="message-text"
                                    autocomplete="off" placeholder="Password" wire:model="detail.password">

                                <div class="position-absolute top-0 end-0 h-100 d-flex align-items-center">
                                    <button type="button" class="btn" @click="showPassword = !showPassword">
                                        <div x-show="!showPassword" class="" style="height:1.5rem; width:1.5rem">
                                            <svg fill="#000000" height="1.5rem" width="1.5rem" version="1.1" id="Capa_1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 488.85 488.85"
                                                xml:space="preserve">
                                                <g>
                                                    <path
                                                        d="M244.425,98.725c-93.4,0-178.1,51.1-240.6,134.1c-5.1,6.8-5.1,16.3,0,23.1c62.5,83.1,147.2,134.2,240.6,134.2
                                                    s178.1-51.1,240.6-134.1c5.1-6.8,5.1-16.3,0-23.1C422.525,149.825,337.825,98.725,244.425,98.725z M251.125,347.025
                                                    c-62,3.9-113.2-47.2-109.3-109.3c3.2-51.2,44.7-92.7,95.9-95.9c62-3.9,113.2,47.2,109.3,109.3
                                                    C343.725,302.225,302.225,343.725,251.125,347.025z M248.025,299.625c-33.4,2.1-61-25.4-58.8-58.8c1.7-27.6,24.1-49.9,51.7-51.7
                                                    c33.4-2.1,61,25.4,58.8,58.8C297.925,275.625,275.525,297.925,248.025,299.625z" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div x-show="showPassword" class="" style="height:1.5rem; width:1.5rem">
                                            {{-- svg eye-off --}}
                                            <svg fill="#000000" width="1.5rem" height="1.5rem" viewBox="0 0 512 512"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <title>ionicons-v5-i</title>
                                                <path
                                                    d="M432,448a15.92,15.92,0,0,1-11.31-4.69l-352-352A16,16,0,0,1,91.31,68.69l352,352A16,16,0,0,1,432,448Z" />
                                                <path
                                                    d="M248,315.85l-51.79-51.79a2,2,0,0,0-3.39,1.69,64.11,64.11,0,0,0,53.49,53.49A2,2,0,0,0,248,315.85Z" />
                                                <path
                                                    d="M264,196.15,315.87,248a2,2,0,0,0,3.4-1.69,64.13,64.13,0,0,0-53.55-53.55A2,2,0,0,0,264,196.15Z" />
                                                <path
                                                    d="M491,273.36a32.2,32.2,0,0,0-.1-34.76c-26.46-40.92-60.79-75.68-99.27-100.53C349,110.55,302,96,255.68,96a226.54,226.54,0,0,0-71.82,11.79,4,4,0,0,0-1.56,6.63l47.24,47.24a4,4,0,0,0,3.82,1.05,96,96,0,0,1,116,116,4,4,0,0,0,1.05,3.81l67.95,68a4,4,0,0,0,5.4.24A343.81,343.81,0,0,0,491,273.36Z" />
                                                <path
                                                    d="M256,352a96,96,0,0,1-93.3-118.63,4,4,0,0,0-1.05-3.81L94.81,162.69a4,4,0,0,0-5.41-.23c-24.39,20.81-47,46.13-67.67,75.72a31.92,31.92,0,0,0-.64,35.54c26.41,41.33,60.39,76.14,98.28,100.65C162.06,402,207.92,416,255.68,416a238.22,238.22,0,0,0,72.64-11.55,4,4,0,0,0,1.61-6.64l-47.47-47.46a4,4,0,0,0-3.81-1.05A96,96,0,0,1,256,352Z" />
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            @error('detail.password')
                            <div class="text-danger mt-1" style="font-size: 0.8rem;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="message-text" class="col-form-label">Konfirmasi Password:</label>
                            <div class="position-relative" x-data={showPassword:false}>
                                <input :type="showPassword ? 'text' : 'password'" class="form-control" id="message-text"
                                    autocomplete="off" placeholder="Password" wire:model="detail.password_confirmation">

                                <div class="position-absolute top-0 end-0 h-100 d-flex align-items-center">
                                    <button type="button" class="btn" @click="showPassword = !showPassword">
                                        <div x-show="!showPassword" class="" style="height:1.5rem; width:1.5rem">
                                            <svg fill="#000000" height="1.5rem" width="1.5rem" version="1.1" id="Capa_1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 488.85 488.85"
                                                xml:space="preserve">
                                                <g>
                                                    <path
                                                        d="M244.425,98.725c-93.4,0-178.1,51.1-240.6,134.1c-5.1,6.8-5.1,16.3,0,23.1c62.5,83.1,147.2,134.2,240.6,134.2
                                                    s178.1-51.1,240.6-134.1c5.1-6.8,5.1-16.3,0-23.1C422.525,149.825,337.825,98.725,244.425,98.725z M251.125,347.025
                                                    c-62,3.9-113.2-47.2-109.3-109.3c3.2-51.2,44.7-92.7,95.9-95.9c62-3.9,113.2,47.2,109.3,109.3
                                                    C343.725,302.225,302.225,343.725,251.125,347.025z M248.025,299.625c-33.4,2.1-61-25.4-58.8-58.8c1.7-27.6,24.1-49.9,51.7-51.7
                                                    c33.4-2.1,61,25.4,58.8,58.8C297.925,275.625,275.525,297.925,248.025,299.625z" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div x-show="showPassword" class="" style="height:1.5rem; width:1.5rem">
                                            {{-- svg eye-off --}}
                                            <svg fill="#000000" width="1.5rem" height="1.5rem" viewBox="0 0 512 512"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <title>ionicons-v5-i</title>
                                                <path
                                                    d="M432,448a15.92,15.92,0,0,1-11.31-4.69l-352-352A16,16,0,0,1,91.31,68.69l352,352A16,16,0,0,1,432,448Z" />
                                                <path
                                                    d="M248,315.85l-51.79-51.79a2,2,0,0,0-3.39,1.69,64.11,64.11,0,0,0,53.49,53.49A2,2,0,0,0,248,315.85Z" />
                                                <path
                                                    d="M264,196.15,315.87,248a2,2,0,0,0,3.4-1.69,64.13,64.13,0,0,0-53.55-53.55A2,2,0,0,0,264,196.15Z" />
                                                <path
                                                    d="M491,273.36a32.2,32.2,0,0,0-.1-34.76c-26.46-40.92-60.79-75.68-99.27-100.53C349,110.55,302,96,255.68,96a226.54,226.54,0,0,0-71.82,11.79,4,4,0,0,0-1.56,6.63l47.24,47.24a4,4,0,0,0,3.82,1.05,96,96,0,0,1,116,116,4,4,0,0,0,1.05,3.81l67.95,68a4,4,0,0,0,5.4.24A343.81,343.81,0,0,0,491,273.36Z" />
                                                <path
                                                    d="M256,352a96,96,0,0,1-93.3-118.63,4,4,0,0,0-1.05-3.81L94.81,162.69a4,4,0,0,0-5.41-.23c-24.39,20.81-47,46.13-67.67,75.72a31.92,31.92,0,0,0-.64,35.54c26.41,41.33,60.39,76.14,98.28,100.65C162.06,402,207.92,416,255.68,416a238.22,238.22,0,0,0,72.64-11.55,4,4,0,0,0,1.61-6.64l-47.47-47.46a4,4,0,0,0-3.81-1.05A96,96,0,0,1,256,352Z" />
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            @error('detail.password_confirmation')
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
