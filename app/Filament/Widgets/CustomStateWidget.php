<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class CustomStateWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Total registered users')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Total Revenue', '$' . number_format(Invoice::sum('total_amount'), 2))
                ->description('Total revenue from invoices')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total Invoices', Invoice::count())
                ->description('Number of invoices issued')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning'),
        ];
    }
}
