<?php

namespace App\Livewire;

use App\Models\Announcement;
use Livewire\Component;

class Dashboard extends Component
{

    public function requestBet()
    {
        $this->redirect(route('request.bet'), navigate: true);
    }

    public function lottoResults()
    {
        $this->redirect(route('lotto.results'), navigate: true);
    }

    public function videos()
    {
        $this->redirect(route('videos'), navigate: true);
    }

    public function tickets()
    {
        $this->redirect(route('recent.bets'), navigate: true);
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'announcements' => Announcement::all()
        ]);
    }
}
