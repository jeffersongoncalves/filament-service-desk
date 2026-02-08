<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Services\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.general'))
                            ->schema([
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
                                Forms\Components\RichEditor::make('long_description')
                                    ->label(__('filament-service-desk::service-desk.fields.long_description'))
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.settings'))
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label(__('filament-service-desk::service-desk.fields.category'))
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                                Forms\Components\Select::make('department_id')
                                    ->label(__('filament-service-desk::service-desk.fields.department'))
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                                Forms\Components\TextInput::make('icon')
                                    ->label(__('filament-service-desk::service-desk.fields.icon'))
                                    ->maxLength(255),
                                Forms\Components\Select::make('default_priority')
                                    ->label(__('filament-service-desk::service-desk.fields.default_priority'))
                                    ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()]))
                                    ->nullable(),
                                Forms\Components\Select::make('visibility')
                                    ->label(__('filament-service-desk::service-desk.fields.visibility'))
                                    ->options([
                                        'public' => __('filament-service-desk::service-desk.visibility.public'),
                                        'internal' => __('filament-service-desk::service-desk.visibility.internal'),
                                    ])
                                    ->default('public'),
                                Forms\Components\Toggle::make('requires_approval')
                                    ->label(__('filament-service-desk::service-desk.fields.requires_approval'))
                                    ->default(false),
                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('filament-service-desk::service-desk.fields.is_active'))
                                    ->default(true),
                                Forms\Components\TextInput::make('expected_duration_minutes')
                                    ->label(__('filament-service-desk::service-desk.fields.expected_duration'))
                                    ->numeric()
                                    ->suffix(__('filament-service-desk::service-desk.fields.minutes'))
                                    ->nullable(),
                                Forms\Components\TextInput::make('sort_order')
                                    ->label(__('filament-service-desk::service-desk.fields.sort_order'))
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
