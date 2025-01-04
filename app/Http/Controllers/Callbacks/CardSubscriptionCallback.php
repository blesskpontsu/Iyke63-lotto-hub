<?php

namespace App\Http\Controllers\Callbacks;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Plan;

class CardSubscriptionCallback extends Controller
{
    public function handle(Request $request)
    {
        $response = $request->json()->all();
        Log::info('data', $response);

        // Validate required response fields
        $successful = $response['ResponseCode'] === '0000';
        $data = $response['Data'] ?? [];
        $clientReference = $data['ClientReference'] ?? null;

        if (!$clientReference) {
            Log::error("Card Subscription Callback: Client Reference missing in the response.");
            return response()->json(['error' => "Client Reference missing in the response."], 400);
        }

        $subscription = Subscription::query()->where('reference', $clientReference);

        if (!$subscription) {
            Log::error("Card Subscription Callback: Subscription not found. Reference: {$clientReference}");
            return response()->json(['error' => 'Subscription not found']);
        }

        $user = User::query()->find($subscription->user_id);

        if (!$user) {
            Log::error("Card Subscription Callback: User not found. User_id: {$clientReference}");
            return response()->json(['error' => 'User not found']);
        }

        $plan = Plan::query()->find($subscription->plan_id);

        if (!$plan) {
            Log::error("Card Subscription Callback: Plan not found. Plan_id: {$subscription->plan_id}");
            return response()->json(['error' => 'Plan not found']);
        }

        // Encode response payload for storage
        $jsonResponse = json_encode($data, JSON_PRETTY_PRINT);

        if (!$successful) {
            // Handle transaction
            $transactionData = [
                'user_id' => $user->id,
                'transaction_id' => $data['CheckoutId'] ?? null,
                'amount' => $data['Amount'] ?? 0,
                'status' => 'failed',
                'source' => 'Hubtel',
                'type' => 'Subscription',
                'payload' => $jsonResponse,
            ];

            Transaction::create($transactionData);

            Log::error("Card Subscription Callback: Subscription Failed. Transaction Id: {$transactionData['transaction_id']}");
            return response()->json(['error' => 'Subscription Failed']);
        }

        $transactionData = [
            'user_id' => $user->id,
            'transaction_id' => $data['CheckoutId'] ?? null,
            'amount' => $data['Amount'] ?? 0,
            'status' => 'success',
            'source' => 'Hubtel',
            'type' => 'Subscription',
            'payload' => $jsonResponse,
        ];

        Transaction::create($transactionData);

        $subscription->update([
            'start_date' => now(),
            'end_date' => now()->addDays($plan->interval),
            'is_active' => true,
        ]);

        Log::info("Card Subscription Callback: Subscription Successful. Transaction Id: {$data['CheckoutId']}");
        return response()->json(['message' => 'Subscription Successful']);
    }
}
