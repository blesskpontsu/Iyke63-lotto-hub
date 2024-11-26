<?php

namespace App\Handler;

use App\Models\Plan;
use App\Models\RequestBet;
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
        $payload = json_decode($this->webhookCall, true); //Incomming payload

        $data = $payload['payload']['data']; //Retrieve data from payload

        $eventType = $payload['payload']['event']; //Retrive event

        $metadata = $data['metadata'] ?? null; //Retrieve Metadata

        $user = User::query()->where('email', $data['customer']['email'])->first(); //Retrive User

        $lastSubscription = Subscription::query()->where('user_id', $user->id)->first(); //Retrive previous subscription

        $dataPlan = $data['plan'] ?? null; //Retrieve plan

        //Check if plan exists and query plan
        // Check if $dataPlan exists and contains 'plan_code'
        if ($dataPlan && array_key_exists('plan_code', $dataPlan)) {
            $plan = Plan::query()->where('plan_code', $dataPlan['plan_code'])->first();
        }

        //Every charge event
        if ($eventType == 'charge.success') {
            if ($user) {
                $jsonResponse = json_encode($data, JSON_PRETTY_PRINT);
                $transaction = new Transaction([
                    'user_id' => $user->id,
                    'transaction_id' => $data['id'],
                    'customer_id' => $data['customer']['id'],
                    'amount' => $data['amount'],
                    'source' => 'Paystack',
                    'status' => $data['status'],
                    'payload' => $jsonResponse,
                ]);
                $transaction->save();


                //Create or update subscription
                if ($metadata) {
                    if ($metadata['type'] == 'subscription') {
                        $start_date = Carbon::parse($data['created_at']);
                        $end_date = $start_date->copy()->addDays(30);

                        if ($lastSubscription) {
                            $lastSubscription->forceFill([
                                'is_active' => true,
                                'start_date' => $start_date->format('Y-m-d H:i:s'),
                                'end_date' => $end_date->format('Y-m-d H:i:s'),
                            ])->save();
                        }
                    }

                    if ($metadata['type'] == 'bet_request') {
                        $betRequest = RequestBet::find($data['reference']);

                        Log::info($betRequest);

                        $betRequest->update([
                            'status' => 'paid'
                        ]);
                    }
                }
            }
        }

        //Event for new subscription created
        if ($eventType == 'subscription.create') {
            $start_date = Carbon::parse($data['createdAt']);
            $end_date = Carbon::parse($data['next_payment_date']);

            if ($user) {
                $subscription = new Subscription();
                $subscription->plan_id = $plan->id;
                $subscription->is_active = true;
                $subscription->start_date = $start_date->format('Y-m-d H:i:s');
                $subscription->end_date = $end_date->format('Y-m-d H:i:s');
                $subscription->user_id = $user->id;
                $subscription->subscription_code = $data['subscription_code'];
                $subscription->save();
            }
        }


        if ($eventType == 'invoice.update') {
            $start_date = Carbon::parse($data['period_start']);
            $end_date = Carbon::parse($data['period_end']);
            if ($data['status'] == 'success' && $data['paid'] == true) {
                $lastSubscription->forceFill([
                    'is_active' => true,
                    'start_date' => $start_date->format('Y-m-d H:i:s'),
                    'end_date' => $end_date->format('Y-m-d H:i:s'),
                ])->save();
            }
        }
        logger($payload);
        http_response_code(200); //Acknowledge you received the response
    }
}
