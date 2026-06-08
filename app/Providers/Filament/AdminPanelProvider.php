<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Dashboard;
use App\Filament\Admin\Resources\AdminResource;
use App\Filament\Admin\Resources\CategoryResource;
use App\Filament\Admin\Resources\ProductResource;
use App\Filament\Admin\Resources\ProviderResource;
use App\Filament\Admin\Resources\RoleResource;
use App\Filament\Admin\Resources\ServiceResource;
use App\Models\Admin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path(config('app.admin_panel_path', 'admin'))
            ->login()
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Slate,
            ])
            ->brandName('Admin Portal')
            ->brandLogo(null)
            ->favicon(null)
            //->authModel(Admin::class)
            ->authGuard('admin')
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([])
            ->navigationGroups([
                NavigationGroup::make()->label('User Management')->icon('heroicon-o-users'),
                NavigationGroup::make()->label('Provider Management')->icon('heroicon-o-building-office-2'),
                NavigationGroup::make()->label('Catalog')->icon('heroicon-o-squares-2x2'),
                NavigationGroup::make()->label('Settings')->icon('heroicon-o-cog-6-tooth'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
