<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskUserPlugin;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('user')
            ->path('support')
            ->plugins([ServiceDeskUserPlugin::make()]);
    }
}
