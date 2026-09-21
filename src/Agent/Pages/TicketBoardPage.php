<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use JeffersonGoncalves\Filament\Kanban\Pages\KanbanBoard;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Exceptions\InvalidStatusTransitionException;
use JeffersonGoncalves\ServiceDesk\Exceptions\UnauthorizedOperatorException;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;
use Livewire\Attributes\On;

/**
 * Optional drag-and-drop alternative to the ticket table — see
 * ServiceDeskAgentPlugin::kanban(). Only ever registered when
 * jeffersongoncalves/filament-kanban is installed; this class itself is
 * never autoloaded otherwise (see the plugin's class_exists() guard).
 */
class TicketBoardPage extends KanbanBoard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedViewColumns;

    protected static ?int $navigationSort = 2;

    protected static string $model = Ticket::class;

    protected static string $statusEnum = TicketStatus::class;

    protected static string $recordTitleAttribute = 'title';

    protected static string $recordStatusAttribute = 'status';

    // service_desk_tickets has no order column — reads order by created_at
    // instead, and both hooks below skip persistOrder() entirely, so this
    // column is never written to.
    protected static string $orderColumn = 'created_at';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.agent.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-service-desk::service-desk.pages.ticket_board.label');
    }

    public function getTitle(): string
    {
        return __('filament-service-desk::service-desk.pages.ticket_board.title');
    }

    /** @return Collection<int, array<string, string>> */
    protected function statuses(): Collection
    {
        // Closed is deliberately left off the board: a Kanban is a working
        // queue, and a closed ticket is done being worked. Reopening it back
        // onto the board still goes through the ticket view action, not a drag.
        return collect([
            TicketStatus::Open,
            TicketStatus::Pending,
            TicketStatus::InProgress,
            TicketStatus::OnHold,
            TicketStatus::Resolved,
        ])->map(fn (TicketStatus $status): array => [
            'id' => $status->value,
            'label' => $status->label(),
        ]);
    }

    /** @param  array<int, int|string>  $toOrderedIds */
    #[On('status-changed')]
    public function onStatusChanged(int|string $recordId, string $toStatus, array $toOrderedIds): void
    {
        /** @var Ticket|null $record */
        $record = $this->getEloquentQuery()->find($recordId);

        if (! $record) {
            return;
        }

        $fromStatus = $record->status;
        $targetStatus = TicketStatus::from($toStatus);

        if (! $this->canTransition($fromStatus, $targetStatus)) {
            $this->rejectMove($recordId, $fromStatus, $targetStatus);

            return;
        }

        try {
            // Routed through the service, not $record->update(): changeStatus()
            // is what fires TicketStatusChanged (SLA pause/resume listens on it)
            // and writes the history entry. A drag is a status change like any
            // other and must not skip either.
            app(TicketService::class)->changeStatus(
                ticket: $record,
                newStatus: $targetStatus,
                performer: auth()->guard()->user(),
            );
        } catch (InvalidStatusTransitionException|UnauthorizedOperatorException $e) {
            // canTransition() only knows the enum's own transition map; the
            // service also authorizes the performer, so a drag can still be
            // refused here.
            $this->rejectMove($recordId, $fromStatus, $targetStatus);

            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /** @param  array<int, int|string>  $orderedIds */
    #[On('sort-changed')]
    public function onSortChanged(array $orderedIds): void
    {
        // No order column to persist to — a pure in-column reorder is visual
        // only and reverts to created_at order on the next render.
    }
}
