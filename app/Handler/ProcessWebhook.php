<?php

namespace App\Handler;

use App\Models\Plan;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

//The class extends "ProcessWebhookJob" class as that is the class //that will handle the job of processing our webhook before we have //access to it.
class ProcessWebhook extends ProcessWebhookJob
{
    public function handle()
    {
        Log::info('This is running');
        $data = json_decode($this->webhookCall, true);

        $payload = $data['payload']['data'];

        $user = User::query()->where('email', $payload['customer']['email'])->first();

        $lastSubscription = Subscription::query()->where('user_id', $user->id)->first();

        //Every charge event
        if ($data['payload']['event'] == 'charge.success') {
            if ($user) {
                $jsonResponse = json_encode($payload, JSON_PRETTY_PRINT);
                $transaction = new Transaction([
                    'user_id' => $user->id,
                    'transaction_id' => $payload['id'],
                    'customer_id' => $payload['customer']['id'],
                    'amount' => $payload['amount'],
                    'status' => $payload['status'],
                    'payload' => $jsonResponse,
                ]);
                $transaction->save();
            }

            $start_date = Carbon::parse($payload['created_at']);
            $end_date = $start_date->copy()->addDays(30);
            $plan = Plan::query()->where('name', $payload['plan']['name'])->first();
            if (!$lastSubscription) {
                $subscription = new Subscription();
                $subscription->plan_id = $plan->id;
                $subscription->is_active = true;
                $subscription->start_date = $start_date->format('Y-m-d H:i:s');
                $subscription->end_date = $end_date->format('Y-m-d H:i:s');
                $subscription->user_id = $user->id;
                $subscription->subscription_code = $payload['subscription_code'];
                $subscription->save();
            } else {
                $lastSubscription->forceFill([
                    'is_active' => true,
                    'start_date' => $start_date->format('Y-m-d H:i:s'),
                    'end_date' => $end_date->format('Y-m-d H:i:s'),
                ])->save();
            }
        }

        //Event for new subscription created
        if ($data['payload']['event'] == 'subscription.create') {
            $start_date = Carbon::parse($payload['createdAt']);
            $end_date = Carbon::parse($payload['next_payment_date']);

            if ($user) {
                $subscription = new Subscription();
                $subscription->plan_id = $plan->id;
                $subscription->is_active = true;
                $subscription->start_date = $start_date->format('Y-m-d H:i:s');
                $subscription->end_date = $end_date->format('Y-m-d H:i:s');
                $subscription->user_id = $user->id;
                $subscription->subscription_code = $payload['subscription_code'];
                $subscription->save();
            }
        }

        // //Invoice created event for subscription renewal
        // if ($data['payload']['event'] == 'invoice.create') {
        //     $start_date = Carbon::parse($payload['period_start']);
        //     $end_date = Carbon::parse($payload['period_end']);
        //     $lastSubscription->forceFill([
        //         'is_active' => true,
        //         'start_date' => $start_date->format('Y-m-d H:i:s'),
        //         'end_date' => $end_date->format('Y-m-d H:i:s'),
        //     ])->save();
        // }


        if ($data['payload']['event'] == 'invoice.update') {
            $start_date = Carbon::parse($payload['period_start']);
            $end_date = Carbon::parse($payload['period_end']);
            if ($payload['status'] == 'success' && $payload['paid'] == true) {
                $lastSubscription->forceFill([
                    'is_active' => true,
                    'start_date' => $start_date->format('Y-m-d H:i:s'),
                    'end_date' => $end_date->format('Y-m-d H:i:s'),
                ])->save();
            }
        }

        logger($data['payload']);
        http_response_code(200); //Acknowledge you received the response
    }
}
