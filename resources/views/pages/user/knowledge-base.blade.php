<x-filament-panels::page>
    <div class="space-y-6">
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

                <div class="prose dark:prose-invert max-w-none">
                    {!! $article->content !!}
                </div>

                <x-slot name="footerActions">
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500">
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
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($this->getCategories() as $category)
                        <x-filament::section class="cursor-pointer" wire:click="selectCategory({{ $category->id }})">
                            <x-slot name="heading">
                                {{ $category->name }}
                            </x-slot>

                            @if ($category->description)
                                <p class="text-sm text-gray-500">{{ $category->description }}</p>
                            @endif

                            <p class="text-xs text-gray-400 mt-2">
                                {{ trans_choice('filament-service-desk::service-desk.pages.knowledge_base.articles_count', $category->articles_count) }}
                            </p>
                        </x-filament::section>
                    @endforeach
                </div>
            @endunless

            {{-- Articles List --}}
            <div class="space-y-2">
                @forelse ($this->getArticles() as $article)
                    <x-filament::section class="cursor-pointer" wire:click="selectArticle({{ $article->id }})">
                        <x-slot name="heading">
                            {{ $article->title }}
                        </x-slot>

                        @if ($article->excerpt)
                            <p class="text-sm text-gray-500">{{ $article->excerpt }}</p>
                        @endif
                    </x-filament::section>
                @empty
                    <div class="text-center py-8 text-gray-500">
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
