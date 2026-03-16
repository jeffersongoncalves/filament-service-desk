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
            Admin\Resources\Departments\DepartmentResource::class,
            Admin\Resources\Categories\CategoryResource::class,
            Admin\Resources\Tags\TagResource::class,
            Admin\Resources\CannedResponses\CannedResponseResource::class,
            Admin\Resources\Tickets\TicketResource::class,
        ];

        if ($this->hasSla()) {
            $resources = array_merge($resources, [
                Admin\Resources\SlaPolicies\SlaPolicyResource::class,
                Admin\Resources\EscalationRules\EscalationRuleResource::class,
                Admin\Resources\BusinessHoursSchedules\BusinessHoursScheduleResource::class,
            ]);
        }

        if ($this->hasEmailChannels()) {
            $resources[] = Admin\Resources\EmailChannels\EmailChannelResource::class;
        }

        if ($this->hasKnowledgeBase()) {
            $resources = array_merge($resources, [
                Admin\Resources\KbArticles\KbArticleResource::class,
                Admin\Resources\KbCategories\KbCategoryResource::class,
            ]);
        }

        if ($this->hasServiceCatalog()) {
            $resources = array_merge($resources, [
                Admin\Resources\Services\ServiceResource::class,
                Admin\Resources\ServiceCategories\ServiceCategoryResource::class,
            ]);
        }

        $panel
            ->resources($resources)
            ->widgets(config('filament-service-desk.widgets.admin', [
                Admin\Widgets\ServiceDeskOverviewWidget::class,
                Admin\Widgets\SlaComplianceWidget::class,
                Admin\Widgets\TicketsByDepartmentWidget::class,
            ]));
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
