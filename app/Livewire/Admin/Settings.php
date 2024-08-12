<?php

namespace App\Livewire\Admin;

use App\Livewire\Actions\LogoutAdmin;
use Livewire\Component;

class Settings extends Component
{
    public function logout(LogoutAdmin $logout): void
    {
        $logout();

        $this->redirect('/admin/login', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
