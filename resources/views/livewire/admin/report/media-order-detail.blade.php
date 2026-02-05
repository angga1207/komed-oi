<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center pb-2">
            <div class="">
                <h5>
                    Rekap Media Order {{ $media->nama_media }} Tahun {{ $filterTahun }}
                </h5>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <select wire:model.live="filterTahun" class="form-select form-select-sm">
                    @foreach ($years as $year)
                        <option value="{{ $year }}">
                            {{ $year }}
                        </option>
                    @endforeach
                </select>

                <a class="btn btn-solid" href="{{ route('reports.media-order', ['filterTahun' => $filterTahun]) }}">
                    <i class="fa fa-arrow-left me-2"></i>
                    Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="display" id="myTable" style="height: calc(100vh - 400px);">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Perusahaan</th>
                            <th>Nama Media</th>
                            <th>Jenis Media</th>
                            <th>Nomor Media Order</th>
                            <th>Tanggal Media Order</th>
                            <th>Tanggal Terbitan</th>
                            <th>Nama Acara</th>
                            <th>Status</th>
                            <th>Evident</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->MediaPers->nama_perusahaan }}</td>
                                <td>{{ $data->MediaPers->nama_media }}</td>
                                <td>{{ $data->MediaPers->jenis_media }}</td>
                                <td>{{ $data->order_code }}</td>
                                <td>{{ Carbon\Carbon::parse($data->tanggal_pelaksanaan)->isoFormat('DD MMM Y') }}</td>
                                <td>{{ Carbon\Carbon::parse($data->tanggal_pelaksanaan)->isoFormat('DD MMM Y') }}</td>
                                <td>{{ $data->nama_acara }}</td>
                                <td>
                                    @if ($data->status == 'unsent')
                                        Belum Dikirimkan
                                    @endif
                                    @if ($data->status == 'sent')
                                        Sedang Dikerjakan
                                    @endif
                                    @if ($data->status == 'review')
                                        Dalam Review
                                    @endif
                                    @if ($data->status == 'verified')
                                        Terverifikasi
                                    @endif
                                    @if ($data->status == 'rejected')
                                        Ditolak
                                    @endif
                                    @if ($data->status == 'done')
                                        Selesai
                                    @endif
                                </td>
                                <td>
                                    @if ($data->Evidences->count() > 0)
                                        @foreach ($data->Evidences as $evident)
                                            <a href="{{ $evident->url }}" target="_blank">
                                                Lihat Evident
                                            </a>
                                            <br>
                                        @endforeach
                                    @else
                                        Tidak ada evident
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('styles')
        <link
            href="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.3.7/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/date-1.6.3/fh-4.0.5/r-3.0.8/sl-3.1.3/datatables.min.css"
            rel="stylesheet" integrity="sha384-tkk4xI2Sm7uIYg6ey7+z/21biR03RTH9Yutg6OqQHHBjt93MjJJGoQXa6hQ7Rp28"
            crossorigin="anonymous">
    @endpush
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"
            integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"
            integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous">
        </script>
        <script
            src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.3.7/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/date-1.6.3/fh-4.0.5/r-3.0.8/sl-3.1.3/datatables.min.js"
            integrity="sha384-X8KcR1t8ABE5AKej/GpZ6qexslUh96QohOjEhkSYyy2gB0+vAkf3d6QVXOXGOWLq" crossorigin="anonymous">
        </script>
        <script>
            let table = new DataTable('#myTable', {
                layout: {
                    topStart: {
                        buttons: [{
                                extend: 'excelHtml5',
                                titleAttr: 'Export to Excel',
                                exportOptions: {
                                    columns: ':not(.no-export)'
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                titleAttr: 'Export to PDF',
                                exportOptions: {
                                    columns: ':not(.no-export)'
                                }
                            },
                        ]
                    }
                }
            });
        </script>
    @endpush
</div>
