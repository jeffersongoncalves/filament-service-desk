@if ($articles->isNotEmpty())
    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-white/10 dark:bg-white/5">
        <p class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
            {{ __('filament-service-desk::service-desk.fields.suggested_articles') }}
        </p>
        <ul class="space-y-1">
            @foreach ($articles as $article)
                <li class="text-sm text-gray-700 dark:text-gray-300">
                    {{ $article->title }}
                </li>
            @endforeach
        </ul>
    </div>
@endif
