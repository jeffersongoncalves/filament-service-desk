<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Categories\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(null)
            ->schema([
                Schemas\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('department_id')
                            ->label(__('filament-service-desk::service-desk.fields.department'))
                            ->relationship('department', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('parent_id')
                            ->label(__('filament-service-desk::service-desk.fields.parent_category'))
                            ->relationship('parent', 'name', fn ($query) => $query->whereNull('parent_id'))
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament-service-desk::service-desk.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament-service-desk::service-desk.fields.description'))
                            ->maxLength(65535)
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
