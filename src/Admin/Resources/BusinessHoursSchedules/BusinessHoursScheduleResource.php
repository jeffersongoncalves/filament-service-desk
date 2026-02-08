<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursSchedules;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursSchedules\Schemas\BusinessHoursScheduleForm;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursSchedules\Tables\BusinessHoursSchedulesTable;
use JeffersonGoncalves\ServiceDesk\Models\BusinessHoursSchedule;

class BusinessHoursScheduleResource extends Resource
{
    protected static ?string $model = BusinessHoursSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 12;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.admin.sla_group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.business_hours.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.business_hours.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return BusinessHoursScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusinessHoursSchedulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TimeSlotsRelationManager::class,
            RelationManagers\HolidaysRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBusinessHoursSchedules::route('/'),
            'create' => Pages\CreateBusinessHoursSchedule::route('/create'),
            'edit' => Pages\EditBusinessHoursSchedule::route('/{record}/edit'),
        ];
    }
}
