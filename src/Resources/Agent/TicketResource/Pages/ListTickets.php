<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Agent\TicketResource\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Agent\TicketResource;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;
}
