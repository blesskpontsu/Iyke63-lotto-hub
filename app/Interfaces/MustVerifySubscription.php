<?php

namespace App\Interfaces;


interface MustVerifySubscription
{
    public function hasActiveSubscription(): bool;

    public function hasSubscription(): bool;

    public function markIsActiveTrue(): bool;
}
