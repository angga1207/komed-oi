<?php

use Carbon\Carbon;

?>
<div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title flex-wrap align-items-center justify-content-between gap-2">
                        <h5>
                            Daftar Media Order <br>
                            {{ Carbon::parse($filterDate)->isoFormat('dddd, DD MMMM Y') }}
                        </h5>

                        <a href="{{ route('a.media-order.create',[
                            'filterDate' => $filterDate
                        ]) }}" class="btn btn-outline">
                            Buat Media Order
                        </a>
                    </div>

                    <form class="mb-2 d-flex align-items-center justify-content-between flex-grow-1 flex-wrap gap-1"
                        wire:submit.prevent="goSearch">
                        <select class="form-control" style="max-width:32%" wire:model.live="filterStatus">
                            <option value="">Semua Status</option>
                            Dikirim</option>
                            <option value="review">Menunggu Review</option>
                            <option value="rejected">Dikembalikan</option>
                            <option value="verified">Terverifikasi</option>
                            <option value="done">Selesai</option>
                        </select>
                        <input type="date" class="form-control" style="max-width:32%" wire:model.live="filterDate" />
                        <input type="search" class="form-control" style="max-width:32%" placeholder="Pencarian..."
                            wire:model="search" />
                        @if($search || $filterDate)
                        <div class="" wire:click.prevent="resetSearch" style="cursor: pointer">
                            <i class="ri-close-circle-line"></i>
                        </div>
                        @endif
                    </form>

                    <div class="table-responsive table-product">
                        <table class="table all-package theme-table" id="table_id">
                            <thead>
                                <tr>
                                    <th style="width:400px">
                                        Media Order
                                    </th>
                                    <th style="width:200px">
                                        Media Pers
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
                                        <div style="white-space: nowrap;" class="border-bottom pb-1 fw-bold">
                                            {{ $data->order_code }}
                                        </div>
                                        <div style="white-space: normal" class="border-bottom pb-1 mt-1">
                                            {{ $data->nama_acara }}
                                        </div>
                                        <div class="border-bottom pb-1 mt-1 d-flex align-items-center gap-1">
                                            <i class="ri-map-pin-line"></i>
                                            <span style="white-space: normal">
                                                {{ $data->lokasi }}
                                            </span>
                                        </div>
                                        <div class="mt-1 d-flex align-items-center gap-1">
                                            <i class="ri-calendar-2-line"></i>
                                            <span>
                                                {{ Carbon::parse($data->tanggal_pelaksanaan)->isoFormat('DD MMM Y') }} |
                                                {{ Carbon::parse($data->waktu_pelaksanaan)->isoFormat('HH:mm [WIB]') }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="mt-1 d-flex align-items-center gap-1">
                                            <i class="ri-government-line"></i>
                                            <span>
                                                {{ $data->MediaPers->nama_media }}
                                            </span>
                                        </div>
                                        <div class="mt-1 d-flex align-items-center gap-1">
                                            <i class="ri-government-fill"></i>
                                            <span>
                                                {{ $data->MediaPers->nama_perusahaan }}
                                            </span>
                                        </div>
                                        <div class="mt-1 d-flex align-items-center gap-1">
                                            <i class="ri-newspaper-line"></i>
                                            <span>
                                                {{ $data->MediaPers->jenis_media }}
                                            </span>
                                        </div>
                                        <div class="mt-1 d-flex align-items-center gap-1">
                                            <i class="ri-phone-line"></i>
                                            <span>
                                                {{ $data->MediaPers->whatsapp }}
                                            </span>
                                        </div>
                                        <div class="mt-1 d-flex align-items-center gap-1">
                                            <i class="ri-medal-line"></i>
                                            <span>
                                                Tier {{ $data->MediaPers->tier }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        @if($data->status == 'sent')
                                        <span class="badge badge-success">
                                            Dikirim
                                        </span>
                                        <div x-init="startTimer('{{ Carbon::parse($data->deadline) }}','timer{{ $data->id }}')"
                                            id="timer{{ $data->id }}">
                                        </div>
                                        @elseif($data->status == 'review')
                                        <span class="badge badge-warning">
                                            Menunggu Review
                                        </span>
                                        @elseif($data->status == 'rejected')
                                        <span class="badge badge-warning">
                                            Dikembalikan
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

                                    <td class="text-center">
                                        <ul>
                                            <li>
                                                <a href="{{ route('a.media-order.detail', $data->order_code) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <i class="ri-file-chart-line"></i>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="" style="font-size: 10px">
                                            <div class="">
                                                dibuat:
                                            </div>
                                            <div class="">
                                                {{ Carbon::parse($data->created_at)->isoFormat('DD MMM Y, HH:mm
                                                [WIB]')
                                                }}
                                            </div>
                                        </div>
                                        @if($data->created_at != $data->updated_at)
                                        <div class="" style="font-size: 10px">
                                            <div class="">
                                                diperbarui:
                                            </div>
                                            <div class="">
                                                {{ Carbon::parse($data->updated_at)->isoFormat('DD MMM Y, HH:mm
                                                [WIB]')
                                                }}
                                            </div>
                                        </div>
                                        @endif
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
