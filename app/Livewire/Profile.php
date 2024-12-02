<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    use WireUiActions;

    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public string $phone = '';
    public ?string $channel = '';


    public function mount(): void
    {
        $user = Auth::guard('web')->user();
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->channel = $user->channel;
    }

    public function rules(): array
    {
        $user_id = Auth::id();
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user_id)],
            'phone' => ['required', 'string', 'max:12', Rule::unique(User::class)->ignore($user_id)],
            'channel' => ['required', 'string']
        ];
    }

    //Realtime validation
    public function updated($prop): void
    {
        $this->validateOnly($prop);
    }

    public function updateInformation(Request $request): void
    {
        $this->validate();
        $user = $request->user();
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->channel = $this->channel;
        $user->save();

        $this->notification()->send([
            'title'       => 'User Info Successfully Updated',
            'description' => 'Your Information was successfully updated',
            'icon'        => 'success'
        ]);
    }


    public function render()
    {
        return view('livewire.profile');
    }
}
