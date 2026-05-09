<?php

namespace App\Filament\Widgets;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\VolunteerRegistration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalDonations = Donation::where('status', 'completed')->sum('amount');
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $totalVolunteers = VolunteerRegistration::count();

        return [
            Stat::make('Total Donations', '$' . number_format($totalDonations, 2))
                ->description('Successfully processed funds')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Active Campaigns', $activeCampaigns)
                ->description('Currently running campaigns')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('primary'),

            Stat::make('Total Volunteers', $totalVolunteers)
                ->description('Registered across all tasks')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
