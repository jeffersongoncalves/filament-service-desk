<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Concerns;

trait InteractsWithTicketApiTransport
{
    public static function isTicketApiTransport(): bool
    {
        return config('service-desk.ticket.transport') === 'api';
    }
}
