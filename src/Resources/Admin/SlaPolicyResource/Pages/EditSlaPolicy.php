<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\SlaPolicyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\SlaPolicyResource;

class EditSlaPolicy extends EditRecord
{
    protected static string $resource = SlaPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
