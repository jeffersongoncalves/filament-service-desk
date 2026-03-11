<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequests;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequests\Schemas\ServiceRequestInfolist;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequests\Tables\ServiceRequestsTable;
use JeffersonGoncalves\ServiceDesk\Models\ServiceRequest;

class ServiceRequestResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.user.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.service_request.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.service_request.plural_label');
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where('requester_id', $user->getKey())
            ->where('requester_type', $user->getMorphClass());
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceRequestInfolist::configure($schema);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(null)->schema([]);
    }

    public static function table(Table $table): Table
    {
        return ServiceRequestsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceRequests::route('/'),
            'create' => Pages\CreateServiceRequest::route('/create'),
            'view' => Pages\ViewServiceRequest::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-service-desk.resources.user.service_request') !== null;
    }
}
