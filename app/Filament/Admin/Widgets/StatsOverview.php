<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Category;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Providers', Provider::count())
                ->description(Provider::where('is_active', true)->count() . ' active')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5]),

            Stat::make('Verified Providers', Provider::where('is_verified', true)->count())
                ->description('Pending: ' . Provider::where('is_verified', false)->count())
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info'),

            Stat::make('Total Services', Service::count())
                ->description(Service::where('is_active', true)->count() . ' published')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('warning'),

            Stat::make('Total Products', Product::count())
                ->description(Product::where('is_active', true)->count() . ' published')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            Stat::make('Categories', Category::count())
                ->description(Category::whereNull('parent_id')->count() . ' top-level')
                ->descriptionIcon('heroicon-m-tag')
                ->color('gray'),

            Stat::make('Low Stock Products', Product::where('stock_quantity', '<=', 5)->where('stock_quantity', '>', 0)->count())
                ->description(Product::where('stock_quantity', 0)->count() . ' out of stock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
