<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Foundation\Auth\User;
use Filament\Models\Contracts\HasName;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends User implements HasName, FilamentUser
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFilamentName(): string
    {
        return $this->getAttributeValue('firstname');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@iyke63.com') && $this->hasVerifiedEmail();
    }
}
