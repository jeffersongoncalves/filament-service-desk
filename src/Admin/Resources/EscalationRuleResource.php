<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources;

use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRuleResource\Pages;
use JeffersonGoncalves\ServiceDesk\Enums\EscalationAction;
use JeffersonGoncalves\ServiceDesk\Enums\SlaBreachType;
use JeffersonGoncalves\ServiceDesk\Models\EscalationRule;

class EscalationRuleResource extends Resource
{
    protected static ?string $model = EscalationRule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowTrendingUp;
    protected static ?int $navigationSort = 11;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.admin.sla_group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.escalation_rule.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.escalation_rule.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('sla_policy_id')
                            ->label(__('filament-service-desk::service-desk.fields.sla_policy'))
                            ->relationship('slaPolicy', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('breach_type')
                            ->label(__('filament-service-desk::service-desk.fields.breach_type'))
                            ->options(collect(SlaBreachType::cases())->mapWithKeys(fn ($t) => [$t->value => $t->name]))
                            ->required(),
                        Forms\Components\Select::make('trigger_type')
                            ->label(__('filament-service-desk::service-desk.fields.trigger_type'))
                            ->options([
                                'before' => __('filament-service-desk::service-desk.fields.before_breach'),
                                'after' => __('filament-service-desk::service-desk.fields.after_breach'),
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('minutes_before')
                            ->label(__('filament-service-desk::service-desk.fields.minutes_before'))
                            ->numeric()
                            ->required()
                            ->suffix(__('filament-service-desk::service-desk.fields.minutes')),
                        Forms\Components\Select::make('action')
                            ->label(__('filament-service-desk::service-desk.fields.action'))
                            ->options(collect(EscalationAction::cases())->mapWithKeys(fn ($a) => [$a->value => $a->name]))
                            ->required(),
                        Forms\Components\KeyValue::make('action_config')
                            ->label(__('filament-service-desk::service-desk.fields.action_config'))
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament-service-desk::service-desk.fields.is_active'))
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('filament-service-desk::service-desk.fields.sort_order'))
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slaPolicy.name')
                    ->label(__('filament-service-desk::service-desk.fields.sla_policy'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('breach_type')
                    ->label(__('filament-service-desk::service-desk.fields.breach_type'))
                    ->badge(),
                Tables\Columns\TextColumn::make('trigger_type')
                    ->label(__('filament-service-desk::service-desk.fields.trigger_type')),
                Tables\Columns\TextColumn::make('minutes_before')
                    ->label(__('filament-service-desk::service-desk.fields.minutes_before'))
                    ->suffix(' min'),
                Tables\Columns\TextColumn::make('action')
                    ->label(__('filament-service-desk::service-desk.fields.action'))
                    ->badge(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament-service-desk::service-desk.fields.is_active'))
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('sla_policy_id')
                    ->label(__('filament-service-desk::service-desk.fields.sla_policy'))
                    ->relationship('slaPolicy', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament-service-desk::service-desk.fields.is_active')),
            ])
            ->recordActions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEscalationRules::route('/'),
            'create' => Pages\CreateEscalationRule::route('/create'),
            'edit' => Pages\EditEscalationRule::route('/{record}/edit'),
        ];
    }
}
