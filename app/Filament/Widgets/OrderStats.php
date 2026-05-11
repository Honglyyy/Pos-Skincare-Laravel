<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', Order::where('status', 'new')->count())
                ->description('New orders awaiting processing')
                ->descriptionIcon(Heroicon::ReceiptRefund)
                ->color('primary'),
            Stat::make('Processing Orders', Order::where('status', 'processing')->count())
                ->description('Processing orders')
                ->descriptionIcon(Heroicon::Clock)
                ->color('warning'),
            Stat::make('Completed Orders', Order::where('status', 'completed')->count())
                ->description('Completed orders')
                ->descriptionIcon(Heroicon::CheckBadge)
                ->color('success'),
            Stat::make('Revenue', "$". number_format(Order::where('status', 'completed')->sum('total_payment'),0))
                ->description('Revenue')
                ->descriptionIcon(Heroicon::DocumentCurrencyDollar)
                ->color('primary')
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }
}
