<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Textarea::make('comment')
                ->label(__('Comment'))
                ->required()
                ->columnSpanFull()
                ->rules(['max:1000']),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(__('comment'))
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('By')),
                TextColumn::make('comment')
                    ->label(__('Comment'))
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label(__('At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('edited_at')
                    ->label(__('Edited'))
                    ->dateTime()
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                 ->label(__('Add Comment'))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    }),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->visible(fn ($record) => auth()->id() === $record->user_id),
                EditAction::make()
                    ->visible(fn ($record) => auth()->id() === $record->user_id),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canViewForRecord(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): bool
    {
        return auth()->user()->isSupport()
            || auth()->user()->isSupervisor()
            || auth()->user()->isSectoradmin();
    }
    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
{
    return __('Comments');
}
}