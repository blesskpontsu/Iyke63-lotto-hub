<?php

namespace App\Http\Controllers\Callbacks;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ProcessPaystackPayloads extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->json()->all();

        $data = $payload['data'];
        $successful = isset($data['status']) && $data['status'] === 'success';
        $eventType = $payload['event'];
        $metadata = $data['metadata'] ?? null; //Retrieve Metadata

        if (!$successful) {
            Log::error('Paystack Payload: Status is missing in payload');
            return response()->json(['error' => 'Status is missing in payload']);
        }

        $user = User::query()
            ->where('email', $data['customer']['email'])
            ->first();

        if (!$user) {
            Log::error('Paystack Payload: No user associated with customer email');
            return response()->json(['error' => 'No user associated with customer email']);
        }

        $lastSubscription = Subscription::query()
            ->where('user_id', $user->id)
            ->whereNotNull('reference')
            ->latest()
            ->first();

        $dataPlan = $data['plan'] ?? null; //Retrieve plan

        if ($dataPlan && array_key_exists('plan_code', $dataPlan)) {
            $plan = Plan::query()
                ->where('plan_code', $dataPlan['plan_code'])
                ->first();
        }

        if ($eventType == 'charge.success' && $metadata['type'] == 'subscription') {

            if (!$lastSubscription) {
                Log::error('Paystack Payload: User does not have a subscription');
                return response()->json(['error' => 'User does not have a subscription']);
            }

            if (!$plan) {
                Log::error('Paystack Payload: No plan associated with plan code');
                return response()->json(['error' => 'No plan associated with plan code']);
            }

            $transactionExist = Transaction::query()->where('transaction_id', $data['id'])->first();

            if (!$transactionExist) {
                $jsonResponse = json_encode($data, JSON_PRETTY_PRINT);
                $transaction = new Transaction([
                    'user_id' => $user->id,
                    'transaction_id' => $data['id'],
                    'customer_id' => $data['customer']['id'],
                    'amount' => $data['amount'],
                    'source' => 'Paystack',
                    'type' => 'subscription',
                    'status' => $data['status'],
                    'payload' => $jsonResponse,
                ]);
                $transaction->save();
            }

            $start_date = Carbon::parse($data['created_at']);
            $end_date = $start_date->copy()->addDays($plan->interval);

            if ($lastSubscription) {
                $lastSubscription->update([
                    'is_active' => true,
                    'start_date' => $start_date->format('Y-m-d H:i:s'),
                    'end_date' => $end_date->format('Y-m-d H:i:s'),
                ]);
            }

            Log::info('Success: Transaction processed successfully');
            return response()->json(['success' => 'Transaction processed successfully']);
        }

        if ($eventType == 'subscription.create') {
            if (!$lastSubscription) {
                Log::error('Paystack Payload: User does not have a subscription');
                return response()->json(['error' => 'User does not have a subscription']);
            }

            if (!$plan) {
                Log::error('Paystack Payload: No plan associated with plan code');
                return response()->json(['error' => 'No plan associated with plan code']);
            }

            $start_date = Carbon::parse($data['createdAt']);
            $end_date = Carbon::parse($data['next_payment_date']);

            $lastSubscription->update([
                'subscription_code' => $data['subscription_code'],
                'is_active' => true,
                'start_date' => $start_date->format('Y-m-d H:i:s'),
                'end_date' => $end_date->format('Y-m-d H:i:s'),
            ]);

            Log::info('Success: Subscription created successfully');
            return response()->json(['success' => 'Transaction processed successfully']);
        }


        if ($eventType == 'invoice.update') {
            if (!$lastSubscription) {
                Log::error('Paystack Payload: User does not have a subscription');
                return response()->json(['error' => 'User does not have a subscription']);
            }

            $start_date = Carbon::parse($data['period_start']);
            $end_date = Carbon::parse($data['period_end']);

            $lastSubscription->update([
                'is_active' => true,
                'start_date' => $start_date->format('Y-m-d H:i:s'),
                'end_date' => $end_date->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
