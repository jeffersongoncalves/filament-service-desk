<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\TicketResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\TicketResource;
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

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        return app(TicketService::class)->update($record, $data, auth()->user());
    }
}
