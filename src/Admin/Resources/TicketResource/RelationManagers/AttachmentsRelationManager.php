<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\TicketResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\ServiceDesk\Services\AttachmentService;
use Symfony\Component\Mime\MimeTypes;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.attachments');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('file')
                    ->label(__('filament-service-desk::service-desk.fields.file'))
                    ->required()
                    ->maxSize(config('filament-service-desk.attachments.max_file_size', 10240))
                    ->acceptedFileTypes(
                        collect(config('filament-service-desk.attachments.allowed_extensions', []))
                            ->flatMap(fn (string $ext): array => MimeTypes::getDefault()->getMimeTypes($ext))
                            ->unique()
                            ->values()
                            ->toArray()
                    )
                    ->disk(config('filament-service-desk.attachments.disk', 'local'))
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label(__('filament-service-desk::service-desk.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => $record->getUrl())
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        app(AttachmentService::class)->delete($record, auth()->guard()->user());
                    }),
            ]);
    }
}
