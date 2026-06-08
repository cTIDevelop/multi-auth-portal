<?php

namespace App\Filament\Admin\Pages;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Service;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Dashboard extends BaseDashboard
{
    //protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Admin Dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Admin\Widgets\StatsOverview::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 1;
    }
}
