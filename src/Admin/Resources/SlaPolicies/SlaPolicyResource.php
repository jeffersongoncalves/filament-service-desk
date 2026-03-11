<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\SlaPolicies;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\SlaPolicies\Schemas\SlaPolicyForm;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\SlaPolicies\Tables\SlaPoliciesTable;
use JeffersonGoncalves\ServiceDesk\Models\SlaPolicy;

class SlaPolicyResource extends Resource
{
    protected static ?string $model = SlaPolicy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.admin.sla_group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.sla_policy.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.sla_policy.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return SlaPolicyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SlaPoliciesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TargetsRelationManager::class,
            RelationManagers\EscalationRulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSlaPolicies::route('/'),
            'create' => Pages\CreateSlaPolicy::route('/create'),
            'edit' => Pages\EditSlaPolicy::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config()->has('filament-service-desk.resources.admin.sla_policy');
    }
}
