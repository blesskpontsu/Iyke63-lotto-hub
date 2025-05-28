<?php

namespace App\Http\Controllers\Callbacks;

use App\Models\User;
use App\Models\RequestBet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ProcessPaystack2Payloads extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->json()->all();
        Log::info('response', ['payload' => $payload]);
        $data = $payload['data'];
        $successful = isset($data['status']) && $data['status'] === 'success';
        $eventType = $payload['event'];
        $metadata = $data['metadata'] ?? null; //Retrieve Metadata

        if (!$successful) {
            Log::error('Paystack Payload: Payment is not successful');
            return response()->json(['error' => 'Payment is not successful']);
        }

        $user = User::query()
            ->where('email', $data['customer']['email'])
            ->first();

        Log::info($data['customer']['email']);

        if (!$user) {
            Log::error('Paystack Payload: No user associated with customer email');
            return response()->json(['error' => 'No user associated with customer email']);
        }

        if ($eventType == 'charge.success' && $metadata['type'] == 'bet_request') {

            $transactionExist = Transaction::query()->where('transaction_id', $data['id'])->first();

            if (!$transactionExist) {
                $jsonResponse = json_encode($data, JSON_PRETTY_PRINT);
                $transaction = new Transaction([
                    'user_id' => $user->id,
                    'transaction_id' => $data['id'],
                    'customer_id' => $data['customer']['id'],
                    'amount' => $data['amount'],
                    'source' => 'Paystack',
                    'type' => 'request-bet',
                    'status' => $data['status'],
                    'payload' => $jsonResponse,
                ]);
                $transaction->save();
            }

            $betRequest = RequestBet::find($data['reference']);

            if (!$betRequest) {
                Log::error('Paystack Payload: No Bet request associated with reference: ' . $data['reference']);
                return response()->json(['error' => 'No Bet request associated with reference']);
            }

            $betRequest->update([
                'status' => 'paid'
            ]);

            Log::info('Paystack Payload: No user associated with customer email');
            return response()->json(['error' => 'No user associated with customer email']);
        }
    }
}
