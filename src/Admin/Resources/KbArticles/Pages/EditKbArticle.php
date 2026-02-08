<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticles\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticles\KbArticleResource;
use JeffersonGoncalves\ServiceDesk\Services\KnowledgeBaseService;

class EditKbArticle extends EditRecord
{
    protected static string $resource = KbArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('publish')
                ->label(__('filament-service-desk::service-desk.actions.publish'))
                ->icon(Heroicon::GlobeAlt)
                ->color('success')
                ->action(fn () => app(KnowledgeBaseService::class)->publishArticle($this->record))
                ->visible(fn () => $this->record->status !== \JeffersonGoncalves\ServiceDesk\Enums\ArticleStatus::Published),
            Actions\Action::make('archive')
                ->label(__('filament-service-desk::service-desk.actions.archive'))
                ->icon(Heroicon::ArchiveBox)
                ->color('warning')
                ->action(fn () => app(KnowledgeBaseService::class)->archiveArticle($this->record))
                ->visible(fn () => $this->record->status === \JeffersonGoncalves\ServiceDesk\Enums\ArticleStatus::Published),
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        return app(KnowledgeBaseService::class)->updateArticle($record, $data, auth()->user());
    }
}
