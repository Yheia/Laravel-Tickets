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
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('role')
                    ->searchable(),
                TextColumn::make('sector')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make('export')
                    ->label('Export Users')
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
                    ->label('Export Selected')
                    ->icon('heroicon-o-document-arrow-up')
                    ->exporter(ExportsUserExporter::class),

                ]),
                
            ]);
    }
}
