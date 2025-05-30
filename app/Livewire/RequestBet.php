<?php

namespace App\Livewire;

use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\RequestBet as ModelsRequestBet;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportRedirects\Redirector;

class RequestBet extends Component
{
    use WireUiActions;

    public string $company = '';

    public string $game = '';

    public string $game_time = '';

    public string $game_type = '';

    public string $game_code = '';

    public string $selected_numbers = '';

    public int|string|null $amount = null;

    public int|string|null $total_amount = null;

    protected $rules = [
        'company' => 'required|string|max:150',
        'game' => 'required|string|max:150',
        'game_time' => 'nullable|string|max:150',
        'game_type' => 'required|string|max:150',
        'game_code' => 'required|string|max:150',
        'selected_numbers' => 'required|string|max:150',
        'amount' => 'required|integer|min:1',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);

        if ($propertyName === 'selected_numbers') {
            $this->validateSelectedNumbers();
        }
    }

    public function validateSelectedNumbers()
    {
        $numbers = explode('-', $this->selected_numbers);
        $count = count($numbers);

        if ($this->game_type === 'Mega Jackpot' && $count !== 6) {
            return $this->addError('selected_numbers', 'Mega Jackpot requires exactly 6 numbers.');
        }

        if ($this->game_type === 'Direct') {
            if ($this->game_code === '2' && $count !== 2) {
                return $this->addError('selected_numbers', 'Direct 2 requires exactly 2 numbers.');
            }

            if ($this->game_code === '3' && $count !== 3) {
                return $this->addError('selected_numbers', 'Direct 3 requires exactly 3 numbers.');
            }
        }

        if ($this->game_type === 'Perm') {
            if ($this->game_code === '2' && ($count < 3 || $count > 15)) {
                return $this->addError('selected_numbers', 'Perm 2 requires between 3 and 15 numbers.');
            }

            if ($this->game_code === '3' && ($count < 4 || $count > 10)) {
                return $this->addError('selected_numbers', 'Perm 3 requires between 4 and 10 numbers.');
            }
        }

        if ($this->game_type === 'Banker' && $this->game_code === '2' && $count !== 1) {
            return $this->addError('selected_numbers', 'Banker requires exactly 1 number.');
        }
    }

    public function submit(): RedirectResponse|Redirector
    {

        $this->validateSelectedNumbers();

        $this->validate();

        $bet = $this->saveBet();

        if (!$bet) {
            return redirect('/request-bet')->with('error', 'Could not save bet. Please try again.');
        }

        $discount = $this->total_amount * 0.15;

        $amount = $this->total_amount - $discount;

        $metadata = [
            'type' => 'bet_request',
            'cancel_action' => route('request.bet')
        ];

        $formData = [
            'email' => Auth::user()->email,
            'amount' => $amount * 100,
            'reference' => $bet->id,
            'metadata' => $metadata,
            'callback_url' => route('request.bet.callback'),
        ];

        $response = $this->initiate_payment($formData);

        $jsonResponse = $response->json();

        if (!$response || !isset($jsonResponse['status']) || $jsonResponse['status'] == false) {
            return redirect('/request-bet')->with('error', 'Payment initalization failed.');
        }

        return redirect()->away($jsonResponse['data']['authorization_url']);
    }

    public function render(): View
    {
        return view('livewire.request-bet');
    }

    public function calculatePermutations(): void
    {
        $amount = $this->amount;
        $selected_numbers_string = $this->selected_numbers;
        $bet_type_code = (int) $this->game_code; // Ensure this is an integer
        $bet_type = $this->game_type;

        $selected_numbers_string = preg_replace('/[,*\-\.\/]/', ' ', $selected_numbers_string);

        $selected_numbers_string = preg_replace('/\s+/', ' ', $selected_numbers_string);

        $selected_numbers_string = trim($selected_numbers_string);

        // Convert selected numbers string to array
        $selected_numbers = array_map('intval', explode(' ', $selected_numbers_string));

        // Add debugging to see the content of selected_numbers
        if (empty($selected_numbers)) {
            $this->total_amount = 0;
            return;
        }

        if ($bet_type === 'Perm') {
            // Generate permutations
            $combinations = $this->generateCombinations($selected_numbers, $bet_type_code);

            // Calculate total
            $total_combinations = count($combinations);
            $total = $amount * $total_combinations;

            $this->total_amount = $total;
        } elseif ($bet_type === 'Banker') {
            // Generate banker combinations
            $combinations = $this->generateBankerCombinations($selected_numbers);

            // Calculate total
            $total_combinations = count($combinations);
            $total = $amount * $total_combinations;

            $this->total_amount = $total;
        } else {
            $this->total_amount = $amount;
        }
    }


    private function generateCombinations($array, $r): array
    {
        $results = [];
        $this->combine($array, $results, [], 0, count($array) - 1, 0, $r);
        return $results;
    }

    private function combine($arr, &$results, $data, $start, $end, $index, $r): void
    {
        if ($index == $r) {
            $results[] = $data;
            return;
        }

        for ($i = $start; $i <= $end && $end - $i + 1 >= $r - $index; $i++) {
            $data[$index] = $arr[$i];
            $this->combine($arr, $results, $data, $i + 1, $end, $index + 1, $r);
        }
    }

    private function generateBankerCombinations(array $selected_numbers): array
    {
        $limit = 0;
        if ($this->company === 'Afriluck NLA') {
            $limit = 57;
        } else {
            $limit = 90;
        }
        $results = [];
        foreach ($selected_numbers as $selected_number) {
            for ($i = 1; $i <= $limit; $i++) {
                if (!in_array($i, $selected_numbers)) {
                    $results[] = [$selected_number, $i];
                }
            }
        }
        return $results;
    }

    public function initiate_payment($formData): ?ClientResponse
    {
        try {
            $key = config('services.paystack.live_key');
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Bearer $key",
                'Cache-Control' => 'no-cache'
            ])->post('https://api.paystack.co/transaction/initialize', $formData);
            Log::info('initialize payment', ['payload' => $response]);
            return $response;
        } catch (Exception $e) {
            Log::error('Error while initializing payment for bet', [
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }


    private function saveBet()
    {
        $bet = null;
        try {
            $bet = ModelsRequestBet::create([
                'user_id' => Auth::id(),
                'company' => $this->company,
                'game' => $this->game,
                'game_time' => $this->game_time,
                'game_type' => $this->game_type,
                'game_code' => $this->game_code,
                'selected_numbers' => $this->selected_numbers,
                'amount' => $this->amount,
                'total_amount' => $this->total_amount,
                'status' => 'pending',
            ]);
            Log::info('Bet Created', [$bet]);
        } catch (Exception $e) {
            Log::error('Error while saving bet', [
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'message' => $e->getMessage()
            ]);
        }

        return $bet;
    }
}
