<?php

namespace App\Livewire\Auth;

use App\Models\EarlyUser;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use WireUi\Traits\WireUiActions;

class Register extends Component
{
    use WireUiActions;

    public string $firstname = '';
    public string $lastname = '';
    public string $phone = '';
    public string $email = '';
    public string $password = '';


    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'min:2'],
            'lastname' => ['required', 'string', 'min:2'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ]
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

        $this->password = Hash::make($this->password); //Hashing password

        $earlyUser = EarlyUser::query()->where('email', $this->email)->first();

        if (!$earlyUser) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Registration unsuccessful!',
                'description' => 'This registration is only for early users at the moment',
            ]);
            $this->dispatch('redirectAfterDelay');
            return;
        }

        //Creating a new user
        $user = new User($this->all());
        $user->save();

        //Email verification event
        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
