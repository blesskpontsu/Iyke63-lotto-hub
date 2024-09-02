<?php

namespace App\Livewire;

use App\Models\Video as ModelsVideo;
use Carbon\Carbon;
use Livewire\Component;

class Video extends Component
{
    public function render()
    {
        return view('livewire.video', [
            'videos' => ModelsVideo::latest()->whereDate('created_at', Carbon::today())->get()
        ]);
    }
}
