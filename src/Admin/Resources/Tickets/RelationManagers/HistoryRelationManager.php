<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tickets\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class HistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'history';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.history');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('action')
            ->columns([
                Tables\Columns\TextColumn::make('action')
                    ->label(__('filament-service-desk::service-desk.fields.action'))
                    ->badge(),
                Tables\Columns\TextColumn::make('field')
                    ->label(__('filament-service-desk::service-desk.fields.field'))
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('old_value')
                    ->label(__('filament-service-desk::service-desk.fields.old_value'))
                    ->placeholder('—')
                    ->limit(50),
                Tables\Columns\TextColumn::make('new_value')
                    ->label(__('filament-service-desk::service-desk.fields.new_value'))
                    ->placeholder('—')
                    ->limit(50),
                Tables\Columns\TextColumn::make('performer.name')
                    ->label(__('filament-service-desk::service-desk.fields.performer'))
                    ->placeholder(__('filament-service-desk::service-desk.fields.system')),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('filament-service-desk::service-desk.fields.description'))
                    ->limit(80)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->recordActions([]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
