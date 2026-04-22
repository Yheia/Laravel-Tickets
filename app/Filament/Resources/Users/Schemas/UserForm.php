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
            'user'        => __('User'),
            'support'     => __('Support'),
            'supervisor'  => __('Supervisor'),
            'sectoradmin' => __('Sector Admin'),
        ];

        if ($user->isSectoradmin()) {
            $roleOptions = [
                'user'        => __('User'),
                'support'     => __('Support'),
                'sectoradmin' => __('Sector Admin'),
            ];
        }

        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('Email Address'))
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label(__('Password'))
                    ->password()
                    ->required(),
                Select::make('role')
                    ->label(__('Role'))
                    ->options($roleOptions)
                    ->required(),
                Select::make('sector')
                    ->label(__('Sector'))
                    ->options([
                        'Network and Infrastructure' => __('Network and Infrastructure'),
                        'Portal and site'            => __('Portal and site'),
                        'Complain'                   => __('Complain'),
                        'general'                    => __('General'),
                    ])
                    ->default(fn () => $user->isSectoradmin() ? $user->sector : null)
                    ->disabled(fn () => $user->isSectoradmin())
                    ->required()
                    ->dehydrated(),
            ]);
    }
}