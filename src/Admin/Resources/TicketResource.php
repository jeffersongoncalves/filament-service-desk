<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\TicketResource\Pages;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\TicketResource\RelationManagers;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;
use JeffersonGoncalves\ServiceDesk\Enums\TicketSource;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?int $navigationSort = 0;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.admin.group');
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
        return static::getModel()::where('status', TicketStatus::Open)->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('filament-service-desk::service-desk.sections.ticket_details'))
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('filament-service-desk::service-desk.fields.title'))
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Forms\Components\RichEditor::make('description')
                                    ->label(__('filament-service-desk::service-desk.fields.description'))
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('filament-service-desk::service-desk.sections.ticket_metadata'))
                            ->schema([
                                Forms\Components\Select::make('department_id')
                                    ->label(__('filament-service-desk::service-desk.fields.department'))
                                    ->relationship('department', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(fn (Forms\Set $set) => $set('category_id', null)),
                                Forms\Components\Select::make('category_id')
                                    ->label(__('filament-service-desk::service-desk.fields.category'))
                                    ->relationship(
                                        'category',
                                        'name',
                                        fn ($query, Forms\Get $get) => $query->where('department_id', $get('department_id'))
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                                Forms\Components\Select::make('status')
                                    ->label(__('filament-service-desk::service-desk.fields.status'))
                                    ->options(collect(TicketStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()]))
                                    ->required()
                                    ->default(TicketStatus::Open->value),
                                Forms\Components\Select::make('priority')
                                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                                    ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($priority) => [$priority->value => $priority->label()]))
                                    ->required()
                                    ->default(TicketPriority::Medium->value),
                                Forms\Components\Select::make('source')
                                    ->label(__('filament-service-desk::service-desk.fields.source'))
                                    ->options(collect(TicketSource::cases())->mapWithKeys(fn ($source) => [$source->value => $source->name]))
                                    ->default(TicketSource::Web->value),
                                Forms\Components\Select::make('assigned_to_id')
                                    ->label(__('filament-service-desk::service-desk.fields.assigned_to'))
                                    ->relationship('assignedTo', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                                Forms\Components\DateTimePicker::make('due_at')
                                    ->label(__('filament-service-desk::service-desk.fields.due_at'))
                                    ->nullable(),
                                Forms\Components\Select::make('tags')
                                    ->label(__('filament-service-desk::service-desk.fields.tags'))
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload(),
                            ]),
                        Forms\Components\Section::make(__('filament-service-desk::service-desk.sections.reference'))
                            ->schema([
                                Forms\Components\Placeholder::make('reference_number')
                                    ->label(__('filament-service-desk::service-desk.fields.reference_number'))
                                    ->content(fn (?Ticket $record) => $record?->reference_number ?? __('filament-service-desk::service-desk.messages.auto_generated')),
                                Forms\Components\Placeholder::make('created_at')
                                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                                    ->content(fn (?Ticket $record) => $record?->created_at?->diffForHumans() ?? '-'),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label(__('filament-service-desk::service-desk.fields.updated_at'))
                                    ->content(fn (?Ticket $record) => $record?->updated_at?->diffForHumans() ?? '-'),
                            ])
                            ->hiddenOn('create'),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
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
                        Infolists\Components\Section::make(__('filament-service-desk::service-desk.sections.ticket_metadata'))
                            ->schema([
                                Infolists\Components\TextEntry::make('department.name')
                                    ->label(__('filament-service-desk::service-desk.fields.department'))
                                    ->placeholder('—'),
                                Infolists\Components\TextEntry::make('category.name')
                                    ->label(__('filament-service-desk::service-desk.fields.category'))
                                    ->placeholder('—'),
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
                                Infolists\Components\TextEntry::make('source')
                                    ->label(__('filament-service-desk::service-desk.fields.source'))
                                    ->formatStateUsing(fn (TicketSource $state) => $state->name)
                                    ->placeholder('—'),
                                Infolists\Components\TextEntry::make('assignedTo.name')
                                    ->label(__('filament-service-desk::service-desk.fields.assigned_to'))
                                    ->placeholder(__('filament-service-desk::service-desk.fields.unassigned')),
                                Infolists\Components\TextEntry::make('due_at')
                                    ->label(__('filament-service-desk::service-desk.fields.due_at'))
                                    ->dateTime()
                                    ->placeholder('—'),
                                Infolists\Components\TextEntry::make('tags.name')
                                    ->label(__('filament-service-desk::service-desk.fields.tags'))
                                    ->badge()
                                    ->placeholder('—'),
                            ]),
                        Infolists\Components\Section::make(__('filament-service-desk::service-desk.sections.reference'))
                            ->schema([
                                Infolists\Components\TextEntry::make('reference_number')
                                    ->label(__('filament-service-desk::service-desk.fields.reference_number')),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                                    ->since(),
                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label(__('filament-service-desk::service-desk.fields.updated_at'))
                                    ->since(),
                            ]),
                        Infolists\Components\Section::make(__('filament-service-desk::service-desk.sections.sla'))
                            ->schema([
                                Infolists\Components\TextEntry::make('first_response_due')
                                    ->label(__('filament-service-desk::service-desk.fields.first_response_due'))
                                    ->dateTime()
                                    ->placeholder('—'),
                                Infolists\Components\TextEntry::make('resolution_due')
                                    ->label(__('filament-service-desk::service-desk.fields.resolution_due'))
                                    ->dateTime()
                                    ->placeholder('—'),
                            ])
                            ->visible(fn (Ticket $record) => $record->first_response_due || $record->resolution_due),
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
                Tables\Columns\TextColumn::make('department.name')
                    ->label(__('filament-service-desk::service-desk.fields.department'))
                    ->sortable(),
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
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label(__('filament-service-desk::service-desk.fields.assigned_to'))
                    ->placeholder(__('filament-service-desk::service-desk.fields.unassigned'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_at')
                    ->label(__('filament-service-desk::service-desk.fields.due_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament-service-desk::service-desk.fields.status'))
                    ->options(collect(TicketStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])),
                Tables\Filters\SelectFilter::make('priority')
                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                    ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($priority) => [$priority->value => $priority->label()])),
                Tables\Filters\SelectFilter::make('department_id')
                    ->label(__('filament-service-desk::service-desk.fields.department'))
                    ->relationship('department', 'name'),
                Tables\Filters\Filter::make('unassigned')
                    ->label(__('filament-service-desk::service-desk.filters.unassigned'))
                    ->query(fn ($query) => $query->whereNull('assigned_to_id'))
                    ->toggle(),
                Tables\Filters\Filter::make('overdue')
                    ->label(__('filament-service-desk::service-desk.filters.overdue'))
                    ->query(fn ($query) => $query->where('due_at', '<', now())->whereNotIn('status', [TicketStatus::Closed->value, TicketStatus::Resolved->value]))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
            RelationManagers\AttachmentsRelationManager::class,
            RelationManagers\HistoryRelationManager::class,
            RelationManagers\WatchersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config()->has('filament-service-desk.resources.admin.ticket');
    }
}
