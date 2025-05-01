<?php

namespace App\Http\Controllers\Callbacks;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class MomoSubscriptionCallback extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $response = $request->json()->all();

        // Validate required response fields
        $successful = $response['ResponseCode'] === '0000';
        $message = $response['Message'];
        $data = $response['Data'] ?? [];
        $description = $data['Description'] ?? null;
        $phoneNumber = $data['CustomerMobileNumber'] ?? null;

        if (!$description || !$phoneNumber) {
            $missingField = !$description ? 'Description' : 'Phone Number';
            Log::error("Momo Subscription Callback: {$missingField} missing in the response.");
            return response()->json(['error' => "{$missingField} missing in the response."], 400);
        }

        // Retrieve plan and user
        $plan = Plan::query()->where('name', $description)->first();
        $user = User::query()->where('phone', $phoneNumber)->first();

        if (!$plan) {
            Log::error("Momo Subscription Callback: Plan not found. Description: {$description}");
            return response()->json(['error' => 'Plan not found']);
        }

        if (!$user) {
            Log::error("Momo Subscription Callback: User not found. Phone Number: {$phoneNumber}");
            return response()->json(['error' => 'User not found'], 404);
        }

        // Encode response payload for storage
        $jsonResponse = json_encode($data, JSON_PRETTY_PRINT);

        $status = match ($message) {
            'Success' => "success",
            'Failed' => "failed",
            'The Repeat Payment Invoice has been Deactivated Successfully' => 'cancel',
            default => "failed"
        };

        // Handle transaction
        $transactionData = [
            'user_id' => $user->id,
            'transaction_id' => $data['TransactionId'] ?? 'NoID',
            'recurring_invoice_id' => $data['RecurringInvoiceId'] ?? 'NoID',
            'amount' => $data['Amount'] ?? 0,
            'status' => $status,
            'source' => 'Hubtel',
            'type' => 'Subscription',
            'payload' => $jsonResponse,
        ];

        Transaction::create($transactionData);

        if (!$successful) {
            Log::error("Momo Subscription Callback: Subscription Failed. Transaction Id: {$transactionData['transaction_id']}");
            return response()->json(['error' => 'Subscription Failed']);
        }

        // Handle subscription
        $lastSubscription = Subscription::query()->where('user_id', $user->id)->first();
        $startDate = now()->toDateTimeString();
        $endDate = now()->addDays($plan->interval)->toDateTimeString();


        $subscriptionData = [
            'plan_id' => $plan->id,
            'start_date' => $startDate,
            'end_date' => now()->endOfMonth()->toDateTimeString(),
            'is_active' => true,
        ];

        if ($lastSubscription) {
            $lastSubscription->update($subscriptionData);
        } else {
            Subscription::create(array_merge(['user_id' => $user->id], $subscriptionData));
        }

        Log::info("Momo Subscription Callback: Subscription Successful. Transaction Id: {$transactionData['transaction_id']}");
        return response()->json(['message' => 'Subscription Successful']);
    }
}
