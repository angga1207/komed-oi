<div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="card">
            <h4 class="mb-4">Import Media</h4>
            <div class="card-body">
                <div class="mb-3">
                    <label for="file" class="form-label">Pilih File Excel</label>
                    <input type="file" class="form-control" id="file" wire:model.live="file" accept=".xlsx, .xls">
                    @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            @if (!empty($dataPreview))
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Preview Data</h5>
                    @if (!empty($dataPreview))
                    <button class="btn btn-primary" wire:click="importData">Import Data</button>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            @if($type === 1)
                            <tr>
                                <th>No</th>
                                <th>Nama Media</th>
                                <th>Nama Perusahaan</th>
                                <th>Alamat</th>
                                <th>Kategori</th>
                                <th>Username</th>
                                <th>Password</th>
                            </tr>
                            @elseif($type === 2)
                            <tr>
                                <th>No</th>
                                <th>Nama Akun</th>
                                <th>Nama Perusahaan</th>
                                <th>Alamat</th>
                                <th>Kategori</th>
                                <th>Username</th>
                                <th>Password</th>
                            </tr>
                            @elseif($type === 3)
                            <tr>
                                <th>No</th>
                                <th>Nama Perusahaan</th>
                                <th>Alamat</th>
                                <th>Kategori</th>
                                <th>Username</th>
                                <th>Password</th>
                            </tr>
                            @endif
                        </thead>
                        <tbody>
                            @foreach ($dataPreview as $index => $row)
                            @if($type === 1 || $type === 2)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row[1] ?? '' }}</td>
                                <td>{{ $row[2] ?? '' }}</td>
                                <td>{{ $row[3] ?? '' }}</td>
                                <td>{{ $row[4] ?? '' }}</td>
                                <td>{{ $row[5] ?? '' }}</td>
                                <td>{{ $row[6] ?? '' }}</td>
                            </tr>
                            @elseif($type === 3)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row[1] ?? '' }}</td>
                                <td>{{ $row[2] ?? '' }}</td>
                                <td>{{ $row[3] ?? '' }}</td>
                                <td>{{ $row[4] ?? '' }}</td>
                                <td>{{ $row[5] ?? '' }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
    <!-- Container-fluid Ends-->
</div>
