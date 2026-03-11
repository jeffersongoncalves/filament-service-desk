<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\Tickets\Tables;

use Filament\Actions;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')
                    ->label(__('filament-service-desk::service-desk.fields.reference_number'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-service-desk::service-desk.fields.title'))
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('status')
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
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                    ->badge()
                    ->formatStateUsing(fn (TicketPriority $state) => $state->label())
                    ->color(fn (TicketPriority $state) => match ($state) {
                        TicketPriority::Low => 'gray',
                        TicketPriority::Medium => 'info',
                        TicketPriority::High => 'warning',
                        TicketPriority::Urgent => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament-service-desk::service-desk.fields.requester'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament-service-desk::service-desk.fields.status'))
                    ->options(collect(TicketStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])),
                Tables\Filters\SelectFilter::make('priority')
                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                    ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()])),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\Action::make('close')
                    ->label(__('filament-service-desk::service-desk.actions.close'))
                    ->icon(Heroicon::XCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Ticket $record) => app(TicketService::class)->close($record, auth()->guard()->user()))
                    ->visible(fn (Ticket $record) => $record->isOpen()),
            ]);
    }
}
