<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Imports\UserImporter;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\ImportAction;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getHeading(): string
    {
        return __('Users');
    }

    public function getSubheading(): ?string
    {
        return __('Manage The Users Here');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}