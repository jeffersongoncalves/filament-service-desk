@if ($attachments->isEmpty())
    <p class="text-sm text-gray-500 dark:text-gray-400">
        {{ __('filament-service-desk::service-desk.relations.attachments') }}: —
    </p>
@else
    <ul class="space-y-1">
        @foreach ($attachments as $attachment)
            <li class="flex items-center justify-between gap-2 text-sm">
                <span class="text-gray-700 dark:text-gray-300">{{ $attachment['file_name'] }}</span>
                <a
                    href="{{ $attachment['dataUri'] }}"
                    download="{{ $attachment['file_name'] }}"
                    class="text-primary-600 hover:underline dark:text-primary-400"
                >
                    {{ __('filament-service-desk::service-desk.actions.download') }}
                </a>
            </li>
        @endforeach
    </ul>
@endif
