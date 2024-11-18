<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class VerifyInvoice extends Component
{
    public $rIId;
    public $rId;
    public $optP;
    protected $queryString = ['rIId', 'rId', 'optP'];

    public function verifyInvoice() {
        
    }

    public function render()
    {
        return view('livewire.auth.verify-invoice');
    }
}
