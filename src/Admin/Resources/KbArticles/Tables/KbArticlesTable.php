<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticles\Tables;

use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\ServiceDesk\Enums\ArticleStatus;
use JeffersonGoncalves\ServiceDesk\Enums\ArticleVisibility;

class KbArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-service-desk::service-desk.fields.title'))
                    ->searchable()
                    ->sortable()
                    ->limit(60),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('filament-service-desk::service-desk.fields.category'))
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament-service-desk::service-desk.fields.status'))
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        ArticleStatus::Draft, 'draft' => 'gray',
                        ArticleStatus::Published, 'published' => 'success',
                        ArticleStatus::Archived, 'archived' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('visibility')
                    ->label(__('filament-service-desk::service-desk.fields.visibility'))
                    ->badge(),
                Tables\Columns\TextColumn::make('view_count')
                    ->label(__('filament-service-desk::service-desk.fields.view_count'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label(__('filament-service-desk::service-desk.fields.published_at'))
                    ->dateTime()
                    ->sortable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament-service-desk::service-desk.fields.status'))
                    ->options(collect(ArticleStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->name])),
                Tables\Filters\SelectFilter::make('visibility')
                    ->label(__('filament-service-desk::service-desk.fields.visibility'))
                    ->options(collect(ArticleVisibility::cases())->mapWithKeys(fn ($v) => [$v->value => $v->name])),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label(__('filament-service-desk::service-desk.fields.category'))
                    ->relationship('category', 'name'),
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
