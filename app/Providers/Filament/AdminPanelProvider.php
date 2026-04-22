<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationItem;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Filament\Facades\Filament; 

class AdminPanelProvider extends PanelProvider
{
    public function boot()
    {
      FilamentView::registerRenderHook(
    'panels::global-search.before',
    fn (): HtmlString => new HtmlString('
        <div style="display: flex; align-items: center; gap: 2rem; padding: 0 1rem;">
            <a href="https://www.ekb.eg/ar/home" 
               style="font-size: 0.875rem; font-weight: 500; text-decoration: none;"
               onmouseover="this.style.opacity=\'0.7\'" 
               onmouseout="this.style.opacity=\'1\'"
            >'.__('Egyptian Knowledge Bank').'</a>
            <a href="https://dmu.edu.eg/units/unit/c7dd384c-b689-4268-8663-ed7fcbdd24d2" 
               style="font-size: 0.875rem; font-weight: 500; text-decoration: none;"
               onmouseover="this.style.opacity=\'0.7\'" 
               onmouseout="this.style.opacity=\'1\'"
            >'.__('University Hospital').'</a>
            <a href="https://dmu.edu.eg/units/unit/7613a5d1-76ef-4424-9305-389da70c7cb9" 
               style="font-size: 0.875rem; font-weight: 500; text-decoration: none;"
               onmouseover="this.style.opacity=\'0.7\'" 
               onmouseout="this.style.opacity=\'1\'"
            >'.__('Software Unit').'</a>
        </div>
    '),
);
        FilamentView::registerRenderHook(
            'panels::user-menu.before',
            function (): HtmlString {
                $locales = config('app.supported_locales');
                $current = app()->getLocale();
                $html = '<div class="flex items-center gap-1 px-2">';
                foreach ($locales as $locale) {
                    $isActive = $current === $locale;
                    $label = $locale === 'en' ? 'EN' : '/AR';
                    $url = route('lang.switch', $locale);
                    $class = $isActive
                        ? 'text-sm font-semibold px-2 py-1 rounded bg-primary-500 text-white'
                        : 'text-sm font-semibold px-2 py-1 rounded text-gray-500 hover:text-gray-700';
                    $html .= "<a href=\"{$url}\" class=\"{$class}\">{$label}</a>";
                }
                $html .= '</div>';
                return new HtmlString($html);
            }
        );
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn (): HtmlString => new HtmlString(
                app()->getLocale() === 'ar'
                    ? '<style>:root { direction: rtl; }</style>'
                    : ''
            ),
        );

       Filament::serving(function () {
    Filament::registerNavigationItems([
        NavigationItem::make(__('Damanhour University'))
            ->url('https://dmu.edu.eg/landing', shouldOpenInNewTab: true)
            ->icon('heroicon-o-academic-cap')
            ->group(__('Important Links'))
            ->sort(1),
        NavigationItem::make(__('Software Unit'))
            ->url('https://dmu.edu.eg/units/unit/7613a5d1-76ef-4424-9305-389da70c7cb9', shouldOpenInNewTab: true)
            ->icon('heroicon-o-computer-desktop')
            ->group(__('Important Links'))
            ->sort(2),
        NavigationItem::make(__('Contact Support'))
            ->url('https://www.linkedin.com/in/yaheia-ibrahim-7y77/', shouldOpenInNewTab: true)
            ->icon('heroicon-o-phone')
            ->group(__('Important Links'))
            ->sort(3),
    ]);
});
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->databaseNotifications()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(asset('imgs/dlogo.png'))
            ->brandLogoHeight('3.5rem')
            ->sidebarCollapsibleOnDesktop()
            ->unsavedChangesAlerts()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                \App\Http\Middleware\SetLocale::class,
                DispatchServingFilamentEvent::class,
                
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}