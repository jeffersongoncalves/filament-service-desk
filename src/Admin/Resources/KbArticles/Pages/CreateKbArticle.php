<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticles\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticles\KbArticleResource;
use JeffersonGoncalves\ServiceDesk\Services\KnowledgeBaseService;

class CreateKbArticle extends CreateRecord
{
    protected static string $resource = KbArticleResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        return app(KnowledgeBaseService::class)->createArticle($data, auth()->user());
    }
}
