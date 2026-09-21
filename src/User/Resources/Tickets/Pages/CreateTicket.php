<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets\TicketResource;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(TicketService::class)->create($data, auth()->guard()->user());
    }

    protected function getRedirectUrl(): string
    {
        // Explicit rather than relying on Filament's default (which could
        // land on the 'index' listing -- unsupported under the API
        // transport, see TicketResource::getRelations()).
        return TicketResource::getUrl('view', ['record' => $this->getRecord()]);
    }
}
