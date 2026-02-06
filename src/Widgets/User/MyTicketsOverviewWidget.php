<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Widgets\User;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;

class MyTicketsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        $user = auth()->user();

        $myTicketsQuery = Ticket::where('user_id', $user->getKey())
            ->where('user_type', $user->getMorphClass());

        return [
            Stat::make(
                __('filament-service-desk::service-desk.widgets.my_tickets.total'),
                (clone $myTicketsQuery)->count()
            )
                ->icon('heroicon-o-ticket'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.my_tickets.open'),
                (clone $myTicketsQuery)
                    ->whereNotIn('status', [TicketStatus::Closed->value, TicketStatus::Resolved->value])
                    ->count()
            )
                ->icon('heroicon-o-exclamation-circle')
                ->color('info'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.my_tickets.resolved'),
                (clone $myTicketsQuery)
                    ->whereIn('status', [TicketStatus::Resolved->value, TicketStatus::Closed->value])
                    ->count()
            )
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
