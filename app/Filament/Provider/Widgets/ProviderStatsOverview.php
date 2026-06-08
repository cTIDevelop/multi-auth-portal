<?php

namespace App\Filament\Provider\Widgets;

use App\Models\Product;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProviderStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $providerId = auth('provider')->id();

        return [
            Stat::make('My Services', Service::where('provider_id', $providerId)->count())
                ->description(Service::where('provider_id', $providerId)->where('is_active', true)->count() . ' published')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('info'),

            Stat::make('My Products', Product::where('provider_id', $providerId)->count())
                ->description(Product::where('provider_id', $providerId)->where('is_active', true)->count() . ' published')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Low Stock', Product::where('provider_id', $providerId)->where('stock_quantity', '<=', 5)->count())
                ->description(Product::where('provider_id', $providerId)->where('stock_quantity', 0)->count() . ' out of stock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
