<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentServiceDesk\Concerns\InteractsWithTicketApiTransport;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource\Pages;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource\RelationManagers;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;
use JeffersonGoncalves\ServiceDesk\Services\KnowledgeBaseService;
use JeffersonGoncalves\ServiceDesk\Services\Transports\ApiTicketTransport;

class TicketResource extends Resource
{
    use InteractsWithTicketApiTransport;

    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?int $navigationSort = 0;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.user.group');
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
        if (static::isTicketApiTransport()) {
            return null;
        }

        $count = static::getEloquentQuery()
            ->whereNotIn('status', [TicketStatus::Closed->value, TicketStatus::Resolved->value])
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var Model $user */
        $user = auth()->guard()->user();

        return parent::getEloquentQuery()
            ->where('user_id', $user->getKey())
            ->where('user_type', $user->getMorphClass());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament-service-desk::service-desk.sections.new_ticket'))
                    ->schema([
                        Forms\Components\Select::make('department_id')
                            ->label(__('filament-service-desk::service-desk.fields.department'))
                            ->relationship('department', 'name', fn ($query) => $query->where('is_active', true))
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
                                fn ($query, Forms\Get $get) => $query
                                    ->where('department_id', $get('department_id'))
                                    ->where('is_active', true)
                            )
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-service-desk::service-desk.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->live(debounce: 500)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->label(__('filament-service-desk::service-desk.fields.description'))
                            ->required()
                            ->live(debounce: 500)
                            ->columnSpanFull(),
                        Forms\Components\Placeholder::make('suggested_articles')
                            ->hiddenLabel()
                            ->content(function (Forms\Get $get) {
                                $query = trim(($get('title') ?? '').' '.strip_tags((string) ($get('description') ?? '')));

                                if ($query === '') {
                                    return null;
                                }

                                $articles = app(KnowledgeBaseService::class)->search($query, ['limit' => 3]);

                                return $articles->isEmpty() ? null : view('filament-service-desk::components.kb-suggestions', ['articles' => $articles]);
                            })
                            ->visible(fn (Forms\Get $get) => filled($get('title')))
                            ->columnSpanFull(),
                        Forms\Components\Select::make('priority')
                            ->label(__('filament-service-desk::service-desk.fields.priority'))
                            ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()]))
                            ->default(TicketPriority::Medium->value)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema(array_filter([
                Infolists\Components\Section::make(__('filament-service-desk::service-desk.sections.ticket_details'))
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label(__('filament-service-desk::service-desk.fields.title'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('description')
                            ->label(__('filament-service-desk::service-desk.fields.description'))
                            ->html()
                            ->columnSpanFull(),
                        Infolists\Components\ViewEntry::make('status')
                            ->label(__('filament-service-desk::service-desk.fields.status_pipeline'))
                            ->view('filament-service-desk::components.ticket-status-stepper')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('department.name')
                            ->label(__('filament-service-desk::service-desk.fields.department'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('category.name')
                            ->label(__('filament-service-desk::service-desk.fields.category'))
                            ->placeholder('—'),
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
                        Infolists\Components\TextEntry::make('reference_number')
                            ->label(__('filament-service-desk::service-desk.fields.reference_number')),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label(__('filament-service-desk::service-desk.fields.created_at'))
                            ->since(),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label(__('filament-service-desk::service-desk.fields.updated_at'))
                            ->since(),
                    ])
                    ->columns(2),

                static::isTicketApiTransport() ? static::attachmentsSection() : null,
            ]));
    }

    /**
     * Attachments under the API transport have no local Eloquent relation
     * (see getRelations()) -- rendered here straight from
     * ApiTicketTransport::listAttachments()/downloadAttachment(), inlined
     * as data: URIs since the API only accepts/returns attachments small
     * enough for that (service-desk.api.max_inline_attachment).
     */
    protected static function attachmentsSection(): Infolists\Components\Section
    {
        return Infolists\Components\Section::make(__('filament-service-desk::service-desk.relations.attachments'))
            ->schema([
                Infolists\Components\TextEntry::make('satellite_attachments')
                    ->hiddenLabel()
                    ->html()
                    ->state(function (Ticket $record) {
                        $transport = app(ApiTicketTransport::class);

                        // Metadata only from listAttachments(); a data: URI needs the
                        // actual bytes, one extra request per file, so this is capped
                        // -- fine for the "small enough to inline" files this API
                        // accepts in the first place (service-desk.api.max_inline_attachment).
                        $attachments = collect($transport->listAttachments($record))
                            ->take(10)
                            ->map(function (array $attachment) use ($transport, $record) {
                                $contents = $transport->downloadAttachment($record, $attachment['uuid']);
                                $attachment['dataUri'] = 'data:'.$attachment['mime_type'].';base64,'.base64_encode($contents);

                                return $attachment;
                            });

                        return view('filament-service-desk::components.satellite-attachments', [
                            'attachments' => $attachments,
                        ]);
                    })
                    ->columnSpanFull(),
            ]);
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-service-desk::service-desk.fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament-service-desk::service-desk.fields.status'))
                    ->options(collect(TicketStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->emptyStateIcon('heroicon-o-inbox')
            ->emptyStateHeading(__('filament-service-desk::service-desk.empty_states.my_tickets.heading'))
            ->emptyStateDescription(__('filament-service-desk::service-desk.empty_states.my_tickets.description'));
    }

    public static function getRelations(): array
    {
        if (static::isTicketApiTransport()) {
            // Comments have no equivalent on TicketTransport -- the API driver
            // was scoped to CRUD/status/attachments only (see #35). Attachments
            // use a dedicated satellite-mode section on ViewTicket instead of
            // this Eloquent-relationship-backed manager.
            return [];
        }

        return [
            RelationManagers\CommentsRelationManager::class,
            RelationManagers\AttachmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-service-desk.user.resources.ticket') !== null;
    }
}
