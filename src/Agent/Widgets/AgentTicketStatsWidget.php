<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Widgets;

use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;

class AgentTicketStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        /** @var Model $user */
        $user = auth()->guard()->user();

        $myTicketsQuery = Ticket::where('assigned_to_id', $user->getKey()) /** @phpstan-ignore staticMethod.notFound */
            ->where('assigned_to_type', $user->getMorphClass());

        return [
            Stat::make(
                __('filament-service-desk::service-desk.widgets.agent_stats.my_open'),
                (clone $myTicketsQuery)->where('status', TicketStatus::Open)->count()
            )
                ->icon(Heroicon::Ticket)
                ->color('info'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.agent_stats.my_in_progress'),
                (clone $myTicketsQuery)->where('status', TicketStatus::InProgress)->count()
            )
                ->icon(Heroicon::ArrowPath)
                ->color('primary'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.agent_stats.resolved_today'),
                (clone $myTicketsQuery)
                    ->where('status', TicketStatus::Resolved)
                    ->whereDate('resolved_at', today())
                    ->count()
            )
                ->icon(Heroicon::CheckCircle)
                ->color('success'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.agent_stats.queue_size'),
                Ticket::whereNull('assigned_to_id') /** @phpstan-ignore staticMethod.notFound */
                    ->whereNotIn('status', [TicketStatus::Closed->value, TicketStatus::Resolved->value])
                    ->count()
            )
                ->icon(Heroicon::QueueList)
                ->color('warning'),
        ];
    }
}
