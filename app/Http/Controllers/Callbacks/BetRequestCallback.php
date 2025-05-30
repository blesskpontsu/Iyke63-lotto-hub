<?php

namespace App\Http\Controllers\Callbacks;

use Exception;
use App\Models\User;
use App\Models\RequestBet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class BetRequestCallback extends Controller
{
    public function handle(Request $request)
    {
        $request->validate([
            'reference' => 'required|string|max:100'
        ]);

        $reference = $request->reference;

        $response = $this->verify_payment($reference);

        if (!$response) {
            return redirect('/request-bet')->with('error', 'Something went wrong during payment verification');
        }

        $data = $response->json('data');

        if (!$data || !isset($data['status'])) {
            return redirect('/request-bet')->with('error', 'Unexpected response from payment gateway.');
        }

        if ($data['status'] !== 'success') {
            return redirect('/request-bet')->with('error', 'Payment was not successful.');
        }

        $user = User::query()
            ->where('email', $data['customer']['email'])
            ->first();

        if (!$user) {
            return redirect('/dashboard')->with('error', 'Unable to verify payment: User not found');
        }


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

        $betRequest = RequestBet::find($data['reference']);

        if (!$betRequest) {
            Log::error('Paystack Payload: No Bet request associated with reference: ' . $data['reference']);
            return redirect('/dashboard')->with('error', 'Unable to find your bet. Contact admin with reference: ' . $data['reference']);
        }

        $bet = $this->updateBet($betRequest);

        if (!$bet) {
            return redirect('/dashboard')->with('error', 'Unable to update bet. Contact admin!');
        }


        return redirect('/dashboard')->with('success', 'Bet Requested successfully');
    }


    private function updateBet($bet)
    {
        try {
            $bet->update([
                'status' => 'paid'
            ]);
            Log::info($bet);
        } catch (Exception $e) {
            Log::error('Error while updating bet', [
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'message' => $e->getMessage()
            ]);
            return null;
        }
        return $bet;
    }


    public function verify_payment($reference): ?Response
    {
        try {
            $key = config('services.paystack.live_key');
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Bearer $key",
                'Cache-Control' => 'no-cache'
            ])->get("https://api.paystack.co/transaction/verify/{$reference}");
            Log::info('verify payment', ['payload' => $response]);
            return $response;
        } catch (Exception $e) {
            Log::error('Error while verifying payment for bet', [
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }
}
