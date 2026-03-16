<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Widgets;

use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;

class MyTicketsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        /** @var Model $user */
        $user = auth()->guard()->user();

        $myTicketsQuery = Ticket::where('user_id', $user->getKey()) /** @phpstan-ignore staticMethod.notFound */
            ->where('user_type', $user->getMorphClass());

        return [
            Stat::make(
                __('filament-service-desk::service-desk.widgets.my_tickets.total'),
                (clone $myTicketsQuery)->count()
            )
                ->icon(Heroicon::Ticket),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.my_tickets.open'),
                (clone $myTicketsQuery)
                    ->whereNotIn('status', [TicketStatus::Closed->value, TicketStatus::Resolved->value])
                    ->count()
            )
                ->icon(Heroicon::ExclamationCircle)
                ->color('info'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.my_tickets.resolved'),
                (clone $myTicketsQuery)
                    ->whereIn('status', [TicketStatus::Resolved->value, TicketStatus::Closed->value])
                    ->count()
            )
                ->icon(Heroicon::CheckCircle)
                ->color('success'),
        ];
    }
}
