<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\CannedResponses\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;

class CannedResponseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(null)
            ->schema([
                Schemas\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-service-desk::service-desk.fields.title'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('department_id')
                            ->label(__('filament-service-desk::service-desk.fields.department'))
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\RichEditor::make('body')
                            ->label(__('filament-service-desk::service-desk.fields.body'))
                            ->required()
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
