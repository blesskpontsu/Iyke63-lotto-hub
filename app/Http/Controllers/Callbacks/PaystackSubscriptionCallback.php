<?php

namespace App\Http\Controllers\Callbacks;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Subscription;
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
            $data = $response->data;

            $user = User::query()
                ->where('email', $data->customer->email)
                ->first();

            if (!$user) {
                return redirect('/plans');
            }

            $lastSubscription = Subscription::query()
                ->where('user_id', $user->id)
                ->whereNotNull('reference')
                ->latest()
                ->first();

            if (!$lastSubscription) {
                return redirect('/plans');
            }

            $dataPlan = $data->plan ?? null;

            $plan = Plan::query()
                ->where('plan_code', $dataPlan)
                ->first();

            if (!$plan) {
                return redirect('/plans');
            }

            $jsonResponse = json_encode($data, JSON_PRETTY_PRINT);
            $transaction = new Transaction([
                'user_id' => $user->id,
                'transaction_id' => $data->id,
                'customer_id' => $data->customer->id,
                'amount' => $data->amount,
                'source' => 'Paystack',
                'type' => 'subscription',
                'status' => $data->status,
                'payload' => $jsonResponse,
            ]);
            $transaction->save();

            $start_date = Carbon::parse($data->created_at);
            $end_date = $start_date->copy()->addDays($plan->interval);

            if ($lastSubscription) {
                $lastSubscription->forceFill([
                    'is_active' => true,
                    'start_date' => $start_date->format('Y-m-d H:i:s'),
                    'end_date' => $end_date->format('Y-m-d H:i:s'),
                ])->save();
            }

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
