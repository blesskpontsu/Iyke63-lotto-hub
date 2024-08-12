<?php

namespace App\Traits;

trait MustVerifySubscription
{
    //Check if authenticated user's organization has subscription created.
    public function hasSubscription(): bool
    {
        $subscription = $this->subscription() ?? false;
        return $subscription ? true : false;
    }

    //Check if authenticated user's organization has an active subscription
    public function hasActiveSubscription(): bool
    {
        $subscription = $this->subscription ?? false;
        return $subscription ? $subscription->is_active : false;
    }

    //Mark subscription as active
    public function markIsActiveTrue(): bool
    {
        $subscription = $this->subscription;

        if ($subscription) {
            // Update the 'is_active' attribute on the associated Subscription model
            return $subscription->forceFill([
                'is_active' => true,
            ])->save();
        }
    }
}
