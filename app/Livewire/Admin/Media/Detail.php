<?php

namespace App\Livewire\Admin\Media;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Detail extends Component
{
    use LivewireAlert, WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $pers;
    #[Url(null, true, false)]
    public $search;

    function mount($unique_id)
    {
        $pers = DB::table('pers_profile')->where('unique_id', $unique_id)->firstOrFail();
        $this->pers = $pers;
    }

    public function render()
    {
        $mediaOrders = Order::search($this->search)
            ->where('media_id', $this->pers->id)
            ->latest('tanggal_pelaksanaan')
            ->latest('waktu_pelaksanaan')
            ->paginate(3);

        $arrRegFiles = [];
        $typeFiles = DB::table('pers_profile_files')
            ->where('pers_profile_id', $this->pers->id)
            ->pluck('file_type')
            ->unique();
        foreach ($typeFiles as $type) {
            $file = DB::table('pers_profile_files')
                ->where('pers_profile_id', $this->pers->id)
                ->where('file_type', $type)
                ->latest('created_at')
                ->first();

            if ($file) {
                $arrRegFiles[] = [
                    'id' => $file->id,
                    'title' => $file->title,
                    'file_type' => $type,
                    'file_name' => $file->file_name,
                    'file_path' => asset($file->file_path),
                    'created_at' => $file->created_at,
                    'updated_at' => $file->updated_at,
                ];
            }
        }
        // dd($arrRegFiles);

        return view('livewire.admin.media.detail', [
            'mediaOrders' => $mediaOrders,
            'arrRegFiles' => $arrRegFiles,
        ])
            ->layout('layouts.app', [
                'title' => $this->pers->nama_media,
            ]);
    }
}
