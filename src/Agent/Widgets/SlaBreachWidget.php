<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\ServiceDesk\Models\TicketSla;

class SlaBreachWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        /** @var Model $user */
        $user = auth()->guard()->user();

        return $table
            ->heading(__('filament-service-desk::service-desk.widgets.sla_breach.heading'))
            ->query(
                TicketSla::query()
                    ->whereHas('ticket', function ($q) use ($user) {
                        $q->where('assigned_to_id', $user->getKey())
                            ->where('assigned_to_type', $user->getMorphClass())
                            ->whereNotIn('status', ['closed', 'resolved']);
                    })
                    ->where(function ($q) {
                        $q->where('first_response_due_at', '<', now())
                            ->whereNull('first_responded_at');
                    })
                    ->orWhere(function ($q) use ($user) {
                        $q->whereHas('ticket', function ($q2) use ($user) {
                            $q2->where('assigned_to_id', $user->getKey())
                                ->where('assigned_to_type', $user->getMorphClass())
                                ->whereNotIn('status', ['closed', 'resolved']);
                        })
                            ->where('resolution_due_at', '<', now())
                            ->whereNull('resolved_at');
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('ticket.reference_number')
                    ->label(__('filament-service-desk::service-desk.fields.reference_number')),
                Tables\Columns\TextColumn::make('ticket.title')
                    ->label(__('filament-service-desk::service-desk.fields.title'))
                    ->limit(40),
                Tables\Columns\TextColumn::make('first_response_due_at')
                    ->label(__('filament-service-desk::service-desk.fields.first_response_due'))
                    ->dateTime()
                    ->color(fn ($record) => $record->first_response_due_at && $record->first_response_due_at->isPast() && ! $record->first_responded_at ? 'danger' : null),
                Tables\Columns\TextColumn::make('resolution_due_at')
                    ->label(__('filament-service-desk::service-desk.fields.resolution_due'))
                    ->dateTime()
                    ->color(fn ($record) => $record->resolution_due_at && $record->resolution_due_at->isPast() && ! $record->resolved_at ? 'danger' : null),
            ])
            ->paginated(false)
            ->defaultSort('first_response_due_at', 'asc');
    }
}
