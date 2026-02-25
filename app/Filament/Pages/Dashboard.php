<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form; 
use Filament\Schemas\Components\Section;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;


        protected function getHeaderWidgets(): array
    {
        return [
            \Filament\Widgets\AccountWidget::class,
        
        ];
    }



    public function filtersForm( $form)    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('Start Date')
                            ->live()
                            ,
                        DatePicker::make('endDate')
                            ->label('End Date')
                            ->live()
                            ,
                    ])
                    
                    ->columnSpanFull()->columns(2),
            ]);
    }
}