<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\CannedResponses\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\CannedResponses\CannedResponseResource;

class ListCannedResponses extends ListRecords
{
    protected static string $resource = CannedResponseResource::class;
}
