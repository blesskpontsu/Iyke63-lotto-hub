<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EarlyUser;
use Livewire\Attributes\On;
use Illuminate\Http\Request;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Http;
use App\Notifications\NewEarlyAccessUser;
use Illuminate\Validation\ValidationException;

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

    // protected function validateRecaptcha(string $token): void
    // {
    //     // validate Google reCaptcha.
    //     $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
    //         'secret' => config('services.grecaptcha.secret_key'),
    //         'response' => $token,
    //         // 'remoteip' => request()->ip(),
    //     ]);
    //     $throw = fn ($message) => throw ValidationException::withMessages(['recaptcha' => $message]);
    //     if (!$response->successful() || !$response->json('success')) {
    //         $throw($response->json(['error-codes'])[0] ?? 'An error occurred.');
    //     }
    //     // if response was score based (the higher the score, the more trustworthy the request)
    //     if ($response->json('score') < 0.6) {
    //         $throw('We were unable to verify that you\'re not a robot. Please try again.');
    //     }
    // }


    public function render()
    {
        return view('livewire.early-access');
    }
}
