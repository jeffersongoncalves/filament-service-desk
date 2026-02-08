<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\SlaPolicies\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\SlaPolicies\SlaPolicyResource;

class CreateSlaPolicy extends CreateRecord
{
    protected static string $resource = SlaPolicyResource::class;
}
