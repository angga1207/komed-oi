<?php

use Carbon\Carbon;

?>

<div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <!-- Table Start -->
                <div class="card-body">
                    <div class="title-header option-title">
                        <h5>
                            Kontrak Media
                        </h5>

                        <div class="d-flex align-items-center gap-2">
                            <form class="d-flex align-items-center" wire:submit='goSearch'>
                                <input type="search" class="form-control rounded-0 rounded-start" placeholder="Search"
                                    wire:model="search">
                                <button type="submit" class="btn btn-primary rounded-0 rounded-end"
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
                                                Nama Perusahaan & <br>
                                                Nama Media
                                            </span>
                                        </th>
                                        <th>
                                            <span>
                                                Jenis Media
                                            </span>
                                            <select class="form-select" wire:model.live="filterJenisMedia">
                                                <option value="">Semua Media</option>
                                                <option value="Media Cetak">Media Cetak</option>
                                                <option value="Media Elektronik">Media Elektronik</option>
                                                <option value="Media Siber">Media Siber</option>
                                                <option value="Media Sosial">Media Sosial</option>
                                                <option value="Multimedia">Multimedia</option>
                                            </select>
                                        </th>
                                        <th>
                                            <span>
                                                Nilai Kontrak
                                            </span>
                                            <select class="form-select rounded-0 rounded-start " wire:model.live='year'>
                                                @for ($i = date('Y') + 1; $i >= 2024; $i--)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
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
                                            <div class="d-flex flex-column">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="ri-building-2-line"></i>
                                                    {{ $data->nama_perusahaan }}
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="ri-newspaper-line"></i>
                                                    {{ $data->nama_media }}
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            {{ $data->jenis_media }}
                                        </td>

                                        <td>
                                            <div class="">
                                                <div
                                                    class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                                    <div>Induk:</div>
                                                    <span>
                                                        Rp. {{ $data->KontrakInduk($year)->first() ?
                                                        number_format($data->KontrakInduk($year)->first()->nilai_kontrak,
                                                        0,
                                                        ',', '.') : 0 }}
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>APBDP:</div>
                                                    <span>
                                                        Rp. {{ $data->KontrakAPBDP($year)->first() ?
                                                        number_format($data->KontrakAPBDP($year)->first()->nilai_kontrak,
                                                        0,
                                                        ',', '.') : 0 }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <ul>
                                                <li>
                                                    <a href="{{ route('a.media-kontrak.detail', $data->unique_id) }}"
                                                        class="badge badge-primary d-flex align-items-center gap-1">
                                                        <i class="ri-eye-line text-white"></i>
                                                        Detail
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
                <!-- Table End -->
            </div>
        </div>
    </div>
</div>
