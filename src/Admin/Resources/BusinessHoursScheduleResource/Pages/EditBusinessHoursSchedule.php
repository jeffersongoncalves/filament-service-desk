<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursScheduleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursScheduleResource;

class EditBusinessHoursSchedule extends EditRecord
{
    protected static string $resource = BusinessHoursScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
