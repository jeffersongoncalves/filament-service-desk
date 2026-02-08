<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tags\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;

class TagForm
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
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\ColorPicker::make('color')
                            ->label(__('filament-service-desk::service-desk.fields.color')),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament-service-desk::service-desk.fields.description'))
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
