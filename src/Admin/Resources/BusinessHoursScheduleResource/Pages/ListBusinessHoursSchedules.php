<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursScheduleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursScheduleResource;

class ListBusinessHoursSchedules extends ListRecords
{
    protected static string $resource = BusinessHoursScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
