<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;

function satelliteUser(): Model
{
    $user = new class extends Model
    {
        protected $table = 'users';

        protected $guarded = [];
    };

    $user->id = 1;
    $user->name = 'Jane Requester';
    $user->email = 'jane@example.com';

    return $user;
}

beforeEach(function () {
    config()->set('service-desk.ticket.transport', 'api');
    config()->set('service-desk.api.url', 'https://central.test');
    config()->set('service-desk.api.app_key', 'satellite-key');
    config()->set('service-desk.api.secret', 'satellite-secret');
});

it('creates a ticket through the api transport', function () {
    Http::fake([
        'central.test/*' => Http::response([
            'data' => [
                'uuid' => 'abc-123',
                'reference_number' => 'SD-00001',
                'title' => 'Cannot log in',
                'status' => 'open',
                'priority' => 'medium',
            ],
        ], 201),
    ]);

    $ticket = app(TicketService::class)->create([
        'title' => 'Cannot log in',
        'description' => 'Nothing happens when I click sign in.',
    ], satelliteUser());

    expect($ticket->uuid)->toBe('abc-123')
        ->and($ticket->reference_number)->toBe('SD-00001')
        ->and($ticket->exists)->toBeTrue();

    Http::assertSent(fn ($request) => $request->url() === 'https://central.test/tickets');
});

it('views a ticket by uuid through the api transport', function () {
    Http::fake([
        'central.test/tickets/abc-123' => Http::response([
            'data' => [
                'uuid' => 'abc-123',
                'reference_number' => 'SD-00001',
                'title' => 'Cannot log in',
                'status' => 'open',
                'priority' => 'medium',
                'requester_name' => 'Jane Requester',
                'requester_email' => 'jane@example.com',
            ],
        ]),
    ]);

    $ticket = app(TicketService::class)->findByUuid('abc-123');

    expect($ticket->uuid)->toBe('abc-123')
        ->and($ticket->user_name)->toBe('Jane Requester')
        ->and($ticket->user_email)->toBe('jane@example.com');
});
