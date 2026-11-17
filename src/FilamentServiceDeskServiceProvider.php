<?php

namespace JeffersonGoncalves\FilamentServiceDesk;

use Filament\Facades\Filament;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\TicketResource as AdminTicketResource;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\TicketResource as AgentTicketResource;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource as UserTicketResource;
use JeffersonGoncalves\ServiceDesk\Models\Department;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Throwable;

class FilamentServiceDeskServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-service-desk';

    public static string $viewNamespace = 'filament-service-desk';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews(static::$viewNamespace)
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        $this->registerDepartmentOperatorsRelationship();
        $this->registerNotificationUrls();
    }

    /**
     * Point service desk notification links at the ticket's view page:
     * operators on the agent panel (or the admin panel without one),
     * requesters on the user panel. An application registering its own
     * resolver in its AppServiceProvider overrides this one, since app
     * providers boot later.
     */
    protected function registerNotificationUrls(): void
    {
        Ticket::resolveNotificationUrlUsing(function (Ticket $ticket, object $notifiable, bool $forOperator): string {
            $targets = $forOperator
                ? [
                    'filament-service-desk-agent' => config('filament-service-desk.agent.resources.ticket') ?? AgentTicketResource::class,
                    'filament-service-desk-admin' => config('filament-service-desk.admin.resources.ticket') ?? AdminTicketResource::class,
                ]
                : ['filament-service-desk-user' => config('filament-service-desk.user.resources.ticket') ?? UserTicketResource::class];

            foreach ($targets as $pluginId => $resource) {
                foreach (Filament::getPanels() as $panel) {
                    if (! $panel->hasPlugin($pluginId)) {
                        continue;
                    }

                    try {
                        return $resource::getUrl('view', ['record' => $ticket], panel: $panel->getId());
                    } catch (Throwable) {
                        // ponytail: a tenant-scoped panel needs a tenant to build the URL; fall back to home rather than fail the email.
                        break;
                    }
                }
            }

            return url('/');
        });
    }

    /**
     * Department has no operators relation of its own in the core package;
     * the admin panel's operators relation manager needs one for the
     * configured operator model.
     */
    protected function registerDepartmentOperatorsRelationship(): void
    {
        Department::resolveRelationUsing('operators', function (Department $department) {
            return $department->morphedByMany(
                config('service-desk.models.operator'),
                'operator',
                'service_desk_department_operator',
                'department_id',
            )->withPivot('role', 'email_notifications')->withTimestamps();
        });
    }
}
