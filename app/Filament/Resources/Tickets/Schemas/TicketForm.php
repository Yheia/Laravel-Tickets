<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;


class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_name') 
                ->label('Ticket Creator')
                ->default(auth()->user()->name)
                ->disabled() 
                ->dehydrated(false),
                // TextInput::make('user_id')
                //     ->default(auth()->id())
                //     ->hidden()
                //     ->dehydrated(true),
                Select::make('assigned_to')
                    ->relationship('assignedSupport', 'name')
                    ->visible(fn () => auth()->user()->isSupervisor() || auth()->user()->isSectoradmin()) 
                    ->searchable()
                    ->default(null),
                TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->rules(['min:20', 'max:255']),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull()
                    ->rules(['min:75', 'max:1000']),
                FileUpload::make('image')
                    ->image()->imagePreviewHeight('200')
                    ->maxSize(4096) // 4MB
                    ->downloadable()
                    ->openable(),
                Select::make('sector')
                    ->options(['Network and Infrastructure' => 'Network and Infrastructure', 'Portal and site' => 'Portal and site', 'Complain' => 'Complain','general' => 'General'])
                    ->default('general')
                    ->required(),
                Select::make('status')
                    ->options(['open' => 'Open', 'in_progress' => 'In progress', 'closed' => 'Closed'])
                    ->default('open')
                    ->visible(fn () => auth()->user()->isSupport() || auth()->user()->isSupervisor() || auth()->user()->isSectoradmin())
                    ->required(),
                                    Select::make('faculty')
                    ->options([
                        'Faculty of Education',
                        'Faculty of Agriculture',
                        'Faculty of Arts',
                        'Faculty of Commerce',
                        'Faculty of Nursing',
                        'Faculty of Science',
                        'Faculty of Pharmacy',
                        'Faculty of Veterinary Medicine',
                        'Faculty of Early Childhood Education',
                        'Faculty of Special Education',
                        'Faculty of Computers and Information',
                        'Faculty of engineering',
                        'Faculty of Applied Arts',
                        'institute of graduate studies and environmental research',
                        'Other'
            ])
                    ->default('Other')
                    ->required()
                    ->searchable(),
                Select::make('priority')
                    ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'])
                    ->default('medium')
                    ->visible(fn () => auth()->user()->isSupervisor() || auth()->user()->isSectoradmin())
                    ->required(),
            ]);
    }
}
