<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tickets\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\ServiceDesk\Services\AttachmentService;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.attachments');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(null)
            ->schema([
                Forms\Components\FileUpload::make('file')
                    ->label(__('filament-service-desk::service-desk.fields.file'))
                    ->required()
                    ->columnSpanFull(),
            ]);
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
                Tables\Columns\TextColumn::make('uploadedBy.name')
                    ->label(__('filament-service-desk::service-desk.fields.uploaded_by')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->headerActions([])
            ->recordActions([
                Actions\Action::make('download')
                    ->label(__('filament-service-desk::service-desk.actions.download'))
                    ->icon(Heroicon::ArrowDownTray)
                    ->url(fn ($record) => $record->getUrl())
                    ->openUrlInNewTab(),
                Actions\DeleteAction::make()
                    ->action(function ($record) {
                        app(AttachmentService::class)->delete($record, auth()->user());
                    }),
            ]);
    }
}
