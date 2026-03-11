<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\Tickets\Schemas;

use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;
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
                                Infolists\Components\TextEntry::make('title')
                                    ->label(__('filament-service-desk::service-desk.fields.title'))
                                    ->state(fn (Ticket $record) => $record->title),
                                Infolists\Components\TextEntry::make('description')
                                    ->label(__('filament-service-desk::service-desk.fields.description'))
                                    ->html()
                                    ->state(fn (Ticket $record) => $record->description),
                                Infolists\Components\TextEntry::make('user_name')
                                    ->label(__('filament-service-desk::service-desk.fields.requester'))
                                    ->state(fn (Ticket $record) => $record->user?->name ?? '—'), // @phpstan-ignore class.notFound
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.manage'))
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label(__('filament-service-desk::service-desk.fields.status'))
                                    ->options(collect(TicketStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))
                                    ->required(),
                                Forms\Components\Select::make('priority')
                                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                                    ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()]))
                                    ->required(),
                            ]),
                        Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.info'))
                            ->schema([
                                Infolists\Components\TextEntry::make('reference_number')
                                    ->label(__('filament-service-desk::service-desk.fields.reference_number'))
                                    ->state(fn (Ticket $record) => $record->reference_number),
                                Infolists\Components\TextEntry::make('department.name')
                                    ->label(__('filament-service-desk::service-desk.fields.department'))
                                    ->placeholder('—'),
                                Infolists\Components\TextEntry::make('category.name')
                                    ->label(__('filament-service-desk::service-desk.fields.category'))
                                    ->placeholder('—'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                                    ->since(),
                                Infolists\Components\TextEntry::make('due_at')
                                    ->label(__('filament-service-desk::service-desk.fields.due_at'))
                                    ->since()
                                    ->placeholder('—'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
