<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use WireUi\Traits\WireUiActions;

class ForgotPassword extends Component
{
    use WireUiActions;

    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        $this->notification()->send([
            'icon' => 'success',
            'title' => "Information",
            'description' => "($status)",
        ]);
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
