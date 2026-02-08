<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\CannedResponses\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\CannedResponses\CannedResponseResource;

class CreateCannedResponse extends CreateRecord
{
    protected static string $resource = CannedResponseResource::class;
}
