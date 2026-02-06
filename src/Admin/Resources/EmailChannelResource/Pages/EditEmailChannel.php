<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannelResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannelResource;

class EditEmailChannel extends EditRecord
{
    protected static string $resource = EmailChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
