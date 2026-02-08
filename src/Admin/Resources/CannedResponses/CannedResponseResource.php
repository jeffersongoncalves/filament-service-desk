<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\CannedResponses;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\CannedResponses\Schemas\CannedResponseForm;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\CannedResponses\Tables\CannedResponsesTable;
use JeffersonGoncalves\ServiceDesk\Models\CannedResponse;

class CannedResponseResource extends Resource
{
    protected static ?string $model = CannedResponse::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.admin.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.canned_response.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.canned_response.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return CannedResponseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CannedResponsesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCannedResponses::route('/'),
            'create' => Pages\CreateCannedResponse::route('/create'),
            'edit' => Pages\EditCannedResponse::route('/{record}/edit'),
        ];
    }
}
