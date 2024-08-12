<?php

namespace App\Livewire\Auth;

use App\Models\Plan;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Plans extends Component
{
    public function initialize_subscription($user_data)
    {
        $http = Http::withHeaders([
            'authorization' => 'Bearer ' . config('services.paystack.live_key'),
        ])->accept('application/json')
            ->post('https://api.paystack.co/transaction/initialize', $user_data);

        return $http;
    }

    public function subscribe($plan_id)
    {
        // Check internet connection
        if (!$this->isInternetConnected()) {
            return response()->json(['error' => 'No internet connection']);
        }

        $user = auth()->user();
        $plan = Plan::find($plan_id);

        $user_data = [
            'email' => $user->email,
            'amount' => $plan->price * 100,
            'plan' => $plan->plan_code,
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
        Log::info('callback started');

        $data = $this->verify_subscription($request->reference);

        $response = $data->json();

        Log::info($response);

        // Ensure that the response is valid and the transaction was successful
        if ($response && isset($response['status']) && $response['status'] === true) {
            return redirect('/dashboard');
        }

        // Handle failed transaction or unexpected response here
        $this->redirect('/subscription/failure');
    }


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


    public function render()
    {
        return view('livewire.auth.plans');
    }
}
