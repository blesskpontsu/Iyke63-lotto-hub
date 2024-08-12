<?php

namespace App\Livewire;

use App\Models\RequestBet as ModelsRequestBet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Validate;
use WireUi\Traits\WireUiActions;

class RequestBet extends Component
{
    use WireUiActions;

    public string $company = '';

    public string $game = '';

    public string $game_time = '';

    public string $game_type = '';

    public string $game_code = '';

    public string $selected_numbers = '';

    public int $amount = 1;

    public int $total_amount = 1;

    protected $rules = [
        'company' => 'required|string|max:150',
        'game' => 'required|string|max:150',
        'game_time' => 'nullable|string|max:150',
        'game_type' => 'required|string|max:150',
        'game_code' => 'required|string|max:150',
        'selected_numbers' => 'required|string|max:150',
        'amount' => 'required|int|max:150',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ($propertyName === 'company' || $propertyName === 'game_time') {
            $this->setGame();
        }

        if ($propertyName === 'selected_numbers') {
            $this->validateSelectedNumbers();
        }

        if ($propertyName === 'selected_numbers' || $propertyName === 'amount' || $propertyName === 'game_code' || $propertyName === 'game_type') {
            $this->calculatePermutations();
        }
    }

    public function setGame()
    {
        $dayOfWeek = Carbon::now()->format('l'); // Get the current day of the week (e.g., 'Monday', 'Tuesday')

        if ($this->company === 'National Lottery Authority') {
            if ($this->game_time === 'morning') {
                $games = [
                    'Monday' => 'VAGA MONDAY',
                    'Tuesday' => 'VAGA TUESDAY',
                    'Wednesday' => 'VAGA WEDNESDAY',
                    'Thursday' => 'VAGA THURSDAY',
                    'Friday' => 'VAGA FRIDAY',
                    'Saturday' => 'VAGA SATURDAY',
                    'Sunday' => 'VAGA SUNDAY'
                ];
            } elseif ($this->game_time === 'afternoon') {
                $games = [
                    'Monday' => 'NOON RUSH MONDAY',
                    'Tuesday' => 'NOON RUSH TUESDAY',
                    'Wednesday' => 'NOON RUSH WEDNESDAY',
                    'Thursday' => 'NOON RUSH THURSDAY',
                    'Friday' => 'NOON RUSH FRIDAY',
                    'Saturday' => 'NOON RUSH SATURDAY',
                    'Sunday' => 'NOON RUSH SUNDAY'
                ];
            } elseif ($this->game_time === 'evening') {
                $games = [
                    'Monday' => 'MONDAY SPECIAL',
                    'Tuesday' => 'LUCKY TUESDAY',
                    'Wednesday' => 'MIDWEEK',
                    'Thursday' => 'FORTUNE THURSDAY',
                    'Friday' => 'FRIDAY BONANZA',
                    'Saturday' => 'NATIONAL WEEKLY LOTTO',
                    'Sunday' => 'SUNDAY ASEDA'
                ];
            }
        } elseif ($this->company === 'Alpha Lotto') {
            $games = [
                'Monday' => 'ALPHA MONDAY',
                'Tuesday' => 'DELTA TUESDAY',
                'Wednesday' => 'OMEGA WEDNESDAY',
                'Thursday' => 'EXCEL THURSDAY',
                'Friday' => 'PRIME FRIDAY',
                'Saturday' => 'KENSTAR SATURDAY',
                'Sunday' => 'PRECISE SUNDAY'
            ];
        }

        $this->game = $games[$dayOfWeek] ?? $this->company;
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

    public function submit()
    {
        $this->validate();

        $bet = ModelsRequestBet::create($this->all());
        $bet->user_id = Auth::user()->id;

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Game Staked Successfully',
            'description' => 'You have successfully stake your game',
        ]);
    }

    public function render()
    {
        $companies = [
            ['name' => 'Afriluck NLA'],
            ['name' => 'Alpha Lotto'],
            ['name' => 'National Lottery Authority']
        ];

        $game_times = [
            ['name' => 'morning'],
            ['name' => 'afternoon'],
            ['name' => 'evening']
        ];

        $game_types = [];
        $codes = [];


        if ($this->company === 'Afriluck NLA') {
            $game_types = [
                ['name' => 'Mega Jackpot'],
                ['name' => 'Direct'],
                ['name' => 'Perm'],
                ['name' => 'Banker'],
            ];
        } else {
            $game_types = [
                ['name' => 'Direct'],
                ['name' => 'Perm'],
                ['name' => 'Banker'],
            ];
        }

        if ($this->game_type === 'Mega Jackpot') {
            $codes = [
                ['name' => 'Mega Jackpot 5GHC', 'code' => '5'],
                ['name' => 'Mega Jackpot 10GHC', 'code' => '10'],
                ['name' => 'Mega Jackpot 20GHC', 'code' => '20'],
            ];
        } elseif ($this->game_type === 'Direct') {
            $codes = [
                ['name' => 'DIRECT 2', 'code' => '2'],
                ['name' => 'DIRECT 3', 'code' => '3'],
            ];
        } elseif ($this->game_type === 'Perm') {
            $codes = [
                ['name' => 'PERM 2', 'code' => '2'],
                ['name' => 'PERM 3', 'code' => '3'],
            ];
        } elseif ($this->game_type === 'Banker') {
            $codes = [
                ['name' => 'Banker Against', 'code' => '2']
            ];
        }

        return view('livewire.request-bet', [
            'companies' => $companies,
            'game_times' => $game_times,
            'available_game' => $this->game,
            'game_types' => $game_types,
            'codes' => $codes
        ]);
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
}
