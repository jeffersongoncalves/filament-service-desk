@php
    use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;

    /** @var TicketStatus $state */
    $steps = TicketStatus::pipelineSteps();
    $currentStep = $state->pipelineStep();
    $isWaiting = in_array($state, [TicketStatus::Pending, TicketStatus::OnHold], true);
@endphp

<div class="flex items-center gap-2">
    @foreach ($steps as $index => $step)
        @php
            $stepNumber = $index + 1;
            $isComplete = $stepNumber < $currentStep;
            $isCurrent = $stepNumber === $currentStep;
        @endphp

        <div class="flex items-center gap-2 {{ $loop->last ? '' : 'flex-1' }}">
            <div
                @class([
                    'flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-semibold',
                    'bg-primary-600 text-white' => $isComplete,
                    'bg-primary-100 text-primary-700 ring-2 ring-primary-600 dark:bg-primary-500/20 dark:text-primary-400' => $isCurrent,
                    'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' => ! $isComplete && ! $isCurrent,
                ])
            >
                @if ($isComplete)
                    <x-heroicon-s-check class="h-4 w-4" />
                @else
                    {{ $stepNumber }}
                @endif
            </div>

            <span
                @class([
                    'text-sm',
                    'font-semibold text-gray-950 dark:text-white' => $isCurrent,
                    'text-gray-700 dark:text-gray-300' => $isComplete,
                    'text-gray-400 dark:text-gray-500' => ! $isComplete && ! $isCurrent,
                ])
            >
                {{ $step->label() }}
            </span>

            @unless ($loop->last)
                <div @class([
                    'h-0.5 flex-1',
                    'bg-primary-600' => $isComplete,
                    'bg-gray-200 dark:bg-gray-700' => ! $isComplete,
                ])></div>
            @endunless
        </div>
    @endforeach

    @if ($isWaiting)
        <span class="ms-2 shrink-0 rounded-full bg-warning-100 px-2 py-1 text-xs font-medium text-warning-700 dark:bg-warning-500/20 dark:text-warning-400">
            {{ $state->label() }}
        </span>
    @endif
</div>
