<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tickets\Schemas;

use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;
use JeffersonGoncalves\ServiceDesk\Enums\TicketSource;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.ticket_details'))
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('filament-service-desk::service-desk.fields.title'))
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Forms\Components\RichEditor::make('description')
                                    ->label(__('filament-service-desk::service-desk.fields.description'))
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.ticket_metadata'))
                            ->schema([
                                Forms\Components\Select::make('department_id')
                                    ->label(__('filament-service-desk::service-desk.fields.department'))
                                    ->relationship('department', 'name')
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
                                        fn ($query, Schemas\Components\Utilities\Get $get) => $query->where('department_id', $get('department_id'))
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                                Forms\Components\Select::make('status')
                                    ->label(__('filament-service-desk::service-desk.fields.status'))
                                    ->options(collect(TicketStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()]))
                                    ->required()
                                    ->default(TicketStatus::Open->value),
                                Forms\Components\Select::make('priority')
                                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                                    ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($priority) => [$priority->value => $priority->label()]))
                                    ->required()
                                    ->default(TicketPriority::Medium->value),
                                Forms\Components\Select::make('source')
                                    ->label(__('filament-service-desk::service-desk.fields.source'))
                                    ->options(collect(TicketSource::cases())->mapWithKeys(fn ($source) => [$source->value => $source->name]))
                                    ->default(TicketSource::Web->value),
                                Forms\Components\MorphToSelect::make('assignedTo')
                                    ->label(__('filament-service-desk::service-desk.fields.assigned_to'))
                                    ->types([
                                        Forms\Components\MorphToSelect\Type::make(config('service-desk.models.operator', 'App\\Models\\User'))
                                            ->titleAttribute('name'),
                                    ])
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\DateTimePicker::make('due_at')
                                    ->label(__('filament-service-desk::service-desk.fields.due_at'))
                                    ->nullable(),
                                Forms\Components\Select::make('tags')
                                    ->label(__('filament-service-desk::service-desk.fields.tags'))
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload(),
                            ]),
                        Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.reference'))
                            ->schema([
                                Infolists\Components\TextEntry::make('reference_number')
                                    ->label(__('filament-service-desk::service-desk.fields.reference_number'))
                                    ->state(fn (?Ticket $record) => $record?->reference_number ?? __('filament-service-desk::service-desk.messages.auto_generated')),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                                    ->state(fn (?Ticket $record) => $record?->created_at?->diffForHumans() ?? '-'),
                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label(__('filament-service-desk::service-desk.fields.updated_at'))
                                    ->state(fn (?Ticket $record) => $record?->updated_at?->diffForHumans() ?? '-'),
                            ])
                            ->hiddenOn('create'),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
