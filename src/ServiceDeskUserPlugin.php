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
        $resources = [
            User\Resources\Tickets\TicketResource::class,
        ];

        $pages = [];

        $widgets = [
            User\Widgets\MyTicketsOverviewWidget::class,
        ];

        if ($this->hasServiceCatalog()) {
            $resources[] = User\Resources\ServiceRequests\ServiceRequestResource::class;
        }

        if ($this->hasKnowledgeBase()) {
            $pages[] = User\Pages\KnowledgeBasePage::class;
        }

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
