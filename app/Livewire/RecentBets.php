<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\RequestBet;
use Illuminate\Support\Facades\Auth;

class RecentBets extends Component
{
    public function render()
    {
        $user = Auth::user();
        $tickets = RequestBet::query()->where('user_id', $user->id)->latest()->get();

        return view('livewire.recent-bets', [
            'tickets' => $tickets
        ]);
    }
}
