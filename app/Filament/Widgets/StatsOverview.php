<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Requests (Total)', '0 (0)')
                ->icon('heroicon-o-shield-check')
                ->color('danger'),

            Stat::make('Total Sales', '508 SAR')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Customers Count', '2')
                ->icon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Collectibles Count', '0')
                ->icon('heroicon-o-briefcase')
                ->color('gray'),

            Stat::make('Event Capacities Count', '1/523')
                ->icon('heroicon-o-calendar')
                ->color('warning'),
        ];
    }
}
