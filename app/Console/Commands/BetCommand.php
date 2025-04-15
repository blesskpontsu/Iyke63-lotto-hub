<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\AccountTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bet-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Running bet process');

        $now = Carbon::now();

        // Determine the time slot
        $isMorning = $now->lt(Carbon::today()->addHours(10)); // Before 10:00 AM
        $isAfternoon = !$isMorning && $now->lt(Carbon::today()->addHours(14)); // Before 1:00 PM

        // Define bet configurations
        $bets = [
            'morning' => [
                'game' => 'anopa',
                'numbers' => [
                    '32-43-25' => '15',
                    '24-22-37' => '15',
                    '56-45-33' => '15',
                    '20-21-22' => '15',
                    '18-25-33' => '15',
                    '21-44-45' => '15',
                    '6-9-29' => '15',
                    '50-55-57' => '15',
                    '7-10-15' => '15',
                    '2-22-21-15-35' => '50',
                    '12-13-15-17-19' => '50',
                    '20-21-22-23-25' => '50',
                    '44-35-21-16' => '30',
                    '10-13-17-19' => '30',
                    '1-2-3-4-5-6-7-8-9-10' => '90',
                    '11-12-13-14-15-16-17-18-19-20' => '90',
                    '20-21-22-23-24-25-26-27-28-29' => '90',
                    '30-31-32-33-34-35-36-37-38-39' => '90',
                    '40-41-42-43-44-45-46-47-48-49' => '90',
                    '48-49-50-51-52-53-54-55-56-57' => '90',
                ],
            ],
            'afternoon' => [
                'game' => 'mid',
                'numbers' => [
                    '32-43-25' => '15',
                    '24-22-37' => '15',
                    '56-45-33' => '15',
                    '20-21-22' => '15',
                    '18-25-33' => '15',
                    '21-44-45' => '15',
                    '6-9-29' => '15',
                    '50-55-57' => '15',
                    '7-10-15' => '15',
                    '2-22-21-15-35' => '50',
                    '12-13-15-17-19' => '50',
                    '20-21-22-23-25' => '50',
                    '44-35-21-16' => '30',
                    '10-13-17-19' => '30',
                    '1-2-3-4-5-6-7-8-9-10' => '90',
                    '11-12-13-14-15-16-17-18-19-20' => '90',
                    '20-21-22-23-24-25-26-27-28-29' => '90',
                    '30-31-32-33-34-35-36-37-38-39' => '90',
                    '40-41-42-43-44-45-46-47-48-49' => '90',
                    '48-49-50-51-52-53-54-55-56-57' => '90',
                ],
            ],
            'evening' => [
                'game' => '657',
                'numbers' => [
                    '32-43-25' => '15',
                    '24-22-37' => '15',
                    '56-45-33' => '15',
                    '20-21-22' => '15',
                    '18-25-33' => '15',
                    '21-44-45' => '15',
                    '6-9-29' => '15',
                    '50-55-57' => '15',
                    '7-10-15' => '15',
                    '2-22-21-15-35' => '50',
                    '12-13-15-17-19' => '50',
                    '20-21-22-23-25' => '50',
                    '44-35-21-16' => '30',
                    '10-13-17-19' => '30',
                    '1-2-3-4-5-6-7-8-9-10' => '90',
                    '11-12-13-14-15-16-17-18-19-20' => '90',
                    '20-21-22-23-24-25-26-27-28-29' => '90',
                    '30-31-32-33-34-35-36-37-38-39' => '90',
                    '40-41-42-43-44-45-46-47-48-49' => '90',
                    '48-49-50-51-52-53-54-55-56-57' => '90',
                ],
            ],
        ];

        // Determine the current slot
        $currentSlot = $isMorning ? 'morning' : ($isAfternoon ? 'afternoon' : 'evening');

        // Fetch the bet data for the current slot
        $betData = $bets[$currentSlot];
        $game = $betData['game'];
        $numbers = $betData['numbers'];

        // Define deposit amount
        $depositAmounts = [
            'morning' => 890,
            'afternoon' => 890,
            'evening' => 890,
        ];

        $depositAmount = $depositAmounts[$currentSlot];

        // Update the account balance
        $account = Account::query()->find('9d42d593-4eae-4e91-b743-1b4608fbbb83');
        if ($account) {
            $account->update(['balance' => $depositAmount]);
            Log::info('Account', [$account->balance]);
        } else {
            Log::error('Account not found for deposit update');
            return;
        }

        foreach ($numbers as $selectedNumbers => $amount) {
            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'x-afriluck-key' => 'suRU/Cbv535hU9kStQyEvrrU0+2aEAhrymCw4UsPnYI='
                ])->post('https://app.afriluck.com/api/V1/place-bet', [
                    'msisdn' => '233533019255',
                    'total_amount' => $amount,
                    'bet_type_code' => 2,
                    'bet_type' => 'perm',
                    'game' => $game,
                    'selected_numbers' => $selectedNumbers,
                    'channel' => 'mtn',
                    'discounted_amount' => '',
                    'use_wallet' => true,
                    'medium' => 'ussd',
                ]);

                if ($response->successful()) {
                    Log::info("Bet placed successfully for numbers: $selectedNumbers", [
                        'response' => $response->json(),
                    ]);
                } else {
                    Log::error("Failed to place bet for numbers: $selectedNumbers", [
                        'status' => $response->status(),
                        'response' => $response->json(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error("Error placing bet for numbers: $selectedNumbers", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        AccountTransaction::query()->where('account_id', '9d42d593-4eae-4e91-b743-1b4608fbbb83')->delete();

        Log::info('Bet process completed');
    }
}
