<?php

use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\AnonymousNotifiable;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\DepartmentResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\DepartmentResource\RelationManagers\OperatorsRelationManager;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\TicketResource as AgentTicketResource;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource as UserTicketResource;
use JeffersonGoncalves\ServiceDesk\Models\Department;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;

it('links requesters to the user panel and operators to the agent panel', function () {
    $ticket = (new Ticket)->forceFill([
        'uuid' => 'a7b1c2d3-0000-4000-8000-000000000001',
        'user_type' => 'requester',
        'user_id' => 1,
    ]);

    $requester = new AnonymousNotifiable;
    $operator = (object) [];

    expect($ticket->notificationUrl($requester))
        ->toBe(UserTicketResource::getUrl('view', ['record' => $ticket], panel: 'user'))
        ->toContain($ticket->uuid)
        ->and($ticket->notificationUrl($operator))
        ->toBe(AgentTicketResource::getUrl('view', ['record' => $ticket], panel: 'agent'));
});

it('gives departments an operators relation with the email opt-out on the pivot', function () {
    config()->set('service-desk.models.operator', User::class);

    $relation = (new Department)->operators();

    expect($relation->getTable())->toBe('service_desk_department_operator')
        ->and($relation->getPivotColumns())->toContain('role', 'email_notifications')
        ->and(DepartmentResource::getRelations())->toContain(OperatorsRelationManager::class);
});
