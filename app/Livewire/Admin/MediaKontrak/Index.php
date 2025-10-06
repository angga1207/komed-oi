<?php

namespace App\Livewire\Admin\MediaKontrak;

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
    public $year;

    function mount()
    {
        $this->year = date('Y');
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

        return view('livewire.admin.media-kontrak.index', [
            'datas' => $datas,
        ])
            ->layout('layouts.app', [
                'title' => 'Kontrak Media',
            ]);
    }

    function updated($field)
    {
        if ($field == 'year') {
            $this->resetPage();
        }
        if ($field == 'filterJenisMedia') {
            $this->resetPage();
        }
    }

    function goSearch()
    {
        $this->resetPage();
    }
}
