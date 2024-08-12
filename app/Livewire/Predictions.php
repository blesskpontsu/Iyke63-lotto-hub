<?php

namespace App\Livewire;

use App\Models\Forcast;
use Livewire\Component;

class Predictions extends Component
{
    public function render()
    {
        return view('livewire.predictions', [
            'predictions' => Forcast::query()->latest()->whereDate('created_at', today())->get()
        ]);
    }
}
