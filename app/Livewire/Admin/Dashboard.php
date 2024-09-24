<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\RequestBet;

class Dashboard extends Component
{
    public ?RequestBet $selectedRequest = null;  // Make it nullable

    protected $listeners = ['refreshBets' => '$refresh', 'betUpdated' => 'clearSelectedRequest'];

    public function clearSelectedRequest()
    {
        $this->selectedRequest = null; // Clear the selected request when the bet is updated
    }

    public function forcast(): void
    {
        $this->redirect('/admin/forcast', navigate: true);
    }

    public function result(): void
    {
        $this->redirect('/admin/results', navigate: true);
    }

    public function videos(): void
    {
        $this->redirect('/admin/videos', navigate: true);
    }

    public function viewRequest(RequestBet $request)
    {
        $this->selectedRequest = $request;

        $this->dispatch('open-modal', name: 'view.request');
    }

    public function render()
    {
        $requests = RequestBet::latest()->where('status', 'paid')->get();
        return view('livewire.admin.dashboard', [
            'requests' => $requests
        ]);
    }
}
