<?php

namespace JeffersonGoncalves\FilamentServiceDesk;

use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\Filament\Kanban\Pages\KanbanBoard;
use JeffersonGoncalves\FilamentServiceDesk\Concerns\HasServiceDeskPluginConfig;

class ServiceDeskAgentPlugin implements Plugin
{
    use HasServiceDeskPluginConfig;

    protected bool $kanbanEnabled = false;

    /**
     * Registers the optional drag-and-drop Kanban page alongside the ticket
     * queue. Requires jeffersongoncalves/filament-kanban -- see the README --
     * and is silently skipped if it is not installed, so calling this on a
     * host app without the dependency never breaks panel boot.
     */
    public function kanban(bool $condition = true): static
    {
        $this->kanbanEnabled = $condition;

        return $this;
    }

    public function hasKanban(): bool
    {
        return $this->kanbanEnabled;
    }

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
        if (config('service-desk.ticket.transport') === 'api') {
            throw new \RuntimeException(
                'ServiceDeskAgentPlugin cannot run under service-desk.ticket.transport=api: '
                .'the Agent panel lists, filters and manages tickets through direct Eloquent '
                .'relationships, which only exist on the side holding the ticket database. '
                .'Register this plugin on the central instance only.'
            );
        }

        $resources = config('filament-service-desk.agent.resources', []);

        $enabled = [
            $resources['ticket'] ?? Agent\Resources\TicketResource::class,
            $resources['canned_response'] ?? Agent\Resources\CannedResponseResource::class,
        ];

        $pages = [
            Agent\Pages\TicketQueuePage::class,
            Agent\Pages\AgentDashboardPage::class,
        ];

        if ($this->hasKanban() && class_exists(KanbanBoard::class)) {
            $pages[] = Agent\Pages\TicketBoardPage::class;
        }

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
