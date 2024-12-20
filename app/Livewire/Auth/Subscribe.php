<?php

namespace App\Livewire\Auth;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Subscription;
use Livewire\Component;
use Illuminate\Http\Request;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Subscribe extends Component
{
    use WireUiActions;

    public $token;
    protected $queryString = ['token'];

    private function isInternetConnected()
    {
        $host = 'www.google.com';
        $port = 80;
        $timeout = 5;

        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$connection) {
            return false;
        }

        fclose($connection);
        return true;
    }

    private function initialize_hubtel_subscription($user_data)
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode('wmJBkgm:808a6b5717dd4c839ad73fa5cd6ce46c'),
            'Cache-Control' => 'no-cache'
        ];

        $http = Http::withHeaders($headers)
            ->post('https://payproxyapi.hubtel.com/items/initiate', $user_data);

        return $http;
    }


    public function hubtel_card_subscription()
    {
        // Check internet connection
        if (!$this->isInternetConnected()) {
            return response()->json(['error' => 'No internet connection']);
        }

        $user = Auth::user();
        $plan = Plan::find($this->token);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addDays($plan->interval),
            'is_active' => false,
        ]);

        $data = [
            'totalAmount' => 1.00,
            'description' => $plan->name,
            'callbackUrl' => route('card.subscription.callback'),
            'returnUrl' => route('dashboard'),
            'merchantAccountNumber' => '2023574',
            'cancellationUrl' => route('plans'),
            'clientReference' => $subscription->id,
        ];

        $response = $this->initialize_hubtel_subscription($data);
        $data = $response->json();

        if ($response['status' !== 'Success']) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Subscription error',
                'description' => $data['data']['message'],
            ]);
            return redirect('/plans');
        }

        $this->redirect($data['data']['checkoutUrl']);
    }


    public function momoSubscription()
    {
        $user = Auth::user();
        $plan = Plan::find($this->token);

        $invoiceEndDate = match ($plan->interval) {
            '30' => Carbon::now()->addYears(5)->format('Y-m-d\TH:i:s'),
            '91' => Carbon::now()->addYears(5)->format('Y-m-d\TH:i:s'),
            '360' => Carbon::now()->addYears(5)->format('Y-m-d\TH:i:s'),
            default => Carbon::now()->addYears(5)->format('Y-m-d\TH:i:s')
        };

        $paymentInterval = match ($plan->interval) {
            '30' => 'MONTHLY',
            '81' => 'QUARTERLY',
            '360' => 'YEARLY',
            default => 'MONTHLY'
        };

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode('wmJBkgm:808a6b5717dd4c839ad73fa5cd6ce46c'),
            'Cache-Control' => 'no-cache'
        ];

        $data = [
            'orderDate' => Carbon::now()->addMinutes(5)->format('Y-m-d\TH:i:s'),
            'invoiceEndDate' => $invoiceEndDate,
            'description' => $plan->name,
            'startTime' => Carbon::now()->addMinutes(5)->toTimeString('minute'),
            'paymentInterval' => $paymentInterval,
            'customerMobileNumber' => $user->phone,
            'paymentOption' => 'MobileMoney',
            'Channel' => $user->channel,
            'recurringAmount' => $plan->amount,
            'totalAmount' => $plan->amount,
            'initialAmount' => $plan->amount,
            'currency' => 'GHS',
            'callbackUrl' => route('momo.subscription.callback')
        ];

        Log::alert($data);

        $invoice = Http::withHeaders($headers)->post('https://rip.hubtel.com/api/proxy/2023574/create-invoice', $data);

        $response = $invoice->json();

        Log::alert($response);

        if ($response['responseCode'] !== '0001') {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Failed to create Invoice!',
                'description' => 'Unable to create invoice.',
            ]);

            return \redirect('/plans');
        }

        $responseData = $response['data'];

        $recurringInvoiceId = $responseData['recurringInvoiceId'];
        $requestId = $responseData['requestId'];
        $otpPrefix = $responseData['otpPrefix'];

        $this->redirect("/verify-invoice?rIId=$recurringInvoiceId&rId=$requestId&optP=$otpPrefix", navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.subscribe');
    }
}
