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
                    ->label(__('Ticket Creator'))
                    ->default(auth()->user()->name)
                    ->disabled() 
                    ->dehydrated(false),
                Select::make('assigned_to')
                    ->label(__('Assigned To'))
                    ->relationship('assignedSupport', 'name')
                    ->visible(fn () => auth()->user()->isSupervisor() || auth()->user()->isSectoradmin()) 
                    ->searchable()
                    ->default(null),
                TextInput::make('title')
                    ->label(__('Title'))
                    ->required()
                    ->columnSpanFull()
                    ->rules(['min:20', 'max:255']),
                Textarea::make('description')
                    ->label(__('Description'))
                    ->required()
                    ->columnSpanFull()
                    ->rules(['min:75', 'max:1000']),
                FileUpload::make('image')
                    ->label(__('Image'))    
                    ->image()->imagePreviewHeight('200')
                    ->maxSize(4096)
                    ->downloadable()
                    ->openable(),
                Select::make('sector')
                    ->label(__('Sector'))
                    ->options([
                        'Network and Infrastructure' => __('Network and Infrastructure'),
                        'Portal and site'            => __('Portal and site'),
                        'Complain'                   => __('Complain'),
                        'general'                    => __('General'),
                    ])
                    ->default('general')
                    ->required(),
                Select::make('status')
                    ->label(__('Status'))   
                    ->options([
                        'open'        => __('Open'),
                        'in_progress' => __('In Progress'),
                        'closed'      => __('Closed'),
                    ])
                    ->default('open')
                    ->visible(fn () => auth()->user()->isSupport() || auth()->user()->isSupervisor() || auth()->user()->isSectoradmin())
                    ->required(),
                Select::make('faculty')
                    ->label(__('Faculty'))
                    ->options([
                        'Faculty of Education'                           => __('Faculty of Education'),
                        'Faculty of Agriculture'                         => __('Faculty of Agriculture'),
                        'Faculty of Arts'                                => __('Faculty of Arts'),
                        'Faculty of Commerce'                            => __('Faculty of Commerce'),
                        'Faculty of Nursing'                             => __('Faculty of Nursing'),
                        'Faculty of Science'                             => __('Faculty of Science'),
                        'Faculty of Pharmacy'                            => __('Faculty of Pharmacy'),
                        'Faculty of Veterinary Medicine'                 => __('Faculty of Veterinary Medicine'),
                        'Faculty of Early Childhood Education'           => __('Faculty of Early Childhood Education'),
                        'Faculty of Special Education'                   => __('Faculty of Special Education'),
                        'Faculty of Computers and Information'           => __('Faculty of Computers and Information'),
                        'Faculty of engineering'                         => __('Faculty of Engineering'),
                        'Faculty of Applied Arts'                        => __('Faculty of Applied Arts'),
                        'institute of graduate studies and environmental research' => __('Institute of Graduate Studies and Environmental Research'),
                        'Other'                                          => __('Other'),
                    ])
                    ->default('Other')
                    ->required()
                    ->searchable(),
                Select::make('priority')
                    ->label(__('Priority'))
                    ->options([
                        'low'    => __('Low'),
                        'medium' => __('Medium'),
                        'high'   => __('High'),
                    ])
                    ->default('medium')
                    ->visible(fn () => auth()->user()->isSupervisor() || auth()->user()->isSectoradmin())
                    ->required(),
            ]);
    }
}