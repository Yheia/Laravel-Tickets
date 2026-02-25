<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;
    protected ?string $heading = 'Tickets';
    protected ?string $subheading = 'Manage The Support Tickets Here';
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
