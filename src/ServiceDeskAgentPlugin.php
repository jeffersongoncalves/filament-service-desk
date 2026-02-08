<?php

namespace JeffersonGoncalves\FilamentServiceDesk;

use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\FilamentServiceDesk\Concerns\HasServiceDeskPluginConfig;

class ServiceDeskAgentPlugin implements Plugin
{
    use HasServiceDeskPluginConfig;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-service-desk-agent';
    }

    public function register(Panel $panel): void
    {
        $resources = [
            Agent\Resources\Tickets\TicketResource::class,
            Agent\Resources\CannedResponses\CannedResponseResource::class,
        ];

        $pages = [
            Agent\Pages\TicketQueuePage::class,
            Agent\Pages\AgentDashboardPage::class,
        ];

        $widgets = [
            Agent\Widgets\AgentTicketStatsWidget::class,
            Agent\Widgets\SlaBreachWidget::class,
        ];

        $panel
            ->resources($resources)
            ->pages($pages)
            ->widgets($widgets);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
