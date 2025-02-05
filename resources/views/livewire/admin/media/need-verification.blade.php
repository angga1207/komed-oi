<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <!-- Table Start -->
                <div class="card-body">
                    <div class="title-header option-title">
                        <h5>
                            Daftar Media Pers yang Perlu Diverifikasi
                        </h5>

                        <div class="">
                            <form class="d-flex align-items-center">
                                <input type="search" class="form-control rounded-0 rounded-start" placeholder="Search"
                                    wire:model.live="search">
                                <button type="button" class="btn btn-primary rounded-0 rounded-end"
                                    style="height: 42px !important;">
                                    <i class="ri-search-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table ticket-table all-package theme-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <span>
                                                Nomor Registrasi
                                            </span>
                                        </th>
                                        <th>
                                            <span>
                                                Nama Perusahaan & <br>
                                                Nama Media
                                            </span>
                                        </th>
                                        <th>
                                            <span>
                                                Jenis Media
                                            </span>
                                        </th>
                                        <th>
                                            <span>
                                                Tier / Tier Point
                                            </span>
                                        </th>
                                        <th>
                                            <span>
                                                Status Verifikasi
                                            </span>
                                        </th>
                                        <th>
                                            <span>
                                                Kelengkapan Berkas
                                            </span>
                                        </th>
                                        <th>
                                            <span>
                                                Opsi
                                            </span>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($datas as $data)
                                    <tr>
                                        <td>
                                            <div class="check-box-contain">
                                                {{-- <span class="form-check user-checkbox">
                                                    <input class="checkbox_animated check-it" type="checkbox" value="">
                                                </span> --}}
                                                <span>
                                                    {{ $data->unique_id }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>25-09-2021</td>

                                        <td>Query about return & exchange</td>

                                        <td class="status-danger">
                                            <span>Pending</span>
                                        </td>
                                        <td>
                                            <ul>
                                                <li>
                                                    <a href="javascript:void(0)">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="javascript:void(0)" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModalToggle">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="100" class="text-center">
                                            Tidak ada data
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Table End -->
            </div>
        </div>
    </div>

</div>
