<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Agent\TicketResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.attachments');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_name')
            ->columns([
                Tables\Columns\TextColumn::make('file_name')
                    ->label(__('filament-service-desk::service-desk.fields.file_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('mime_type')
                    ->label(__('filament-service-desk::service-desk.fields.mime_type')),
                Tables\Columns\TextColumn::make('file_size')
                    ->label(__('filament-service-desk::service-desk.fields.file_size'))
                    ->formatStateUsing(fn ($state) => number_format($state / 1024, 2).' KB'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label(__('filament-service-desk::service-desk.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => $record->getUrl())
                    ->openUrlInNewTab(),
            ]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
