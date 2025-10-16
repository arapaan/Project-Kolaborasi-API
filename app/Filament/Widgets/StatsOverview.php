<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $starOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $ordersCount = Order::whereBetween('created_at', [$starOfWeek, $endOfWeek])->count();
        $totalSales = Order::whereBetween('created_at', [$starOfWeek, $endOfWeek])->sum('total_price');
        $totalCustomers = Order::whereBetween('created_at', [$starOfWeek, $endOfWeek])
            ->distinct('user_id')
            ->count('user_id');
        return [
            Stat::make('Total Orders (This week)', $ordersCount ?? '0')
                ->icon('heroicon-o-shopping-bag')
                ->color('info'),

            Stat::make('Total Sales (This Week)','Rp ' . number_format($totalSales, 0,  ',', '.') ?? '0')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total Customers (This Week)', $totalCustomers ?? '0')
                ->icon('heroicon-o-user-group')
                ->color('primary'),

            // Stat::make('Collectibles Count', '0')
            //     ->icon('heroicon-o-briefcase')
            //     ->color('gray'),

            // Stat::make('Event Capacities Count', '1/523')
            //     ->icon('heroicon-o-calendar')
            //     ->color('warning'),
        ];
    }
}
