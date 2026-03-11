<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticleResource;
use JeffersonGoncalves\ServiceDesk\Enums\ArticleStatus;
use JeffersonGoncalves\ServiceDesk\Services\KnowledgeBaseService;

class EditKbArticle extends EditRecord
{
    protected static string $resource = KbArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('publish')
                ->label(__('filament-service-desk::service-desk.actions.publish'))
                ->icon('heroicon-o-globe-alt')
                ->color('success')
                ->action(fn () => app(KnowledgeBaseService::class)->publishArticle($this->record))
                ->visible(fn () => $this->record->status !== ArticleStatus::Published), // @phpstan-ignore property.nonObject
            Actions\Action::make('archive')
                ->label(__('filament-service-desk::service-desk.actions.archive'))
                ->icon('heroicon-o-archive-box')
                ->color('warning')
                ->action(fn () => app(KnowledgeBaseService::class)->archiveArticle($this->record))
                ->visible(fn () => $this->record->status === ArticleStatus::Published), // @phpstan-ignore property.nonObject
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(KnowledgeBaseService::class)->updateArticle($record, $data, auth()->guard()->user());
    }
}
