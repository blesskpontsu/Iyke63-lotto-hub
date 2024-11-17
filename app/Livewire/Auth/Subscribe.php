<?php

namespace App\Livewire\Auth;

use Carbon\Carbon;
use App\Models\Plan;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Subscribe extends Component
{
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
            '91' => Carbon::now()->addMonths(3)->toDateTimeString(),
            '182' => Carbon::now()->addMonths(6)->toDateTimeString(),
            '360' => Carbon::now()->addMonths(12)->toDateTimeString(),
            default => Carbon::now()->addMonths(3)->toDateTimeString()
        };

        $paymentInterval = match ($plan->interval) {
            '91' => 'QUARTERLY',
            '182' => 'SEMIYEARLY',
            '360' => 'YEARLY',
            default => 'QUARTERLY'
        };

        $data = [
            'orderDate' => Carbon::now()->toDateTimeString(),
            'invoiceEndDate' => $invoiceEndDate,
            'description' => 'Subscription for Iyke63',
            'startTime' => '12:00',
            'paymentInterval' => $paymentInterval,
            'customerMobileNumber' => $user->phone,
            'paymentOption' => 'MobileMoney',
            'Channel' => 'mtn_gh_rec',
            'recurringAmount' => $plan->amount,
            'totalAmount' => $plan->amount,
            'initialAmount' => $plan->amount,
            'currency' => 'GHS',
            'callbackUrl' => 'https://webhook.site/5eb99221-8d97-45d5-b467-8328cb26dcb1'
        ];

        $invoice = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode('Mj42AwP:c957a4de1515499e95604d3a8f44190a'),
            'Cache-Control' => 'no-cache'
        ])->post('https://rip.hubtel.com/api/proxy/2023574/create-invoice', $data);


        Log::info($data);
        Log::info($invoice->body());
        Log::info($invoice->json());
    }

    public function render()
    {
        return view('livewire.auth.subscribe');
    }
}
