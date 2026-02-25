<?php

namespace App\Filament\Resources\Tickets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Exports\TicketExporter;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Ticket Creator')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('assignedSupport.name')
                    ->label('Assigned Support')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                ImageColumn::make('image'),
                TextColumn::make('status')
                    ->badge()->color(fn ($state) => match ($state) {
                        'open' => 'danger',
                        'in_progress' => 'warning',
                        'closed' => 'success',
                        default => null,
                    }),
                TextColumn::make('priority')
                    ->badge()->color(fn ($state) => match ($state) {
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                        default => null,
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                ->label('status') 
                ->options([
                    'open' => 'Open',
                    'in_progress' => 'In progress',
                    'closed' => 'Closed',
                ])->multiple(),

            // Priority Filter
            SelectFilter::make('priority')
                ->label('priority') 
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                ])->multiple(),
            ])
            ->headerActions([
               ExportAction::make('export')
                    ->label('Export Tickets')
                    ->icon('heroicon-o-document-arrow-up')
                    ->exporter(TicketExporter::class),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->visible(fn ($record) => auth()->user()->isSupervisor()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportAction::make('export')
                    ->label('Export Tickets')
                    ->icon('heroicon-o-document-arrow-up')
                    ->exporter(TicketExporter::class),
                ]),
            ]);
    }
}
