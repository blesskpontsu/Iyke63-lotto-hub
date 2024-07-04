<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EarlyUser;
use App\Notifications\NewEarlyAccessUser;
use WireUi\Traits\WireUiActions;

class EarlyAccess extends Component
{
    use WireUiActions;

    public string $firstname;

    public string $lastname;

    public string $phone;

    public string $email;

    public string $country;

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'min:2'],
            'lastname' => ['required', 'string', 'min:2'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . EarlyUser::class],
            'phone' => ['required', 'string', 'max:255', 'unique:' . EarlyUser::class],
            'country' => ['required', 'string', 'min:2'],
        ];
    }

    //Realtime validation
    public function updated($prop): void
    {
        $this->validateOnly($prop);
    }

    public function register(): void
    {
        $this->validate();

        $user = EarlyUser::create([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->country
        ]);

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Registration successful!',
            'description' => 'You have registered for the early access',
        ]);

        $user->notify(new NewEarlyAccessUser($user));

        $this->reset();
    }

    public function render()
    {
        return view('livewire.early-access');
    }
}
