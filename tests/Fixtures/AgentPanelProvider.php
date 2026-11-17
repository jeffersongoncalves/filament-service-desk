<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskAgentPlugin;

class AgentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('agent')
            ->path('agent')
            ->plugins([ServiceDeskAgentPlugin::make()]);
    }
}
