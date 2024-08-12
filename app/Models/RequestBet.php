<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestBet extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'company',
        'game',
        'user_id',
        'game_type',
        'game_time',
        'game_code',
        'selected_numbers',
        'amount',
        'total_amount'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
