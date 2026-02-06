<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\EmailChannelResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\EmailChannelResource;

class ListEmailChannels extends ListRecords
{
    protected static string $resource = EmailChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
