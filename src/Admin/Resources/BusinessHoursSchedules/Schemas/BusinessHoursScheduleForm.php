<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursSchedules\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;

class BusinessHoursScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament-service-desk::service-desk.fields.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('timezone')
                            ->label(__('filament-service-desk::service-desk.fields.timezone'))
                            ->options(collect(timezone_identifiers_list())->mapWithKeys(fn ($tz) => [$tz => $tz]))
                            ->searchable()
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament-service-desk::service-desk.fields.description'))
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_default')
                            ->label(__('filament-service-desk::service-desk.fields.is_default'))
                            ->default(false),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament-service-desk::service-desk.fields.is_active'))
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
