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
                    ->label(__('Ticket Creator'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('assignedSupport.name')
                    ->label(__('Assigned Support'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
                ImageColumn::make('image')
                    ->label(__('Image')),
                TextColumn::make('sector')
                    ->label(__('Sector'))
                    ->badge()->color(fn ($state) => match ($state) {
                        'Network and Infrastructure' => 'primary',
                        'Portal and site' => 'info',
                        'Complain' => 'warning',
                        'general' => 'secondary',
                        default => 'null',
                    })
                    ->formatStateUsing(fn ($state) => __($state)),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()->color(fn ($state) => match ($state) {
                        'open' => 'danger',
                        'in_progress' => 'warning',
                        'closed' => 'success',
                        default => null,
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'open' => __('Open'),
                        'in_progress' => __('In Progress'),
                        'closed' => __('Closed'),
                        default => $state,
                    }),
                TextColumn::make('priority')
                    ->label(__('Priority'))
                    ->badge()->color(fn ($state) => match ($state) {
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                        default => null,
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'low' => __('Low'),
                        'medium' => __('Medium'),
                        'high' => __('High'),
                        default => $state,
                    }),
                TextColumn::make('faculty')
                    ->label(__('Faculty'))
                    ->searchable()
                    ->sortable()
                    ->default('Other')
                    ->formatStateUsing(fn ($state) => __($state)),
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
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'open'        => __('Open'),
                        'in_progress' => __('In Progress'),
                        'closed'      => __('Closed'),
                    ])->multiple(),

                SelectFilter::make('sector')
                    ->label(__('Sector'))
                    ->options([
                        'Network and Infrastructure' => __('Network and Infrastructure'),
                        'Portal and site'            => __('Portal and site'),
                        'Complain'                   => __('Complain'),
                        'general'                    => __('General'),
                    ])->multiple(),

                SelectFilter::make('priority')
                    ->label(__('Priority'))
                    ->options([
                        'low'    => __('Low'),
                        'medium' => __('Medium'),
                        'high'   => __('High'),
                    ])->multiple(),
            ])
            ->headerActions([
                ExportAction::make('export')
                    ->label(__('Export Tickets'))
                    ->icon('heroicon-o-document-arrow-up')
                    ->exporter(TicketExporter::class),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn ($record) => auth()->user()->isSupervisor() || auth()->user()->isSectoradmin()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportAction::make('export')
                        ->label(__('Export Tickets'))
                        ->icon('heroicon-o-document-arrow-up')
                        ->exporter(TicketExporter::class),
                ]),
            ]);
    }
}