<?php

namespace JeffersonGoncalves\FilamentServiceDesk;

use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\FilamentServiceDesk\Concerns\HasServiceDeskPluginConfig;

class ServiceDeskAdminPlugin implements Plugin
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
        return 'filament-service-desk-admin';
    }

    public function register(Panel $panel): void
    {
        $resources = config('filament-service-desk.admin.resources', []);

        $enabled = [
            $resources['department'] ?? Admin\Resources\DepartmentResource::class,
            $resources['category'] ?? Admin\Resources\CategoryResource::class,
            $resources['tag'] ?? Admin\Resources\TagResource::class,
            $resources['canned_response'] ?? Admin\Resources\CannedResponseResource::class,
            $resources['ticket'] ?? Admin\Resources\TicketResource::class,
        ];

        if ($this->hasSla()) {
            $enabled[] = $resources['sla_policy'] ?? Admin\Resources\SlaPolicyResource::class;
            $enabled[] = $resources['escalation_rule'] ?? Admin\Resources\EscalationRuleResource::class;
            $enabled[] = $resources['business_hours_schedule'] ?? Admin\Resources\BusinessHoursScheduleResource::class;
        }

        if ($this->hasEmailChannels()) {
            $enabled[] = $resources['email_channel'] ?? Admin\Resources\EmailChannelResource::class;
        }

        if ($this->hasKnowledgeBase()) {
            $enabled[] = $resources['kb_article'] ?? Admin\Resources\KbArticleResource::class;
            $enabled[] = $resources['kb_category'] ?? Admin\Resources\KbCategoryResource::class;
        }

        if ($this->hasServiceCatalog()) {
            $enabled[] = $resources['service'] ?? Admin\Resources\ServiceResource::class;
            $enabled[] = $resources['service_category'] ?? Admin\Resources\ServiceCategoryResource::class;
        }

        $panel
            ->resources(array_values(array_filter($enabled)))
            ->widgets(config('filament-service-desk.admin.widgets', [
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
