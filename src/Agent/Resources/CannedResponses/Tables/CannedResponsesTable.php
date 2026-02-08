<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\CannedResponses\Tables;

use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class CannedResponsesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-service-desk::service-desk.fields.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label(__('filament-service-desk::service-desk.fields.department'))
                    ->placeholder(__('filament-service-desk::service-desk.fields.all_departments')),
                Tables\Columns\TextColumn::make('body')
                    ->label(__('filament-service-desk::service-desk.fields.body'))
                    ->html()
                    ->limit(100),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->label(__('filament-service-desk::service-desk.fields.department'))
                    ->relationship('department', 'name'),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
            ]);
    }
}
