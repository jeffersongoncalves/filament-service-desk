<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;

class ServiceDeskOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        return [
            Stat::make(
                __('filament-service-desk::service-desk.widgets.overview.open_tickets'),
                Ticket::where('status', TicketStatus::Open)->count()
            )
                ->icon('heroicon-o-ticket')
                ->color('info'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.overview.in_progress'),
                Ticket::where('status', TicketStatus::InProgress)->count()
            )
                ->icon('heroicon-o-arrow-path')
                ->color('primary'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.overview.unassigned'),
                Ticket::whereNull('assigned_to_id')
                    ->whereNotIn('status', [TicketStatus::Closed->value, TicketStatus::Resolved->value])
                    ->count()
            )
                ->icon('heroicon-o-user-minus')
                ->color('warning'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.overview.resolved_today'),
                Ticket::where('status', TicketStatus::Resolved)
                    ->whereDate('resolved_at', today())
                    ->count()
            )
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
