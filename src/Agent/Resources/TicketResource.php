<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Resources;

use BackedEnum;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\TicketResource\Pages;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\TicketResource\RelationManagers;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;
    protected static ?int $navigationSort = 0;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.agent.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.ticket.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.ticket.plural_label');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()
            ->whereIn('status', [TicketStatus::Open->value, TicketStatus::InProgress->value])
            ->count() ?: null;
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where(function (Builder $query) use ($user) {
                $query->where(function (Builder $q) use ($user) {
                    $q->where('assigned_to_id', $user->getKey())
                        ->where('assigned_to_type', $user->getMorphClass());
                });
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('filament-service-desk::service-desk.sections.ticket_details'))
                            ->schema([
                                Forms\Components\Placeholder::make('title')
                                    ->label(__('filament-service-desk::service-desk.fields.title'))
                                    ->content(fn (Ticket $record) => $record->title),
                                Forms\Components\Placeholder::make('description')
                                    ->label(__('filament-service-desk::service-desk.fields.description'))
                                    ->content(fn (Ticket $record) => new \Illuminate\Support\HtmlString($record->description)),
                                Forms\Components\Placeholder::make('user_name')
                                    ->label(__('filament-service-desk::service-desk.fields.requester'))
                                    ->content(fn (Ticket $record) => $record->user?->name ?? '—'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('filament-service-desk::service-desk.sections.manage'))
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label(__('filament-service-desk::service-desk.fields.status'))
                                    ->options(collect(TicketStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))
                                    ->required(),
                                Forms\Components\Select::make('priority')
                                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                                    ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()]))
                                    ->required(),
                            ]),
                        Forms\Components\Section::make(__('filament-service-desk::service-desk.sections.info'))
                            ->schema([
                                Forms\Components\Placeholder::make('reference_number')
                                    ->label(__('filament-service-desk::service-desk.fields.reference_number'))
                                    ->content(fn (Ticket $record) => $record->reference_number),
                                Forms\Components\Placeholder::make('department_name')
                                    ->label(__('filament-service-desk::service-desk.fields.department'))
                                    ->content(fn (Ticket $record) => $record->department?->name ?? '—'),
                                Forms\Components\Placeholder::make('category_name')
                                    ->label(__('filament-service-desk::service-desk.fields.category'))
                                    ->content(fn (Ticket $record) => $record->category?->name ?? '—'),
                                Forms\Components\Placeholder::make('created_at')
                                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                                    ->content(fn (Ticket $record) => $record->created_at?->diffForHumans()),
                                Forms\Components\Placeholder::make('due_at')
                                    ->label(__('filament-service-desk::service-desk.fields.due_at'))
                                    ->content(fn (Ticket $record) => $record->due_at?->diffForHumans() ?? '—'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('filament-service-desk::service-desk.sections.ticket_details'))
                            ->schema([
                                Infolists\Components\TextEntry::make('title')
                                    ->label(__('filament-service-desk::service-desk.fields.title'))
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('description')
                                    ->label(__('filament-service-desk::service-desk.fields.description'))
                                    ->html()
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('user.name')
                                    ->label(__('filament-service-desk::service-desk.fields.requester'))
                                    ->placeholder('—'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('filament-service-desk::service-desk.sections.manage'))
                            ->schema([
                                Infolists\Components\TextEntry::make('status')
                                    ->label(__('filament-service-desk::service-desk.fields.status'))
                                    ->badge()
                                    ->formatStateUsing(fn (TicketStatus $state) => $state->label())
                                    ->color(fn (TicketStatus $state) => match ($state) {
                                        TicketStatus::Open => 'info',
                                        TicketStatus::Pending => 'warning',
                                        TicketStatus::InProgress => 'primary',
                                        TicketStatus::OnHold => 'gray',
                                        TicketStatus::Resolved => 'success',
                                        TicketStatus::Closed => 'gray',
                                    }),
                                Infolists\Components\TextEntry::make('priority')
                                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                                    ->badge()
                                    ->formatStateUsing(fn (TicketPriority $state) => $state->label())
                                    ->color(fn (TicketPriority $state) => match ($state) {
                                        TicketPriority::Low => 'gray',
                                        TicketPriority::Medium => 'info',
                                        TicketPriority::High => 'warning',
                                        TicketPriority::Urgent => 'danger',
                                    }),
                            ]),
                        Infolists\Components\Section::make(__('filament-service-desk::service-desk.sections.info'))
                            ->schema([
                                Infolists\Components\TextEntry::make('reference_number')
                                    ->label(__('filament-service-desk::service-desk.fields.reference_number')),
                                Infolists\Components\TextEntry::make('department.name')
                                    ->label(__('filament-service-desk::service-desk.fields.department'))
                                    ->placeholder('—'),
                                Infolists\Components\TextEntry::make('category.name')
                                    ->label(__('filament-service-desk::service-desk.fields.category'))
                                    ->placeholder('—'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                                    ->since(),
                                Infolists\Components\TextEntry::make('due_at')
                                    ->label(__('filament-service-desk::service-desk.fields.due_at'))
                                    ->dateTime()
                                    ->placeholder('—'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament-service-desk::service-desk.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (TicketStatus $state) => $state->label())
                    ->color(fn (TicketStatus $state) => match ($state) {
                        TicketStatus::Open => 'info',
                        TicketStatus::Pending => 'warning',
                        TicketStatus::InProgress => 'primary',
                        TicketStatus::OnHold => 'gray',
                        TicketStatus::Resolved => 'success',
                        TicketStatus::Closed => 'gray',
                    })
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
                    ->label(__('filament-service-desk::service-desk.fields.requester'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament-service-desk::service-desk.fields.status'))
                    ->options(collect(TicketStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])),
                Tables\Filters\SelectFilter::make('priority')
                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                    ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()])),
            ])
            ->recordActions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('close')
                    ->label(__('filament-service-desk::service-desk.actions.close'))
                    ->icon(Heroicon::XCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Ticket $record) => app(TicketService::class)->close($record, auth()->user()))
                    ->visible(fn (Ticket $record) => $record->isOpen()),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
            RelationManagers\AttachmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
