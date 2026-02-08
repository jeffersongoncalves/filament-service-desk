<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannels;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannels\Schemas\EmailChannelForm;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannels\Tables\EmailChannelsTable;
use JeffersonGoncalves\ServiceDesk\Models\EmailChannel;

class EmailChannelResource extends Resource
{
    protected static ?string $model = EmailChannel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.admin.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.email_channel.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.email_channel.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return EmailChannelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailChannelsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailChannels::route('/'),
            'create' => Pages\CreateEmailChannel::route('/create'),
            'edit' => Pages\EditEmailChannel::route('/{record}/edit'),
        ];
    }
}
