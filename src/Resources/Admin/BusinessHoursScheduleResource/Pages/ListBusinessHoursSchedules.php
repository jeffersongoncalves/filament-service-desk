<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\BusinessHoursScheduleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\BusinessHoursScheduleResource;

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
