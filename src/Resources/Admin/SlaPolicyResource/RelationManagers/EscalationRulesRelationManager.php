<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\SlaPolicyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\ServiceDesk\Enums\EscalationAction;
use JeffersonGoncalves\ServiceDesk\Enums\SlaBreachType;

class EscalationRulesRelationManager extends RelationManager
{
    protected static string $relationship = 'escalationRules';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.escalation_rules');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->required()
                    ->reactive(),
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('breach_type')
            ->columns([
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
