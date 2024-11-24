<?php

namespace App\Livewire\Auth;

use Carbon\Carbon;
use App\Models\Plan;
use Livewire\Component;
use Illuminate\Http\Request;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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

    public function initialize_subscription($user_data)
    {
        $http = Http::withHeaders([
            'authorization' => 'Bearer ' . config('services.paystack.live_key'),
        ])->accept('application/json')
            ->post('https://api.paystack.co/transaction/initialize', $user_data);

        return $http;
    }

    public function verify_subscription($reference)
    {
        $http = Http::withHeaders([
            'authorization' => 'Bearer ' . config('services.paystack.live_key'),
        ])->accept('application/json')
            ->get('https://api.paystack.co/transaction/verify/' . $reference);

        return $http;
    }

    public function subscription_callback(Request $request)
    {
        $data = $this->verify_subscription($request->reference);

        $response = $data->json();

        // Ensure that the response is valid and the transaction was successful
        if ($response && isset($response['status']) && $response['status'] === true) {
            return redirect('/dashboard');
        }

        // Handle failed transaction or unexpected response here
        $this->redirect('/subscription/failure');
    }


    public function cardSubscription()
    {
        // Check internet connection
        if (!$this->isInternetConnected()) {
            return response()->json(['error' => 'No internet connection']);
        }

        $user = Auth::user();
        $plan = Plan::find($this->token);

        $metadata = [
            'type' => 'subscription',
            'cancel_action' => route('plans')
        ];

        $user_data = [
            'email' => $user->email,
            'amount' => $plan->price * 100,
            'plan' => $plan->plan_code,
            'metadata' => $metadata,
            'callback_url' => route('subscription.callback'),
        ];

        $subscribe = $this->initialize_subscription($user_data);

        if ($subscribe->json('status') == false) {
            // session()->flash('subscription-error', $subscribe->json('message'));

            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Subscription error',
                'description' => $subscribe->json('message'),
            ]);
            return;
        }

        $this->redirect($subscribe->json('data')['authorization_url']);
    }

    public function momoSubscription()
    {
        $user = Auth::user();
        $plan = Plan::find($this->token);

        $invoiceEndDate = match ($plan->interval) {
            '91' => Carbon::now()->addYears(5)->format('Y-m-d\TH:i:s'),
            '182' => Carbon::now()->addYears(5)->format('Y-m-d\TH:i:s'),
            '360' => Carbon::now()->addYears(5)->format('Y-m-d\TH:i:s'),
            default => Carbon::now()->addYears(5)->format('Y-m-d\TH:i:s')
        };

        $paymentInterval = match ($plan->interval) {
            '91' => 'QUARTERLY',
            '182' => 'SEMIYEARLY',
            '360' => 'YEARLY',
            default => 'QUARTERLY'
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
            'description' => 'Subscription for Iyke63',
            'startTime' => '15:07',
            'paymentInterval' => $paymentInterval,
            'customerMobileNumber' => '23324174768',
            'paymentOption' => 'MobileMoney',
            'Channel' => 'mtn_gh_rec',
            'recurringAmount' => 5.00,
            'totalAmount' => 5.00,
            'initialAmount' => 5.00,
            'currency' => 'GHS',
            'callbackUrl' => route('momo.subscription.callback')
        ];

        $invoice = Http::withHeaders($headers)->post('https://rip.hubtel.com/api/proxy/2023574/create-invoice', $data);

        $response = $invoice->json();

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
