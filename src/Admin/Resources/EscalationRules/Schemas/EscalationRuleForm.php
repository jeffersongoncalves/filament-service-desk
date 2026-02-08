<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRules\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use JeffersonGoncalves\ServiceDesk\Enums\EscalationAction;
use JeffersonGoncalves\ServiceDesk\Enums\SlaBreachType;

class EscalationRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make()
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
}
