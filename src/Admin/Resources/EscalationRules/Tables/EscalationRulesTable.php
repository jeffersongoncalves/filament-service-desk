<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRules\Tables;

use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class EscalationRulesTable
{
    public static function configure(Table $table): Table
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
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
