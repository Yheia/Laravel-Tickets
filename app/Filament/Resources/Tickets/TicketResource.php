<?php

namespace App\Filament\Resources\Tickets;

use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Tickets\Pages\CreateTicket;
use App\Filament\Resources\Tickets\Pages\EditTicket;
use App\Filament\Resources\Tickets\Pages\ListTickets;
use App\Filament\Resources\Tickets\Schemas\TicketForm;
use App\Filament\Resources\Tickets\Tables\TicketsTable;
use App\Models\Ticket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\User;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;
    protected static ?string $recordTitleAttribute = 'priority';

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-ticket";

    public static function form(Schema $schema): Schema
    {
        return TicketForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TicketsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
    $query = parent::getEloquentQuery();
    $user = auth()->user();

   
    if ($user->isSupervisor()) {
        return $query;
    }

    if ($user->isSectoradmin()) {
        return $query->where('sector', $user->sector || 'sector' , 'general');
    }
   
    if ($user->isSupport()) {
       return $query->where('assigned_to', $user->id);
    }

   
    return $query->where('user_id', $user->id);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTickets::route('/'),
            'create' => CreateTicket::route('/create'),
            'edit' => EditTicket::route('/{record}/edit'),
        ];
    }
}
