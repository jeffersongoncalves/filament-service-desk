<?php

use JeffersonGoncalves\ServiceDesk\Enums\TicketStatus;

it('renders the status stepper from the entry state', function () {
    $html = view('filament-service-desk::components.ticket-status-stepper', [
        'getState' => fn () => TicketStatus::Open,
    ])->render();

    foreach (TicketStatus::pipelineSteps() as $step) {
        expect($html)->toContain($step->label());
    }
});

it('renders the waiting badge for on hold tickets', function () {
    $html = view('filament-service-desk::components.ticket-status-stepper', [
        'getState' => fn () => TicketStatus::OnHold,
    ])->render();

    expect($html)->toContain(TicketStatus::OnHold->label());
});
