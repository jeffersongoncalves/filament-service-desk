<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        return app(TicketService::class)->create($data, auth()->user());
    }
}
