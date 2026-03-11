<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Pages;

use Filament\Pages\Page;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Widgets\AgentTicketStatsWidget;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Widgets\SlaBreachWidget;

class AgentDashboardPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = -1;

    protected static string $view = 'filament-service-desk::pages.agent.dashboard';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.agent.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-service-desk::service-desk.pages.agent_dashboard.label');
    }

    public function getTitle(): string
    {
        return __('filament-service-desk::service-desk.pages.agent_dashboard.title');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AgentTicketStatsWidget::class,
            SlaBreachWidget::class,
        ];
    }
}
