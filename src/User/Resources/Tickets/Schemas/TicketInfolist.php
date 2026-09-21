<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets\Schemas;

use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use JeffersonGoncalves\FilamentServiceDesk\Concerns\InteractsWithTicketApiTransport;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;
use JeffersonGoncalves\ServiceDesk\Services\Transports\ApiTicketTransport;

class TicketInfolist
{
    use InteractsWithTicketApiTransport;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(null)
            ->schema(array_filter([
                Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.ticket_details'))
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label(__('filament-service-desk::service-desk.fields.title'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('description')
                            ->label(__('filament-service-desk::service-desk.fields.description'))
                            ->html()
                            ->columnSpanFull(),
                        Infolists\Components\ViewEntry::make('status')
                            ->label(__('filament-service-desk::service-desk.fields.status_pipeline'))
                            ->view('filament-service-desk::components.ticket-status-stepper')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('department.name')
                            ->label(__('filament-service-desk::service-desk.fields.department'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('category.name')
                            ->label(__('filament-service-desk::service-desk.fields.category'))
                            ->placeholder('—'),
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

                static::isTicketApiTransport() ? static::attachmentsSection() : null,
            ]));
    }

    /**
     * Attachments under the API transport have no local Eloquent relation
     * (see TicketResource::getRelations()) -- rendered here straight from
     * ApiTicketTransport::listAttachments()/downloadAttachment(), inlined
     * as data: URIs since the API only accepts/returns attachments small
     * enough for that (service-desk.api.max_inline_attachment).
     */
    protected static function attachmentsSection(): Schemas\Components\Section
    {
        return Schemas\Components\Section::make(__('filament-service-desk::service-desk.relations.attachments'))
            ->schema([
                Infolists\Components\TextEntry::make('satellite_attachments')
                    ->hiddenLabel()
                    ->html()
                    ->state(function (Ticket $record) {
                        $transport = app(ApiTicketTransport::class);

                        // Metadata only from listAttachments(); a data: URI needs the
                        // actual bytes, one extra request per file, so this is capped
                        // -- fine for the "small enough to inline" files this API
                        // accepts in the first place (service-desk.api.max_inline_attachment).
                        $attachments = collect($transport->listAttachments($record))
                            ->take(10)
                            ->map(function (array $attachment) use ($transport, $record) {
                                $contents = $transport->downloadAttachment($record, $attachment['uuid']);
                                $attachment['dataUri'] = 'data:'.$attachment['mime_type'].';base64,'.base64_encode($contents);

                                return $attachment;
                            });

                        return view('filament-service-desk::components.satellite-attachments', [
                            'attachments' => $attachments,
                        ]);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
