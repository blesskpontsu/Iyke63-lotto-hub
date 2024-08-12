<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Contracts\View\View;
use App\Models\Result as ModelsResult;
use WireUi\Traits\WireUiActions;

class Result extends Component
{
    use WireUiActions;

    #[Validate('required|min:3')]
    public string $company = '';
    #[Validate('required|min:3')]
    public string $game = '';
    #[Validate('required|min:3')]
    public string $date = '';
    #[Validate('required|min:3')]
    public string $numbers = '';

    public function store(): void
    {
        ModelsResult::create($this->all());

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Results Created Successfully',
            'description' => 'You have created new result',
        ]);

        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.admin.result');
    }
}
