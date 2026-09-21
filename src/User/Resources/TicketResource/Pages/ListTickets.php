<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Concerns\InteractsWithTicketApiTransport;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;

class ListTickets extends ListRecords
{
    use InteractsWithTicketApiTransport;

    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        $table = parent::table($table);

        if (! static::isTicketApiTransport()) {
            return $table;
        }

        // TicketTransport has no listing/pagination method under the API
        // driver (see #23/#35) -- querying Eloquent here would silently
        // show "no tickets" instead of "listing isn't supported". Show an
        // explicit empty state instead of a real (always-empty) query.
        return $table
            ->query(fn () => Ticket::query()->whereRaw('1 = 0'))
            ->emptyStateIcon('heroicon-o-signal-slash')
            ->emptyStateHeading(__('filament-service-desk::service-desk.empty_states.satellite_listing.heading'))
            ->emptyStateDescription(__('filament-service-desk::service-desk.empty_states.satellite_listing.description'));
    }
}
