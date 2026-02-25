<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationItem;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function boot()
{
    FilamentView::registerRenderHook(
        'panels::global-search.before',
        fn (): string => Blade::render('<a href="https://www.ekb.eg/ar/home" class="text-sm font-medium">Egyptian Knowledge bank</a>'),
    );
     FilamentView::registerRenderHook(
        'panels::global-search.before',
        fn (): string => Blade::render('<a href="https://dmu.edu.eg/units/unit/c7dd384c-b689-4268-8663-ed7fcbdd24d2" class="text-sm font-medium">University Hospital</a>'),
    );
    FilamentView::registerRenderHook(
        'panels::global-search.before',
        fn (): string => Blade::render('<a href="https://dmu.edu.eg/units/unit/7613a5d1-76ef-4424-9305-389da70c7cb9" class="text-sm font-medium">Software Unit</a>'),
    );
}

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            // ->topNavigation()
            ->databaseNotifications()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(asset('imgs/dlogo.png'))
            ->brandLogoHeight('3.5rem')
            ->sidebarCollapsibleOnDesktop()
            ->unsavedChangesAlerts()
            ->navigationItems([
        NavigationItem::make('Damanhour University')
            ->url('https://dmu.edu.eg/landing', shouldOpenInNewTab: true)
            ->icon('heroicon-o-academic-cap')
            ->group('Important Links')
            ->sort(1),
        NavigationItem::make('Software Unit')
            ->url('https://dmu.edu.eg/units/unit/7613a5d1-76ef-4424-9305-389da70c7cb9', shouldOpenInNewTab: true)
            ->icon('heroicon-o-computer-desktop')
            ->group('Important Links')
            ->sort(2),
        NavigationItem::make('Contact Support')
            ->url('https://www.linkedin.com/in/yaheia-ibrahim-7y77/', shouldOpenInNewTab: true)
            ->icon('heroicon-o-phone')
            ->group('Important Links')
            ->sort(3),
    ])
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                // Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                
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
