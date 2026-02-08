<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursSchedules\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursSchedules\BusinessHoursScheduleResource;

class CreateBusinessHoursSchedule extends CreateRecord
{
    protected static string $resource = BusinessHoursScheduleResource::class;
}
