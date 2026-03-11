<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use JeffersonGoncalves\ServiceDesk\Models\KbArticle;
use JeffersonGoncalves\ServiceDesk\Models\KbCategory;
use JeffersonGoncalves\ServiceDesk\Services\KnowledgeBaseService;
use Livewire\Attributes\Url;

class KnowledgeBasePage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament-service-desk::pages.user.knowledge-base';

    #[Url]
    public ?string $search = null;

    #[Url]
    public ?int $categoryId = null;

    #[Url]
    public ?int $articleId = null;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.user.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-service-desk::service-desk.pages.knowledge_base.label');
    }

    public function getTitle(): string
    {
        return __('filament-service-desk::service-desk.pages.knowledge_base.title');
    }

    public function getCategories()
    {
        /** @phpstan-ignore-next-line */
        return KbCategory::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->withCount(['articles' => fn ($q) => $q->where('status', 'published')])
            ->ordered()
            ->get();
    }

    public function getArticles()
    {
        $query = KbArticle::query()
            ->where('status', 'published')
            ->where('visibility', 'public');

        if ($this->search) {
            return app(KnowledgeBaseService::class)->search($this->search, [
                'visibility' => 'public',
            ]);
        }

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        return $query->latest('published_at')->limit(20)->get();
    }

    public function getArticle()
    {
        if (! $this->articleId) {
            return null;
        }

        $article = KbArticle::query()
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->find($this->articleId);

        if ($article) {
            $article->incrementViewCount();
        }

        return $article;
    }

    public function selectCategory(?int $categoryId): void
    {
        $this->categoryId = $categoryId;
        $this->articleId = null;
    }

    public function selectArticle(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    public function backToList(): void
    {
        $this->articleId = null;
    }

    public function submitFeedback(int $articleId, bool $isHelpful): void
    {
        /** @phpstan-ignore staticMethod.notFound */
        $article = KbArticle::findOrFail($articleId);
        app(KnowledgeBaseService::class)->addFeedback(
            $article,
            $isHelpful,
            auth()->guard()->user(),
            null,
            request()->ip(),
        );
    }
}
