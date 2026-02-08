<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\Tickets\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\Tickets\TicketResource;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;
}
