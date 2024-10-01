<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use App\Models\EarlyUser;
use Illuminate\Support\Str;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;

class Register extends Component
{
    use WireUiActions;

    public string $firstname = '';
    public string $lastname = '';
    public string $phone = '';
    public string $email = '';
    public string $password = '';
    public $countryCode = '+233';


    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'min:2'],
            'lastname' => ['required', 'string', 'min:2'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'unique:users,phone'],
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
        if ($prop === 'phone') {
            if (Str::startsWith($this->phone, '0')) {
                $this->phone = Str::replaceFirst('0', $this->countryCode, $this->phone);
            } else {
                $this->phone = $this->countryCode . $this->phone;
            }
        }
    }

    public function register(): void
    {
        $this->validate();

        $this->password = Hash::make($this->password); //Hashing password

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
