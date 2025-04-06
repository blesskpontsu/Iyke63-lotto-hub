<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsUsersOverview extends BaseWidget
{
    public int|string $totalUsers = 0;
    public int|string $totalVerifiedUsers = 0;
    public int $totalUnverifiedUsers = 0;
    public int $newUsers = 0;

    public function loadData(): void
    {
        $today = today()->toDateString();

        $data = User::selectRaw("
        COUNT(*) as total_users,
        COUNT(CASE WHEN email_verified_at IS NOT NULL THEN 1 END) as verified_users,
        COUNT(CASE WHEN email_verified_at IS NULL THEN 1 END) as unverified_users,
        COUNT(CASE WHEN DATE(created_at) = ? THEN 1 END) as new_users
    ", [$today])->first();

        $this->totalUsers = $data->total_users;
        $this->totalVerifiedUsers = $data->verified_users;
        $this->totalUnverifiedUsers = $data->unverified_users;
        $this->newUsers = $data->new_users;
    }

    protected function getStats(): array
    {
        $this->loadData();
        return [
            Stat::make('Total Users', $this->totalUsers)
                ->description('Total registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Verified Users', $this->totalVerifiedUsers)
                ->description('Total users that verified their emails')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Unverified Users', $this->totalUnverifiedUsers)
                ->description('Registered users who fail to verify their emails')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),

            Stat::make('New Users Today', $this->newUsers)
                ->description('New Users who have purchased tickets')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
