<?php

namespace App\Models;

use App\Models\AccountTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory, HasUuids;

    protected $connection = 'secondary_mysql';

    protected $fillable = [
        'user_id',
        'balance',
        'currency',
        'pin',
        'bonus',
        'bonus_expires_at'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(AccountTransaction::class);
    }
}
