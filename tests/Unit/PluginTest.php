<?php

use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskAdminPlugin;
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskAgentPlugin;
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskUserPlugin;

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
