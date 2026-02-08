<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannels\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannels\EmailChannelResource;

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
