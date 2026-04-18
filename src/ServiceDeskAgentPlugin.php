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
        $resources = config('filament-service-desk.agent.resources', []);

        $enabled = [
            $resources['ticket'] ?? Agent\Resources\TicketResource::class,
            $resources['canned_response'] ?? Agent\Resources\CannedResponseResource::class,
        ];

        $pages = [
            Agent\Pages\TicketQueuePage::class,
            Agent\Pages\AgentDashboardPage::class,
        ];

        $panel
            ->resources(array_values(array_filter($enabled)))
            ->pages($pages)
            ->widgets(config('filament-service-desk.agent.widgets', [
                Agent\Widgets\AgentTicketStatsWidget::class,
                Agent\Widgets\SlaBreachWidget::class,
            ]));
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
