<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tags;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tags\Schemas\TagForm;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tags\Tables\TagsTable;
use JeffersonGoncalves\ServiceDesk\Models\Tag;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.admin.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.tag.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.tag.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return TagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TagsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config()->has('filament-service-desk.resources.admin.tag');
    }
}
