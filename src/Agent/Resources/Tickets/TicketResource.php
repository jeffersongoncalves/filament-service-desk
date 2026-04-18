<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\Tickets;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\Tickets\Schemas\TicketForm;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\Tickets\Schemas\TicketInfolist;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\Tickets\Tables\TicketsTable;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;

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
        $count = static::getEloquentQuery()
            ->whereIn('status', [TicketStatus::Open->value, TicketStatus::InProgress->value])
            ->count();

        return $count ? (string) $count : null;
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var Model $user */
        $user = auth()->guard()->user();

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
        return TicketForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TicketInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TicketsTable::configure($table);
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

    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-service-desk.agent.resources.ticket') !== null;
    }
}
