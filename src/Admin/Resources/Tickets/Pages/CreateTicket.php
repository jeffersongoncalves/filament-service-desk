<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tickets\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tickets\TicketResource;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(TicketService::class)->create($data, auth()->user());
    }
}
