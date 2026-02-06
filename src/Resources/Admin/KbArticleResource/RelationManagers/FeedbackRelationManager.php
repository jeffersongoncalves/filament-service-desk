<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\KbArticleResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FeedbackRelationManager extends RelationManager
{
    protected static string $relationship = 'feedback';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.feedback');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('is_helpful')
            ->columns([
                Tables\Columns\IconColumn::make('is_helpful')
                    ->label(__('filament-service-desk::service-desk.fields.is_helpful'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament-service-desk::service-desk.fields.user'))
                    ->placeholder(__('filament-service-desk::service-desk.fields.anonymous')),
                Tables\Columns\TextColumn::make('comment')
                    ->label(__('filament-service-desk::service-desk.fields.comment'))
                    ->limit(100)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
