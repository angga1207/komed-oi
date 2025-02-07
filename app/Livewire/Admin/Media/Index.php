<?php

namespace App\Livewire\Admin\Media;

use Livewire\Component;
use App\Models\MediaPers;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Index extends Component
{
    use LivewireAlert, WithPagination;
    protected $paginationTheme = 'bootstrap';

    #[Url(null, true, false)]
    public $search;
    public $detail = null, $filterJenisMedia;
    public $bannedId, $activeId;

    function getListeners()
    {
        return [
            'banned' => 'banned',
            'verificate' => 'verificate',
        ];
    }

    public function render()
    {
        $datas = MediaPers::search($this->search)
            ->when($this->filterJenisMedia, function ($query, $filterJenisMedia) {
                return $query->where('jenis_media', $filterJenisMedia);
            })
            ->where('verified_status', 'verified')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.media.index', [
            'datas' => $datas,
        ])
            ->layout('layouts.app', [
                'title' => 'Daftar Media Pers',
            ]);
    }

    function goSearch()
    {
        $this->resetPage();
    }

    function showDetail($id)
    {
        $mediaPers = MediaPers::with('RegisterFiles')->find($id);
        $mediaPers = $mediaPers->toArray();
        $this->detail = $mediaPers;
        // dd($mediaPers);
    }
}
