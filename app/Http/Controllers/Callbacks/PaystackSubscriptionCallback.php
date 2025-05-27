<?php

namespace App\Http\Controllers\Callbacks;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PaystackSubscriptionCallback extends Controller
{

    public function handle(Request $request)
    {
        $response = json_decode($this->verify_subscription($request->reference));

        // Ensure that the response is valid and the transaction was successful
        if ($response && isset($response->status) && $response->status === true) {
            return redirect('/dashboard');
        }

        // Handle failed transaction or unexpected response here
        return redirect('/subscription/failure');
    }


    private function verify_subscription($reference)
    {
        $http = Http::withHeaders([
            'authorization' => 'Bearer ' . config('services.paystack.live_key2'),
        ])->accept('application/json')
            ->get('https://api.paystack.co/transaction/verify/' . $reference);

        return $http;
    }
}
