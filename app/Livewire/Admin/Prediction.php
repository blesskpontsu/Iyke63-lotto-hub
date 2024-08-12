<?php

namespace App\Livewire\Admin;

use App\Models\Forcast;
use Livewire\Component;

class Prediction extends Component
{
    public function render()
    {
        return view('livewire.admin.prediction', [
            'predictions' => Forcast::query()->latest()->whereDate('created_at', today())->get()
        ]);
    }
}
