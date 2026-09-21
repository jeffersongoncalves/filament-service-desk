<?php

use Filament\Panel;
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskAdminPlugin;
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskAgentPlugin;
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskUserPlugin;
use JeffersonGoncalves\FilamentServiceDesk\User\Widgets\MyTicketsOverviewWidget;

it('can create admin plugin', function () {
    $plugin = ServiceDeskAdminPlugin::make();

    expect($plugin->getId())->toBe('filament-service-desk-admin');
});

it('can create agent plugin', function () {
    $plugin = ServiceDeskAgentPlugin::make();

    expect($plugin->getId())->toBe('filament-service-desk-agent');
});

it('can create user plugin', function () {
    $plugin = ServiceDeskUserPlugin::make();

    expect($plugin->getId())->toBe('filament-service-desk-user');
});

it('can toggle features on admin plugin', function () {
    $plugin = ServiceDeskAdminPlugin::make()
        ->knowledgeBase(false)
        ->sla(false)
        ->emailChannels(false)
        ->serviceCatalog(false);

    expect($plugin->hasKnowledgeBase())->toBeFalse()
        ->and($plugin->hasSla())->toBeFalse()
        ->and($plugin->hasEmailChannels())->toBeFalse()
        ->and($plugin->hasServiceCatalog())->toBeFalse();
});

it('can set navigation group', function () {
    $plugin = ServiceDeskAdminPlugin::make()
        ->navigationGroup('Custom Group');

    expect($plugin->getNavigationGroup())->toBe('Custom Group');
});

it('refuses to register the admin panel under the api ticket transport', function () {
    config()->set('service-desk.ticket.transport', 'api');

    ServiceDeskAdminPlugin::make()->register(Panel::make());
})->throws(RuntimeException::class, 'service-desk.ticket.transport=api');

it('refuses to register the agent panel under the api ticket transport', function () {
    config()->set('service-desk.ticket.transport', 'api');

    ServiceDeskAgentPlugin::make()->register(Panel::make());
})->throws(RuntimeException::class, 'service-desk.ticket.transport=api');

it('registers the user panel under the api ticket transport without the stats widget', function () {
    config()->set('service-desk.ticket.transport', 'api');

    $panel = Panel::make()->id('user');

    ServiceDeskUserPlugin::make()->register($panel);

    expect($panel->getWidgets())->not->toContain(MyTicketsOverviewWidget::class);
});
