<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\TicketResource\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\TicketResource;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;
}
