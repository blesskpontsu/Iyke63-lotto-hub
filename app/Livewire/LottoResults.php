<?php

namespace App\Livewire;

use App\Models\Result;
use Carbon\Carbon;
use Livewire\Component;

class LottoResults extends Component
{
    public function render()
    {
        return view('livewire.lotto-results', [
            'today' => Carbon::now()->toDateString(),
            'results' => Result::latest()->limit(10)->get()
        ]);
    }
}
