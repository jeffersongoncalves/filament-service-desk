<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets\TicketResource;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        return app(TicketService::class)->create($data, auth()->guard()->user());
    }
}
