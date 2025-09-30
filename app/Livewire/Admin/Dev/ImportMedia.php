<?php

namespace App\Livewire\Admin\Dev;

use App\Models\User;
use Livewire\Component;
use App\Models\MediaPers;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ImportMedia extends Component
{
    use LivewireAlert, WithFileUploads;
    public $file;
    public $dataPreview = [];
    public $type = null;

    public function render()
    {
        return view('livewire.admin.dev.import-media');
    }

    function updatedFile()
    {
        $this->resetErrorBag();
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls', // 2MB Max
        ]);

        $filePath = $this->file->getRealPath();
        $dataExcel = Excel::toArray(new \App\Imports\MediaImport, $filePath)[0];
        // check validate
        $header = $dataExcel[0];
        $requiredHeaders1 = ['NO.', 'NAMA MEDIA', 'NAMA PERUSAHAAN', 'ALAMAT', 'PLATFORM'];
        $requiredHeaders2 = ['NO.', 'NAMA AKUN', 'NAMA PERUSAHAAN', 'ALAMAT', 'PLATFORM'];
        $requiredHeaders3 = ['NO.', 'NAMA PERUSAHAAN', 'ALAMAT', 'PLATFORM'];

        $type = 1;

        // check required headers 1 or 2
        if ($header === $requiredHeaders1) {
            // valid
            $type = 1; // Media Cetak, Media Elektronik, Media Siber
        } elseif ($header === $requiredHeaders2) {
            // valid
            $type = 2; // Media Sosial
        } elseif ($header === $requiredHeaders3) {
            // valid
            $type = 3; // Multimedia
        } else {
            $this->addError('file', 'Invalid file format. Please use the provided template.');
            return;
        }
        $this->type = $type;


        $datas = array_slice($dataExcel, 1);
        if ($type === 1 || $type === 2) {
            foreach ($datas as $index => $row) {
                if (count($row) < count($requiredHeaders1)) {
                    $this->addError('file', "Row " . ($index + 2) . " is incomplete.");
                }

                // if empty skip
                if (empty($row[1]) && empty($row[2]) && empty($row[3]) && empty($row[4])) {
                    unset($datas[$index]);
                    continue;
                }

                // generate username & random password
                $username = strtolower(preg_replace('/\s+/', '_', $row[1]));
                // replace special character to underscore
                $username = preg_replace('/[^A-Za-z0-9_]/', '', $username);
                // check if username exists in previous rows
                $existingUsernames = array_map(function ($r) {
                    return strtolower(preg_replace('/\s+/', '_', $r[1]));
                }, array_slice($datas, 0, $index));
                $counter = 1;
                while (in_array($username, $existingUsernames)) {
                    $username = strtolower(preg_replace('/\s+/', '_', $row[1])) . $counter;
                    $username = preg_replace('/[^A-Za-z0-9_]/', '', $username);
                    $counter++;
                }
                // check if username exists in database
                while (\App\Models\User::where('username', $username)->exists()) {
                    $username = strtolower(preg_replace('/\s+/', '_', $row[1])) . $counter;
                    $username = preg_replace('/[^A-Za-z0-9_]/', '', $username);
                    $counter++;
                }

                $row[] = $username;

                $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
                $row[] = $password;

                $datas[$index] = $row;
            }
        } elseif ($type === 3) {
            foreach ($datas as $index => $row) {
                if (count($row) < count($requiredHeaders3)) {
                    $this->addError('file', "Row " . ($index + 2) . " is incomplete.");
                }

                // if empty skip
                if (empty($row[1]) && empty($row[2]) && empty($row[3])) {
                    unset($datas[$index]);
                    continue;
                }

                // generate username & random password
                $username = strtolower(preg_replace('/\s+/', '_', $row[1]));
                // replace special character to underscore
                $username = preg_replace('/[^A-Za-z0-9_]/', '', $username);
                // check if username exists in previous rows
                $existingUsernames = array_map(function ($r) {
                    return strtolower(preg_replace('/\s+/', '_', $r[1]));
                }, array_slice($datas, 0, $index));
                $counter = 1;
                while (in_array($username, $existingUsernames)) {
                    $username = strtolower(preg_replace('/\s+/', '_', $row[1])) . $counter;
                    $username = preg_replace('/[^A-Za-z0-9_]/', '', $username);
                    $counter++;
                }
                // check if username exists in database
                while (\App\Models\User::where('username', $username)->exists()) {
                    $username = strtolower(preg_replace('/\s+/', '_', $row[1])) . $counter;
                    $username = preg_replace('/[^A-Za-z0-9_]/', '', $username);
                    $counter++;
                }

                $row[] = $username;

                $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
                $row[] = $password;

                $datas[$index] = $row;
            }
        }

        $this->dataPreview = collect($datas)->values()->toArray();
        // dd($datas);
    }

    function importData()
    {
        $this->resetErrorBag();
        foreach ($this->dataPreview as $row) {
            DB::beginTransaction();
            try {
                // random email
                $randomEmail = rand(1000, 9999) . '@example.com';
                while (User::where('email', $randomEmail)->exists()) {
                    $randomEmail = rand(1000, 9999) . rand(10, 99) . '@example.com';
                }
                // check if username exists
                $user = User::where('fullname', $row[1])->first();
                if (!$user) {
                    $user = User::create([
                        'fullname' => $row[1],
                        'first_name' => explode(' ', $row[1])[0],
                        'last_name' => count(explode(' ', $row[1])) > 1 ? implode(' ', array_slice(explode(' ', $row[1]), 1)) : null,
                        'email' => $randomEmail,
                        'username' => $row[count($row) - 2],
                        'password' => bcrypt($row[count($row) - 1]),
                        'role_id' => 4,
                        'photo' => '/storage/images/users/default.png',
                        'status' => 'active',
                    ]);
                } else {
                    // update name
                    $user->fullname = $row[1];
                    $user->first_name = explode(' ', $row[1])[0];
                    $user->last_name = count(explode(' ', $row[1])) > 1 ? implode(' ', array_slice(explode(' ', $row[1]), 1)) : null;
                    $user->password = bcrypt($row[count($row) - 1]);
                    $user->save();
                }

                $media = MediaPers::where('user_id', $user->id)->first();
                if (!$media) {
                    $media = new MediaPers();
                    $media->user_id = $user->id;
                }
                $media->nik = $row[count($row) - 2];
                $media->verified_status = 'verified';
                if ($this->type === 1) {
                    $jenisMedia = null;
                    if ($row[4] == 'CETAK') {
                        $jenisMedia = 'Media Cetak';
                    } elseif ($row[4] == 'ELEKTRONIK') {
                        $jenisMedia = 'Media Elektronik';
                    } elseif ($row[4] == 'SIBER') {
                        $jenisMedia = 'Media Siber';
                    } elseif ($row[4] == 'MEDIA SOSIAL') {
                        $jenisMedia = 'Media Sosial';
                    } elseif ($row[4] == 'MULTIMEDIA') {
                        $jenisMedia = 'Multimedia';
                    } else {
                        $jenisMedia = 'Lainnya';
                    }
                    $media->jenis_media = $jenisMedia;
                    $media->nama_perusahaan = $row[2];
                    $media->nama_media = $row[1];
                    $media->alamat_media = $row[3];
                } elseif ($this->type === 2) {
                    // Media Sosial
                    $media->jenis_media = 'Media Sosial';
                    $media->account_name = $row[1];
                    $media->nama_perusahaan = $row[2];
                    $media->nama_media = $row[1];
                    $media->alamat_media = $row[3];
                } elseif ($this->type === 3) {
                    // Multimedia
                    $media->jenis_media = 'Multimedia';
                    $media->nama_perusahaan = $row[1];
                    $media->nama_media = $row[1];
                    $media->alamat_media = $row[2];
                }
                $media->save();

                // send email to user
                // \Mail::to($user->email ?? env('MAIL_TO_ADDRESS'))->send(new \App\Mail\MediaAccountCreatedMail($user, $row[count($row) - 1]));

                DB::commit();
                $this->alert('success', 'Berhasil', [
                    'position' =>  'center',
                    'timer' => null,
                    'toast' => false,
                    'text' => 'Berhasil mengimpor data media: ' . $user->fullname,
                    'showCancelButton' => false,
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Tutup',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                $this->addError('file', 'Error importing data: ' . $e->getMessage());
                return;
            }
        }

        // export $this->dataPreview to excel
        $fileName = null;
        $exportedData = [];
        if ($this->type === 1) {
            $fileName = 'imported_media_cetak_elektronik_siber_' . date('Ymd_His') . '.xlsx';
            $exportedData = collect($this->dataPreview)->map(function ($item, $key) {
                return [
                    $item[0], // NO.
                    $item[1], // NAMA MEDIA
                    $item[2], // NAMA PERUSAHAAN
                    // $item[3], // ALAMAT
                    $item[4], // PLATFORM
                    $item[5], // NIK
                    $item[6], // PASSWORD
                ];
            })->toArray();
        } elseif ($this->type === 2) {
            $fileName = 'imported_media_sosial_' . date('Ymd_His') . '.xlsx';
            $exportedData = collect($this->dataPreview)->map(function ($item, $key) {
                return [
                    $item[0], // NO.
                    $item[1], // NAMA MEDIA
                    $item[2], // NAMA PERUSAHAAN
                    // $item[3], // ALAMAT
                    $item[4], // PLATFORM
                    $item[5], // NIK
                    $item[6], // PASSWORD
                ];
            })->toArray();
        } elseif ($this->type === 3) {
            $fileName = 'imported_multimedia_' . date('Ymd_His') . '.xlsx';
            $exportedData = collect($this->dataPreview)->map(function ($item, $key) {
                return [
                    $item[0], // NO.
                    $item[1], // NAMA PERUSAHAAN
                    $item[1], // NAMA PERUSAHAAN
                    // $item[2], // ALAMAT
                    $item[3], // PLATFORM
                    $item[4], // NIK
                    $item[5], // PASSWORD
                ];
            })->toArray();
        }
        return Excel::download(new \App\Exports\MediaAfterImport($exportedData), $fileName);

        session()->flash('message', 'Data imported successfully.');
        // reset
        $this->reset(['file', 'dataPreview', 'type']);
    }
}
