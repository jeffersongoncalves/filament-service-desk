<?php

namespace JeffersonGoncalves\FilamentServiceDesk;

use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\FilamentServiceDesk\Concerns\HasServiceDeskPluginConfig;

class ServiceDeskPlugin implements Plugin
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
        return 'filament-service-desk';
    }

    public function register(Panel $panel): void
    {
        $resources = [
            Admin\Resources\DepartmentResource::class,
            Admin\Resources\CategoryResource::class,
            Admin\Resources\TagResource::class,
            Admin\Resources\CannedResponseResource::class,
            Admin\Resources\TicketResource::class,
        ];

        $widgets = [
            Admin\Widgets\ServiceDeskOverviewWidget::class,
        ];

        if ($this->hasSla()) {
            $resources = array_merge($resources, [
                Admin\Resources\SlaPolicyResource::class,
                Admin\Resources\EscalationRuleResource::class,
                Admin\Resources\BusinessHoursScheduleResource::class,
            ]);
            $widgets[] = Admin\Widgets\SlaComplianceWidget::class;
        }

        if ($this->hasEmailChannels()) {
            $resources[] = Admin\Resources\EmailChannelResource::class;
        }

        if ($this->hasKnowledgeBase()) {
            $resources = array_merge($resources, [
                Admin\Resources\KbArticleResource::class,
                Admin\Resources\KbCategoryResource::class,
            ]);
        }

        if ($this->hasServiceCatalog()) {
            $resources = array_merge($resources, [
                Admin\Resources\ServiceResource::class,
                Admin\Resources\ServiceCategoryResource::class,
            ]);
        }

        $widgets[] = Admin\Widgets\TicketsByDepartmentWidget::class;

        $panel
            ->resources($resources)
            ->widgets($widgets);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
