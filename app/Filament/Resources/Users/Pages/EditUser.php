<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeUpdate(array $data): array
{
    $user  = auth()->user();
    $record = $this->getRecord();

    if ($user->isSectoradmin()) {
        // Cannot edit a supervisor at all (policy covers this, belt-and-suspenders)
        abort_if($record->isSupervisor(), 403);

        // Cannot change their own role
        if ($record->id === $user->id) {
            $data['role'] = $user->role;  // Silently keep existing role
        } else {
            abort_unless(
                in_array($data['role'], ['user', 'support', 'sectoradmin']),
                403
            );
        }

        // Always force sector
        $data['sector'] = $user->sector;
    }

    return $data;
}
}
