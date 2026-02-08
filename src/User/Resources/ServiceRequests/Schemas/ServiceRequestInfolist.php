<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequests\Schemas;

use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use JeffersonGoncalves\ServiceDesk\Enums\ServiceRequestStatus;
use JeffersonGoncalves\ServiceDesk\Models\ServiceRequest;

class ServiceRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(null)
            ->schema([
                Schemas\Components\Section::make(__('filament-service-desk::service-desk.resources.service_request.label'))
                    ->schema([
                        Infolists\Components\TextEntry::make('service.name')
                            ->label(__('filament-service-desk::service-desk.fields.service')),
                        Infolists\Components\TextEntry::make('status')
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
                            }),
                        Infolists\Components\TextEntry::make('ticket.reference_number')
                            ->label(__('filament-service-desk::service-desk.fields.ticket'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('notes')
                            ->label(__('filament-service-desk::service-desk.fields.notes'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        Infolists\Components\KeyValueEntry::make('form_data')
                            ->label(__('filament-service-desk::service-desk.fields.form_data'))
                            ->columnSpanFull()
                            ->visible(fn (ServiceRequest $record) => ! empty($record->form_data)),
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
