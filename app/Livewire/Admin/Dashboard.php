<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\RequestBet;

class Dashboard extends Component
{
    public function forcast(): void
    {
        $this->redirect('/admin/forcast', navigate: true);
    }

    public function result(): void
    {
        $this->redirect('/admin/results', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'requests' => RequestBet::latest()->get()
        ]);
    }
}
