<?php

namespace JeffersonGoncalves\FilamentServiceDesk;

use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\FilamentServiceDesk\Concerns\HasServiceDeskPluginConfig;

class ServiceDeskUserPlugin implements Plugin
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
        return 'filament-service-desk-user';
    }

    public function register(Panel $panel): void
    {
        $resources = config('filament-service-desk.user.resources', []);

        $enabled = [
            $resources['ticket'] ?? User\Resources\TicketResource::class,
        ];

        $pages = [];

        if ($this->hasServiceCatalog()) {
            $enabled[] = $resources['service_request'] ?? User\Resources\ServiceRequestResource::class;
        }

        if ($this->hasKnowledgeBase()) {
            $pages[] = User\Pages\KnowledgeBasePage::class;
        }

        $panel
            ->resources(array_values(array_filter($enabled)))
            ->pages($pages)
            ->widgets(config('filament-service-desk.user.widgets', [
                User\Widgets\MyTicketsOverviewWidget::class,
            ]));
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
