<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User;
use Filament\Models\Contracts\HasName;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends User implements HasName
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
}
