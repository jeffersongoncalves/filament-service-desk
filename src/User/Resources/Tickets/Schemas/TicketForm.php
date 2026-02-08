<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(null)
            ->schema([
                Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.new_ticket'))
                    ->schema([
                        Forms\Components\Select::make('department_id')
                            ->label(__('filament-service-desk::service-desk.fields.department'))
                            ->relationship('department', 'name', fn ($query) => $query->where('is_active', true))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Schemas\Components\Utilities\Set $set) => $set('category_id', null)),
                        Forms\Components\Select::make('category_id')
                            ->label(__('filament-service-desk::service-desk.fields.category'))
                            ->relationship(
                                'category',
                                'name',
                                fn ($query, Schemas\Components\Utilities\Get $get) => $query
                                    ->where('department_id', $get('department_id'))
                                    ->where('is_active', true)
                            )
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-service-desk::service-desk.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->label(__('filament-service-desk::service-desk.fields.description'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('priority')
                            ->label(__('filament-service-desk::service-desk.fields.priority'))
                            ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()]))
                            ->default(TicketPriority::Medium->value)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
