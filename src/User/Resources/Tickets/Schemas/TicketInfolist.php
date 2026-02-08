<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets\Schemas;

use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;

class TicketInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.ticket_details'))
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label(__('filament-service-desk::service-desk.fields.title'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('description')
                            ->label(__('filament-service-desk::service-desk.fields.description'))
                            ->html()
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('department.name')
                            ->label(__('filament-service-desk::service-desk.fields.department'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('category.name')
                            ->label(__('filament-service-desk::service-desk.fields.category'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('status')
                            ->label(__('filament-service-desk::service-desk.fields.status'))
                            ->badge()
                            ->formatStateUsing(fn (TicketStatus $state) => $state->label())
                            ->color(fn (TicketStatus $state) => match ($state) {
                                TicketStatus::Open => 'info',
                                TicketStatus::Pending => 'warning',
                                TicketStatus::InProgress => 'primary',
                                TicketStatus::OnHold => 'gray',
                                TicketStatus::Resolved => 'success',
                                TicketStatus::Closed => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('priority')
                            ->label(__('filament-service-desk::service-desk.fields.priority'))
                            ->badge()
                            ->formatStateUsing(fn (TicketPriority $state) => $state->label())
                            ->color(fn (TicketPriority $state) => match ($state) {
                                TicketPriority::Low => 'gray',
                                TicketPriority::Medium => 'info',
                                TicketPriority::High => 'warning',
                                TicketPriority::Urgent => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('reference_number')
                            ->label(__('filament-service-desk::service-desk.fields.reference_number')),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label(__('filament-service-desk::service-desk.fields.created_at'))
                            ->since(),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label(__('filament-service-desk::service-desk.fields.updated_at'))
                            ->since(),
                    ])
                    ->columns(2),
            ]);
    }
}
