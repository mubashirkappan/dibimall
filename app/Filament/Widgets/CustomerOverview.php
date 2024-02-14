<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CustomerOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $totalCustomers = Customer::query()->count();

        $label = $totalCustomers === 1 ? 'Customer' : 'Customers';
        $chartData = Customer::query()
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('count')
        ->toArray();
        return [
            Card::make($label, $totalCustomers) 
                ->icon('heroicon-s-users')
                ->color('success')
                ->chart($chartData)
        ];
    }
}