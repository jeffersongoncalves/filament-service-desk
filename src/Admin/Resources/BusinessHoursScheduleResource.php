<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources;

use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursScheduleResource\Pages;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursScheduleResource\RelationManagers;
use JeffersonGoncalves\ServiceDesk\Models\BusinessHoursSchedule;

class BusinessHoursScheduleResource extends Resource
{
    protected static ?string $model = BusinessHoursSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    protected static ?int $navigationSort = 12;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.admin.sla_group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.business_hours.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.business_hours.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament-service-desk::service-desk.fields.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('timezone')
                            ->label(__('filament-service-desk::service-desk.fields.timezone'))
                            ->options(collect(timezone_identifiers_list())->mapWithKeys(fn ($tz) => [$tz => $tz]))
                            ->searchable()
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament-service-desk::service-desk.fields.description'))
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_default')
                            ->label(__('filament-service-desk::service-desk.fields.is_default'))
                            ->default(false),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament-service-desk::service-desk.fields.is_active'))
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-service-desk::service-desk.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('timezone')
                    ->label(__('filament-service-desk::service-desk.fields.timezone'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->label(__('filament-service-desk::service-desk.fields.is_default'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament-service-desk::service-desk.fields.is_active'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament-service-desk::service-desk.fields.is_active')),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TimeSlotsRelationManager::class,
            RelationManagers\HolidaysRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBusinessHoursSchedules::route('/'),
            'create' => Pages\CreateBusinessHoursSchedule::route('/create'),
            'edit' => Pages\EditBusinessHoursSchedule::route('/{record}/edit'),
        ];
    }
}
