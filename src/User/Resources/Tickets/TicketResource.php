<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets\Schemas\TicketForm;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets\Schemas\TicketInfolist;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets\Tables\TicketsTable;
use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

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
        return static::getEloquentQuery()
            ->whereNotIn('status', [TicketStatus::Closed->value, TicketStatus::Resolved->value])
            ->count() ?: null;
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where('user_id', $user->getKey())
            ->where('user_type', $user->getMorphClass());
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
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config()->has('filament-service-desk.resources.user.ticket');
    }
}
