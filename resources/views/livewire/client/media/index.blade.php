<?php

use Carbon\Carbon;

?>
<div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title flex-wrap gap-2">
                        <h5>
                            Daftar Media Order
                        </h5>
                        <form class="d-inline-flex justify-content-end flex-grow-1 flex-wrap gap-1"
                            wire:submit.prevent="goSearch">
                            <select class="form-control" style="max-width:32%" wire:model.live="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="sent">Belum Dikerjakan</option>
                                <option value="review">Menunggu Review</option>
                                <option value="verified">Terverifikasi</option>
                                <option value="done">Selesai</option>
                            </select>
                            <input type="date" class="form-control" style="max-width:32%"
                                wire:model.live="filterDate" />
                            <input type="search" class="form-control" style="max-width:32%" placeholder="Pencarian..."
                                wire:model="search" />
                            @if($search || $filterDate)
                            <div class="" wire:click.prevent="resetSearch" style="cursor: pointer">
                                <i class="ri-close-circle-line"></i>
                            </div>
                            @endif
                        </form>
                    </div>

                    <div class="table-responsive table-product">
                        <table class="table all-package theme-table" id="table_id">
                            <thead>
                                <tr>
                                    <th style="width:200px">
                                        Kode Order
                                    </th>
                                    <th style="width:200px">
                                        Nama Acara
                                    </th>
                                    <th style="width:200px">
                                        Waktu & Lokasi Pelaksanaan
                                    </th>
                                    <th style="width:150px">
                                        Status
                                    </th>
                                    <th style="width:100px">
                                        Opsi
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($datas as $data)
                                <tr wire:key="{{ $data->id }}">

                                    <td>
                                        <span style="white-space: nowrap; font-size:13px; font-weight:500">
                                            {{ $data->order_code }}
                                        </span>
                                    </td>

                                    <td>
                                        <span style="white-space: normal">
                                            {{ $data->nama_acara }}
                                        </span>
                                    </td>

                                    <td>
                                        <div>
                                            {{ $data->lokasi }}
                                        </div>
                                        <div>
                                            {{ Carbon::parse($data->tanggal_pelaksanaan)->isoFormat('DD MMM Y') }} |
                                            {{ Carbon::parse($data->waktu_pelaksanaan)->isoFormat('HH:mm [WIB]') }}
                                        </div>
                                    </td>

                                    <td>
                                        @if($data->status == 'sent')
                                        <span class="badge badge-success">
                                            Belum Dikerjakan
                                        </span>
                                        <div x-init="startTimer('{{ Carbon::parse($data->created_at)->addDays(7) }}','timer{{ $data->id }}')"
                                            id="timer{{ $data->id }}">
                                        </div>
                                        @elseif($data->status == 'review')
                                        <span class="badge badge-warning">
                                            Sedang Direview Admin
                                        </span>
                                        @elseif($data->status == 'verified')
                                        <span class="badge badge-primary">
                                            Terverifikasi
                                        </span>
                                        @elseif($data->status == 'done')
                                        <span class="badge badge-primary">
                                            Selesai
                                        </span>
                                        @endif
                                    </td>

                                    <td>
                                        <ul>
                                            <li>
                                                <a href="{{ route('media-order.detail', $data->order_code) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <i class="ri-file-chart-line"></i>
                                                </a>
                                            </li>
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

    @push('styles')
    <style>
        .countdown {
            cursor: pointer;
            margin-top: 5px;
            font-size: 12px;
            user-select: none;
        }

        .countdown-end {
            cursor: pointer;
            margin-top: 5px;
            font-size: 10px;
            user-select: none;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function startTimer(date, elemID) {
            // Set the date we're counting down to
            // var countDownDate = new Date("Jan 5, 2021 15:37:25").getTime();
            var countDownDate = new Date(date).getTime();
            console.log(date, countDownDate)

            // Update the count down every 1 second
            var x = setInterval(function() {

            // Get todays date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"


            document.getElementById(elemID).innerHTML = "<div class='countdown'>" + days + " <span> hari </span> " + hours + " <span>jam</span> " + minutes + " <span>mnt </span> " + seconds + "<span> dtk</span> </div>";

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById(elemID).innerHTML = "<div class='countdown-end text-danger'>Sudah Lewat Batas</div>";
            }
            }, 1000);
        }
    </script>
    @endpush
</div>
