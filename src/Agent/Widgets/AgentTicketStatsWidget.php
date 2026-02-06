<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;

class AgentTicketStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        $user = auth()->user();

        $myTicketsQuery = Ticket::where('assigned_to_id', $user->getKey())
            ->where('assigned_to_type', $user->getMorphClass());

        return [
            Stat::make(
                __('filament-service-desk::service-desk.widgets.agent_stats.my_open'),
                (clone $myTicketsQuery)->where('status', TicketStatus::Open)->count()
            )
                ->icon('heroicon-o-ticket')
                ->color('info'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.agent_stats.my_in_progress'),
                (clone $myTicketsQuery)->where('status', TicketStatus::InProgress)->count()
            )
                ->icon('heroicon-o-arrow-path')
                ->color('primary'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.agent_stats.resolved_today'),
                (clone $myTicketsQuery)
                    ->where('status', TicketStatus::Resolved)
                    ->whereDate('resolved_at', today())
                    ->count()
            )
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.agent_stats.queue_size'),
                Ticket::whereNull('assigned_to_id')
                    ->whereNotIn('status', [TicketStatus::Closed->value, TicketStatus::Resolved->value])
                    ->count()
            )
                ->icon('heroicon-o-queue-list')
                ->color('warning'),
        ];
    }
}
