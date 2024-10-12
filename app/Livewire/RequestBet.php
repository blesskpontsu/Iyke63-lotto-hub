<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\RequestBet as ModelsRequestBet;
use Illuminate\Http\Client\Response as ClientResponse;

class RequestBet extends Component
{
    use WireUiActions;

    public string $company = '';

    public string $game = '';

    public string $game_time = '';

    public string $game_type = '';

    public string $game_code = '';

    public string $selected_numbers = '';

    public ?int $amount = null;

    public ?int $total_amount = null;

    protected $rules = [
        'company' => 'required|string|max:150',
        'game' => 'required|string|max:150',
        'game_time' => 'nullable|string|max:150',
        'game_type' => 'required|string|max:150',
        'game_code' => 'required|string|max:150',
        'selected_numbers' => 'required|string|max:150',
        'amount' => 'required|integer|min:1',
    ];

    public function updated($propertyName)
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
            $this->addError('selected_numbers', 'Mega Jackpot requires exactly 6 numbers.');
        } elseif ($this->game_type === 'Direct' && $this->game_code === '2' && $count !== 2) {
            $this->addError('selected_numbers', 'Direct 2 requires exactly 2 numbers.');
        } elseif ($this->game_type === 'Direct' && $this->game_code === '3' && $count !== 3) {
            $this->addError('selected_numbers', 'Direct 3 requires exactly 3 numbers.');
        } elseif ($this->game_type === 'Perm' && $this->game_code === '2' && ($count < 3 || $count > 15)) {
            $this->addError('selected_numbers', 'Perm 2 requires between 3 and 15 numbers.');
        } elseif ($this->game_type === 'Perm' && $this->game_code === '3' && ($count < 4 || $count > 10)) {
            $this->addError('selected_numbers', 'Perm 3 requires between 4 and 10 numbers.');
        } elseif ($this->game_type === 'Banker' && $this->game_code === '2' && $count !== 1) {
            $this->addError('selected_numbers', 'Banker requires exactly 1 number.');
        }
    }

    public function callback(Request $request)
    {
        $response = $this->verify_payment($request->reference);

        $jsonResponse = $response->json('data');

        $successful = $jsonResponse['status'] == 'success';

        if (!$successful) {
            return redirect('/request-bet');
        }

        return redirect('/dashboard');
    }

    public function submit()
    {
        if ($this->company == 'National Lottery Authority' || $this->company == 'Afriluck NLA') {
            $this->rules = [
                'company' => 'required|string|max:150',
                'game' => 'required|string|max:150',
                'game_time' => 'required|string|max:150',
                'game_type' => 'required|string|max:150',
                'game_code' => 'required|string|max:150',
                'selected_numbers' => 'required|string|max:150',
                'amount' => 'required|int|max:150',
            ];
        }

        $this->validateSelectedNumbers();

        $this->validate();

        $bet = new ModelsRequestBet($this->all());
        $bet->user_id = Auth::user()->id;
        $bet->status = 'pending';
        $bet->save();

        $discount = $this->total_amount * 0.15;

        $amount = $this->total_amount - $discount;

        $metadata = [
            'type' => 'bet_request',
            'cancel_action' => route('plans')
        ];

        $formData = [
            'email' => Auth::user()->email,
            'amount' => $amount * 100,
            'reference' => $bet->id,
            'metadata' => $metadata,
            'callback_url' => route('request.bet.callback'),
        ];

        $response = $this->initiate_payment($formData);

        if ($response->json('status') == false) {

            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Request not successful',
                'description' => $response->json('message'),
            ]);
        } else {
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Request Succesful',
                'description' => 'Proceed to make payment',
            ]);
            $this->redirect($response->json('data')['authorization_url']);
        };
    }

    public function render()
    {

        return view('livewire.request-bet');
    }

    public function calculatePermutations()
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

    public function initiate_payment($formData): ClientResponse
    {
        $key = config('services.paystack.live_key');
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $key",
            'Cache-Control' => 'no-cache'
        ])->post('https://api.paystack.co/transaction/initialize', $formData);

        return $response;
    }

    public function verify_payment($reference)
    {
        $key = config('services.paystack.live_key');
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $key",
            'Cache-Control' => 'no-cache'
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        return $response;
    }
}
