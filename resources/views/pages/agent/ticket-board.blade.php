@php
    use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;
    use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;

    $priorityColor = fn (TicketPriority $priority) => match ($priority) {
        TicketPriority::Low => 'gray',
        TicketPriority::Medium => 'info',
        TicketPriority::High => 'warning',
        TicketPriority::Urgent => 'danger',
    };
@endphp

<x-filament-panels::page>
    <div class="flex gap-4 overflow-x-auto pb-4" x-data="{ draggingOver: null }">
        @foreach ($this->getColumns() as $statusValue => $tickets)
            @php $status = TicketStatus::from($statusValue); @endphp

            <div
                class="flex w-72 shrink-0 flex-col rounded-xl bg-gray-50 dark:bg-gray-800"
                x-on:dragover.prevent="draggingOver = '{{ $statusValue }}'"
                x-on:dragleave="draggingOver = null"
                x-on:drop.prevent="
                    draggingOver = null;
                    $wire.moveTicket(parseInt($event.dataTransfer.getData('ticketId')), '{{ $statusValue }}');
                "
                :class="draggingOver === '{{ $statusValue }}' ? 'ring-2 ring-primary-500' : ''"
            >
                <div class="flex items-center justify-between px-3 py-2">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $status->label() }}</span>
                    <span class="rounded-full bg-gray-200 px-2 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ $tickets->count() }}</span>
                </div>

                <div class="flex flex-col gap-2 px-2 pb-2">
                    @forelse ($tickets as $ticket)
                        <div
                            draggable="true"
                            x-on:dragstart="$event.dataTransfer.setData('ticketId', '{{ $ticket->id }}')"
                            class="cursor-grab rounded-lg bg-white p-3 shadow-sm ring-1 ring-gray-950/5 active:cursor-grabbing dark:bg-gray-900 dark:ring-white/10"
                        >
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $ticket->reference_number }}</span>
                                <x-filament::badge :color="$priorityColor($ticket->priority)" size="xs">
                                    {{ $ticket->priority->label() }}
                                </x-filament::badge>
                            </div>
                            <p class="mt-1 line-clamp-2 text-sm text-gray-950 dark:text-white">{{ $ticket->title }}</p>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                {{ $ticket->assignedTo?->name ?? __('filament-service-desk::service-desk.fields.unassigned') }}
                            </p>
                        </div>
                    @empty
                        <p class="px-1 py-4 text-center text-xs text-gray-400 dark:text-gray-500">
                            {{ __('filament-service-desk::service-desk.empty_states.queue.heading') }}
                        </p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
