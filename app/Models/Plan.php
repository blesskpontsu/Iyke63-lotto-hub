<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'interval',
        'plan_code'
    ];

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }
}
