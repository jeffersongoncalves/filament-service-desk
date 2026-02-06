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
            Resources\Admin\DepartmentResource::class,
            Resources\Admin\CategoryResource::class,
            Resources\Admin\TagResource::class,
            Resources\Admin\CannedResponseResource::class,
            Resources\Admin\TicketResource::class,
        ];

        $widgets = [
            Widgets\Admin\ServiceDeskOverviewWidget::class,
        ];

        if ($this->hasSla()) {
            $resources = array_merge($resources, [
                Resources\Admin\SlaPolicyResource::class,
                Resources\Admin\EscalationRuleResource::class,
                Resources\Admin\BusinessHoursScheduleResource::class,
            ]);
            $widgets[] = Widgets\Admin\SlaComplianceWidget::class;
        }

        if ($this->hasEmailChannels()) {
            $resources[] = Resources\Admin\EmailChannelResource::class;
        }

        if ($this->hasKnowledgeBase()) {
            $resources = array_merge($resources, [
                Resources\Admin\KbArticleResource::class,
                Resources\Admin\KbCategoryResource::class,
            ]);
        }

        if ($this->hasServiceCatalog()) {
            $resources = array_merge($resources, [
                Resources\Admin\ServiceResource::class,
                Resources\Admin\ServiceCategoryResource::class,
            ]);
        }

        $widgets[] = Widgets\Admin\TicketsByDepartmentWidget::class;

        $panel
            ->resources($resources)
            ->widgets($widgets);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
