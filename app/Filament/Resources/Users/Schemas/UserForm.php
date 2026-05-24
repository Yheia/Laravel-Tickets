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
    $operation = $schema->getOperation(); // 'create' or 'edit'
    $record = $schema->getRecord();       // null on create

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

    // Sectoradmin editing their own record: lock the role field entirely
    $roleIsLocked = $user->isSectoradmin()
        && $operation === 'edit'
        && $record?->id === $user->id;

    return $schema->components([
        TextInput::make('name')
            ->label(__('Name'))
            ->required(),

        TextInput::make('email')
            ->label(__('Email Address'))
            ->email()
            ->unique(ignoreRecord: true)   //  won't fail on own email
            ->required(),

        TextInput::make('password')
            ->label(__('Password'))
            ->password()
            ->required(fn () => $operation === 'create')  //  optional on edit
            ->nullable(),

        TextInput::make('password_confirmation')
            ->label(__('Confirm Password'))
            ->password()
            ->same('password')
            ->required(fn () => $operation === 'create'),

        Select::make('role')
            ->label(__('Role'))
            ->options($roleOptions)
            ->disabled($roleIsLocked)      // own role is locked for sectoradmin
            ->dehydrated(! $roleIsLocked)  //  don't submit it at all if locked
            ->required(),

        // Replace disabled()->dehydrated() with hidden() + Hidden field
        Select::make('sector')
            ->label(__('Sector'))
            ->options([
                'Network and Infrastructure' => __('Network and Infrastructure'),
                'Portal and site'            => __('Portal and site'),
                'Complain'                   => __('Complain'),
                'general'                    => __('General'),
            ])
            ->visible(! $user->isSectoradmin())  // sectoradmin never sees it
            ->required(! $user->isSectoradmin()),

        \Filament\Forms\Components\Hidden::make('sector')
            ->default($user->isSectoradmin() ? $user->sector : null)
            ->visible($user->isSectoradmin()),   // silently carries the value
    ]);
}
}