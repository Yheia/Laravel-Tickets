<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {




         $user = auth()->user(); 

        
        $roleOptions = [
            'user' => 'User',
            'support' => 'Support',
            'supervisor' => 'Supervisor',
            'sectoradmin' => 'Sector Admin',
        ];

        if ($user->isSectoradmin()) {
            
            $roleOptions = [
                'user' => 'User',
                'support' => 'Support',
                'sectoradmin' => 'Sector Admin',
            ];
        }



        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('role')
                    ->options([
                        'user' => 'User',
                        'support' => 'Support',
                        'supervisor' => 'Supervisor',
                    ])

                    ->required(),
                Select::make('sector')
                     ->options([
                        'Network and Infrastructure' => 'Network and Infrastructure',
                        'Portal and site' => 'Portal and site',
                        'Complain' => 'Complain',
                        'general' => 'General',
                    ])
                    ->default(fn () => $user->isSectoradmin() ? $user->sector : null)
                    ->disabled(fn () => $user->isSectoradmin())
                    ->required()
                    ->dehydrated(),
   
            ]);
    }
}
