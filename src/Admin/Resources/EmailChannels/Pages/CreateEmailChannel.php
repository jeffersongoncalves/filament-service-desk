<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannels\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannels\EmailChannelResource;

class CreateEmailChannel extends CreateRecord
{
    protected static string $resource = EmailChannelResource::class;
}
