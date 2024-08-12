<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forcast extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['company', 'game', 'draw_time', 'image'];
}
