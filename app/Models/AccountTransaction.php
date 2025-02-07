<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountTransaction extends Model
{
    use HasFactory, HasUuids;

    protected $connection = 'secondary_mysql';

    protected $fillable = [
        'account_id',
        'amount',
        'type',
        'status',
        'balance',
        'bonus'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
