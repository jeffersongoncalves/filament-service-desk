<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Widgets;

use Filament\Support\Icons\Heroicon;
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
                Ticket::where('status', TicketStatus::Open)->count() // @phpstan-ignore staticMethod.notFound
            )
                ->icon(Heroicon::Ticket)
                ->color('info'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.overview.in_progress'),
                Ticket::where('status', TicketStatus::InProgress)->count() // @phpstan-ignore staticMethod.notFound
            )
                ->icon(Heroicon::ArrowPath)
                ->color('primary'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.overview.unassigned'),
                Ticket::whereNull('assigned_to_id') // @phpstan-ignore staticMethod.notFound
                    ->whereNotIn('status', [TicketStatus::Closed->value, TicketStatus::Resolved->value])
                    ->count()
            )
                ->icon(Heroicon::UserMinus)
                ->color('warning'),
            Stat::make(
                __('filament-service-desk::service-desk.widgets.overview.resolved_today'),
                Ticket::where('status', TicketStatus::Resolved) // @phpstan-ignore staticMethod.notFound
                    ->whereDate('resolved_at', today())
                    ->count()
            )
                ->icon(Heroicon::CheckCircle)
                ->color('success'),
        ];
    }
}
