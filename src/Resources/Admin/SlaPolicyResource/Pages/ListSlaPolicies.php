<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\SlaPolicyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\SlaPolicyResource;

class ListSlaPolicies extends ListRecords
{
    protected static string $resource = SlaPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
