<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\BusinessHoursScheduleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\BusinessHoursScheduleResource;

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
