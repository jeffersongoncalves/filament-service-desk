<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\TicketResource;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;

class TicketQueuePage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament-service-desk::pages.agent.ticket-queue';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.agent.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-service-desk::service-desk.pages.ticket_queue.label');
    }

    public function getTitle(): string
    {
        return __('filament-service-desk::service-desk.pages.ticket_queue.title');
    }

    public static function getNavigationBadge(): ?string
    {
        /** @phpstan-ignore staticMethod.notFound */
        $count = Ticket::whereNull('assigned_to_id')
            ->whereIn('status', [TicketStatus::Open->value, TicketStatus::Pending->value])
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ticket::query()
                    ->whereNull('assigned_to_id')
                    ->whereIn('status', [TicketStatus::Open->value, TicketStatus::Pending->value])
            )
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')
                    ->label(__('filament-service-desk::service-desk.fields.reference_number'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-service-desk::service-desk.fields.title'))
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('department.name')
                    ->label(__('filament-service-desk::service-desk.fields.department'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                    ->badge()
                    ->formatStateUsing(fn (TicketPriority $state) => $state->label())
                    ->color(fn (TicketPriority $state) => match ($state) {
                        TicketPriority::Low => 'gray',
                        TicketPriority::Medium => 'info',
                        TicketPriority::High => 'warning',
                        TicketPriority::Urgent => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament-service-desk::service-desk.fields.requester')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->label(__('filament-service-desk::service-desk.fields.department'))
                    ->relationship('department', 'name'),
                Tables\Filters\SelectFilter::make('priority')
                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                    ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()])),
            ])
            ->actions([
                Tables\Actions\Action::make('claim')
                    ->label(__('filament-service-desk::service-desk.actions.claim'))
                    ->icon('heroicon-o-hand-raised')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->action(function (Ticket $record) {
                        app(TicketService::class)->assign($record, auth()->guard()->user(), auth()->guard()->user());

                        return redirect(TicketResource::getUrl('view', ['record' => $record]));
                    }),
            ]);
    }
}
