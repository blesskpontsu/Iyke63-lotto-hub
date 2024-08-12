<?php

namespace App\Livewire;

use App\Models\Announcement;
use Livewire\Component;

class Dashboard extends Component
{

    public function requestBet()
    {
        $this->redirectIntended(default: route('request.bet', absolute: false), navigate: true);
    }

    public function lottoResults()
    {
        $this->redirectIntended(default: route('lotto.results', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'announcements' => Announcement::all()
        ]);
    }
}
