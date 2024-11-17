<?php

namespace App\Livewire\Auth;

use App\Models\Plan;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Plans extends Component
{

    public function chooseMedium(string $plan_id)
    {
        // $this->redirect('/subscribe?token=' . $plan_id, navigate: true);

        return redirect('/subscribe?token=' . $plan_id);
    }


    public function render()
    {
        return view('livewire.auth.plans', [
            'basic' => Plan::where('name', 'IYKE63 Basic')->first(),
            'deluxe' => Plan::where('name', 'IYKE63 Deluxe')->first(),
            'gold' => Plan::where('name', 'IYKE63 Gold')->first(),
        ]);
    }
}
