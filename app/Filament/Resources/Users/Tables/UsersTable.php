<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Exports\UserExporter as ExportsUserExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('Email Address'))
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label(__('Email Verified At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('role')
                    ->label(__('Role'))
                    ->searchable()
                    ->formatStateUsing(fn ($state) => __($state)),
                TextColumn::make('sector')
                    ->label(__('Sector'))
                    ->searchable()
                    ->formatStateUsing(fn ($state) => __($state)),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make('export')
                    ->label(__('Export Users'))
                    ->icon('heroicon-o-document-arrow-up')
                    ->exporter(ExportsUserExporter::class),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make('export')
                        ->label(__('Export Selected'))
                        ->icon('heroicon-o-document-arrow-up')
                        ->exporter(ExportsUserExporter::class),
                ]),
            ]);
    }
}