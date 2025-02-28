<?php

namespace App\Livewire\Client\Profile;

use App\Models\MediaPers;
use Livewire\Component;

class Index extends Component
{
    public $detail = null;

    public function mount()
    {
        $mediaPers = MediaPers::where('user_id', auth()->id())->with('RegisterFiles')->first();
        $mediaPers = $mediaPers->toArray();
        $this->detail = $mediaPers;}

    public function render()
    {
        return view('livewire.client.profile.index');
    }
}
