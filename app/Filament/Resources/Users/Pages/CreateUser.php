<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
{
    $user = auth()->user();

    if ($user->isSectoradmin()) {
        // Enforce allowed roles
        abort_unless(
            in_array($data['role'], ['user', 'support', 'sectoradmin']),
            403
        );
        // Force sector regardless of submitted value
        $data['sector'] = $user->sector;
    }

    return $data;
}
}
