<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticles\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class VersionsRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.versions');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('version_number')
            ->columns([
                Tables\Columns\TextColumn::make('version_number')
                    ->label(__('filament-service-desk::service-desk.fields.version_number'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-service-desk::service-desk.fields.title'))
                    ->limit(50),
                Tables\Columns\TextColumn::make('editor.name')
                    ->label(__('filament-service-desk::service-desk.fields.editor'))
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('change_notes')
                    ->label(__('filament-service-desk::service-desk.fields.change_notes'))
                    ->limit(80)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('version_number', 'desc');
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
