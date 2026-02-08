<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\SlaPolicies\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;

class SlaPolicyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(null)
            ->schema([
                Schemas\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament-service-desk::service-desk.fields.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('business_hours_schedule_id')
                            ->label(__('filament-service-desk::service-desk.fields.business_hours_schedule'))
                            ->relationship('businessHoursSchedule', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament-service-desk::service-desk.fields.description'))
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\KeyValue::make('conditions')
                            ->label(__('filament-service-desk::service-desk.fields.conditions'))
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament-service-desk::service-desk.fields.is_active'))
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('filament-service-desk::service-desk.fields.sort_order'))
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }
}
