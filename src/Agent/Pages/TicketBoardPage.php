<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Exceptions\InvalidStatusTransitionException;
use JeffersonGoncalves\ServiceDesk\Exceptions\UnauthorizedOperatorException;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;

class TicketBoardPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedViewColumns;

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament-service-desk::pages.agent.ticket-board';

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

    /** @return array<string, Collection<int, Ticket>> */
    public function getColumns(): array
    {
        $tickets = Ticket::with(['assignedTo'])
            ->orderByDesc('priority')
            ->limit(200)
            ->get()
            ->groupBy(fn (Ticket $ticket) => $ticket->status->value);

        $columns = [];

        foreach (TicketStatus::cases() as $status) {
            /** @var Collection<int, Ticket> $columnTickets */
            $columnTickets = $tickets->get($status->value) ?? new Collection;
            $columns[$status->value] = $columnTickets;
        }

        return $columns;
    }

    public function moveTicket(int $ticketId, string $newStatus): void
    {
        /** @phpstan-ignore staticMethod.notFound */
        $ticket = Ticket::findOrFail($ticketId);

        try {
            app(TicketService::class)->update($ticket, ['status' => $newStatus], auth()->guard()->user());
        } catch (InvalidStatusTransitionException|UnauthorizedOperatorException $e) {
            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
