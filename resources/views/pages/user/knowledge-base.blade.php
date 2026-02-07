<x-filament-panels::page>
    <div class="fi-sd-kb">
        {{-- Search --}}
        <div>
            <x-filament::input.wrapper>
                <x-filament::input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    :placeholder="__('filament-service-desk::service-desk.pages.knowledge_base.search_placeholder')"
                />
            </x-filament::input.wrapper>
        </div>

        @if ($articleId && $article = $this->getArticle())
            {{-- Article Detail --}}
            <div>
                <x-filament::button
                    wire:click="backToList"
                    :icon="\Filament\Support\Icons\Heroicon::ArrowLeft"
                    color="gray"
                    size="sm"
                >
                    {{ __('filament-service-desk::service-desk.pages.knowledge_base.back') }}
                </x-filament::button>
            </div>

            <x-filament::section>
                <x-slot name="heading">
                    {{ $article->title }}
                </x-slot>

                <div class="fi-sd-kb-content prose dark:prose-invert">
                    {!! $article->content !!}
                </div>

                <x-slot name="footerActions">
                    <div class="fi-sd-kb-feedback">
                        <span class="fi-sd-kb-text-muted">
                            {{ __('filament-service-desk::service-desk.pages.knowledge_base.was_helpful') }}
                        </span>
                        <x-filament::button
                            wire:click="submitFeedback({{ $article->id }}, true)"
                            :icon="\Filament\Support\Icons\Heroicon::HandThumbUp"
                            color="success"
                            size="sm"
                        >
                            {{ __('filament-service-desk::service-desk.pages.knowledge_base.yes') }}
                        </x-filament::button>
                        <x-filament::button
                            wire:click="submitFeedback({{ $article->id }}, false)"
                            :icon="\Filament\Support\Icons\Heroicon::HandThumbDown"
                            color="danger"
                            size="sm"
                        >
                            {{ __('filament-service-desk::service-desk.pages.knowledge_base.no') }}
                        </x-filament::button>
                    </div>
                </x-slot>
            </x-filament::section>
        @else
            {{-- Categories --}}
            @unless($search)
                <div class="fi-sd-kb-categories-grid">
                    @foreach ($this->getCategories() as $category)
                        <x-filament::section class="fi-sd-kb-card-clickable" wire:click="selectCategory({{ $category->id }})">
                            <x-slot name="heading">
                                {{ $category->name }}
                            </x-slot>

                            @if ($category->description)
                                <p class="fi-sd-kb-text-muted">{{ $category->description }}</p>
                            @endif

                            <p class="fi-sd-kb-text-subtle">
                                {{ trans_choice('filament-service-desk::service-desk.pages.knowledge_base.articles_count', $category->articles_count) }}
                            </p>
                        </x-filament::section>
                    @endforeach
                </div>
            @endunless

            {{-- Articles List --}}
            <div class="fi-sd-kb-articles-list">
                @forelse ($this->getArticles() as $article)
                    <x-filament::section class="fi-sd-kb-card-clickable" wire:click="selectArticle({{ $article->id }})">
                        <x-slot name="heading">
                            {{ $article->title }}
                        </x-slot>

                        @if ($article->excerpt)
                            <p class="fi-sd-kb-text-muted">{{ $article->excerpt }}</p>
                        @endif
                    </x-filament::section>
                @empty
                    <div class="fi-sd-kb-empty">
                        {{ __('filament-service-desk::service-desk.pages.knowledge_base.no_articles') }}
                    </div>
                @endforelse
            </div>

            @if ($categoryId)
                <div>
                    <x-filament::button
                        wire:click="selectCategory(null)"
                        :icon="\Filament\Support\Icons\Heroicon::ArrowLeft"
                        color="gray"
                        size="sm"
                    >
                        {{ __('filament-service-desk::service-desk.pages.knowledge_base.all_categories') }}
                    </x-filament::button>
                </div>
            @endif
        @endif
    </div>
</x-filament-panels::page>
