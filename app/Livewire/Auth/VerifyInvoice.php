<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use WireUi\Traits\WireUiActions;

class VerifyInvoice extends Component
{
    use WireUiActions;

    public $rIId;
    public $rId;
    public $optP;
    protected $queryString = ['rIId', 'rId', 'optP'];

    #[Validate(['required', 'string', 'min:4', 'max:4'])]
    public $otp;


    public function verifyInvoice()
    {
        $this->validate();

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode('wmJBkgm:808a6b5717dd4c839ad73fa5cd6ce46c'),
            'Cache-Control' => 'no-cache'
        ];

        $data = [
            "recurringInvoiceId" => $this->rIId,
            "requestId" => $this->rId,
            "otpCode" => "{$this->optP}-{$this->otp}"
        ];

        $request = Http::withHeaders($headers)->post('https://rip.hubtel.com/api/proxy/verify-invoice', $data);

        $response = $request->json();

        Log::info($response);

        if ($response['responseCode'] !== '0001') {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Failed to verify otp!',
                'description' => 'Unable to create invoice.',
            ]);

            return \redirect('/plans');
        }

        $this->redirect('/dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.verify-invoice');
    }
}
