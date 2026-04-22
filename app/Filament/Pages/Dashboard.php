<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;
    public static function getNavigationLabel(): string
    {
    return __('Dashboard');
    }

     public function getHeading(): string
    {
        return __('Damanhour Ticketing System');
    }
    protected function getHeaderWidgets(): array
    {
        return [
            \Filament\Widgets\AccountWidget::class,
        ];
    }

    public function filtersForm($form)
    {
        $user = auth()->user();

        if (!$user || $user->isUser()) {
            return $form->schema([]);
        }

        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        DatePicker::make('startDate')
                            ->label(__('Start Date'))
                            ->live(),
                        DatePicker::make('endDate')
                            ->label(__('End Date'))
                            ->live(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }
}