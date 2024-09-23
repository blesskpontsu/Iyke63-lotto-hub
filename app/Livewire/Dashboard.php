<?php

namespace App\Livewire;

use App\Models\Announcement;
use Livewire\Component;

class Dashboard extends Component
{

    public function requestBet()
    {
        $this->redirectIntended(default: route('request.bet', absolute: true), navigate: true);
    }

    public function lottoResults()
    {
        $this->redirectIntended(default: route('lotto.results', absolute: true), navigate: true);
    }

    public function videos()
    {
        $this->redirectIntended(default: route('videos', absolute: true), navigate: true);
    }

    public function tickets()
    {
        $this->redirectIntended(default: route('recent.bets', absolute: true), navigate: true);
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'announcements' => Announcement::all()
        ]);
    }
}
