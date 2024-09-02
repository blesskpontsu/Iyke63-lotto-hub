<?php

namespace App\Livewire\Admin\Video;

use App\Models\Video;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['refreshVideos' => '$refresh'];

    public function render()
    {
        return view('livewire.admin.video.index', [
            'videos' => Video::latest()->take(5)->get()
        ]);
    }
}
