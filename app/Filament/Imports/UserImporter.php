<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),

            ImportColumn::make('password')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('role')
                ->requiredMapping()
                ->rules(['required', Rule::in(['user', 'support', 'supervisor', 'sectoradmin'])]),

            ImportColumn::make('sector')
                ->rules(['nullable', 'string', 'max:255', 'required_unless:role,sectoradmin']),
        ];
    }

    public function resolveRecord(): User
    {
        return User::firstOrNew([
            'email' => $this->data['email'],
        ]);
    }

    protected function beforeValidate(): void
    {
        // $this->import->user is whoever triggered this import 
        $importingUser = $this->import->user;

        if ($importingUser->isSectoradmin()) {
            // force every row into the sectoradmin's own sector, regardless of what the CSV says
            $this->data['sector'] = $importingUser->sector;

            // sectoradmins can't hand out the supervisor role
            if (($this->data['role'] ?? null) === 'supervisor') {
                throw ValidationException::withMessages([
                    'role' => 'You are not allowed to assign the supervisor role.',
                ]);
            }
        } elseif (! $importingUser->isSupervisor()) {
            // anyone else shouldn't reach this at all
            throw ValidationException::withMessages([
                'email' => 'You are not authorized to import users.',
            ]);
        }


    }
    protected function beforeSave(): void
    {
        $this->record->password = Hash::make($this->data['password']);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}