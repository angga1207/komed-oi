<div>
    <div class="card">
        <div class="card-header">
            <h5>Laporan</h5>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <a href="{{ route('reports.media') }}" class="main-tiles border card-hover card o-hidden">
                        <div class="custome-1-bg b-r-4 card-body">
                            <div class="media align-items-center static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">
                                        Jumlah Mitra Media
                                    </span>
                                    <h4 class="mb-0 counter">
                                        {{ $countMedia }}
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-database-2-line"></i>
                                </div>
                            </div>
                            <div class="font-italic" style="font-style: italic;">
                                Klik untuk melihat detail
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <a href="{{ route('reports.kerja-sama') }}" class="main-tiles border card-hover card o-hidden">
                        <div class="custome-1-bg b-r-4 card-body">
                            <div class="media align-items-center static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">
                                        Nilai Kerja Sama
                                    </span>
                                    <h4 class="mb-0 counter">
                                        Rp. {{ $countNilaiKerjaSama }}
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </div>
                            </div>
                            <div class="font-italic" style="font-style: italic;">
                                Klik untuk melihat detail
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <a href="{{ route('reports.media-order') }}" class="main-tiles border card-hover card o-hidden">
                        <div class="custome-1-bg b-r-4 card-body">
                            <div class="media align-items-center static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">
                                        Media Order Per Media
                                    </span>
                                    <h4 class="mb-0 counter">
                                        {{ $countMediaOrder }}
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-rss-line"></i>
                                </div>
                            </div>
                            <div class="font-italic" style="font-style: italic;">
                                Klik untuk melihat detail
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <a href="{{ route('reports.anggaran-per-media') }}"
                        class="main-tiles border card-hover card o-hidden">
                        <div class="custome-1-bg b-r-4 card-body">
                            <div class="media align-items-center static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">
                                        Realisasi Anggaran Per Media
                                    </span>
                                    <h4 class="mb-0 counter">
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </div>
                            </div>
                            <div class="font-italic" style="font-style: italic;">
                                Klik untuk melihat detail
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <a href="{{ route('reports.data-bpk') }}" class="main-tiles border card-hover card o-hidden">
                        <div class="custome-1-bg b-r-4 card-body">
                            <div class="media align-items-center static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">
                                        Data Permintaan BPK
                                    </span>
                                    <h4 class="mb-0 counter">
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-reddit-line"></i>
                                </div>
                            </div>
                            <div class="font-italic" style="font-style: italic;">
                                Klik untuk melihat detail
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
