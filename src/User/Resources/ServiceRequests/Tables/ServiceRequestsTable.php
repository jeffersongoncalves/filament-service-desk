<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequests\Tables;

use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\ServiceDesk\Enums\ServiceRequestStatus;

class ServiceRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label(__('filament-service-desk::service-desk.fields.service'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ticket.reference_number')
                    ->label(__('filament-service-desk::service-desk.fields.ticket'))
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament-service-desk::service-desk.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (ServiceRequestStatus $state) => $state->name)
                    ->color(fn (ServiceRequestStatus $state) => match ($state) {
                        ServiceRequestStatus::Pending => 'warning',
                        ServiceRequestStatus::Approved => 'info',
                        ServiceRequestStatus::Rejected => 'danger',
                        ServiceRequestStatus::InProgress => 'primary',
                        ServiceRequestStatus::Fulfilled => 'success',
                        ServiceRequestStatus::Cancelled => 'gray',
                    })
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
                    ->options(collect(ServiceRequestStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->name])),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
            ]);
    }
}
