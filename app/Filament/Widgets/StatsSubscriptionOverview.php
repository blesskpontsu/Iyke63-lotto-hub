<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsSubscriptionOverview extends BaseWidget
{
    public int|string $totalSubscriptions = 0;
    public int|string $activeSubscriptions = 0;
    public int $inactiveSubscriptions = 0;
    public int|string $newSubscriptions = 0;


    public function loadData(): void
    {
        $today = today()->toDateString();

        $data = Subscription::selectRaw("
        COUNT(*) as total_subscriptions,
        COUNT(CASE WHEN is_active = true THEN 1 END) as active_subscriptions,
        COUNT(CASE WHEN is_active = false THEN 1 END) as inactive_subscriptions,
        COUNT(CASE WHEN DATE(created_at) = ? THEN 1 END) as new_subscriptions
    ", [$today])->first();

        $this->totalSubscriptions = $data->total_subscriptions;
        $this->activeSubscriptions = $data->active_subscriptions;
        $this->inactiveSubscriptions = $data->inactive_subscriptions;
        $this->newSubscriptions = $data->new_subscriptions;
    }

    protected function getStats(): array
    {

        $this->loadData();
        return [
            Stat::make('Total Subscriptions', $this->totalSubscriptions)
                ->description('Total available subscriptions')
                ->descriptionIcon('heroicon-m-gift')
                ->color('success'),

            Stat::make('Total Active Subscriptions', $this->activeSubscriptions)
                ->description('Total subscriptions that are active')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Expired Subscriptions', $this->inactiveSubscriptions)
                ->description('Total subscriptions that are inactive or expired')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),

            Stat::make('New Subscriptions Today', $this->newSubscriptions)
                ->description('Subscriptions that have occured today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
