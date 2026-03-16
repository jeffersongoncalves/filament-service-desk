<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tickets\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tickets\TicketResource;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(TicketService::class)->update($record, $data, auth()->guard()->user());
    }
}
