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
            Resources\User\TicketResource::class,
        ];

        $pages = [];

        $widgets = [
            Widgets\User\MyTicketsOverviewWidget::class,
        ];

        if ($this->hasServiceCatalog()) {
            $resources[] = Resources\User\ServiceRequestResource::class;
        }

        if ($this->hasKnowledgeBase()) {
            $pages[] = Pages\User\KnowledgeBasePage::class;
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
