<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Tests\Fixtures;

use Filament\Http\Middleware\Authenticate;
use Filament\Panel;
use Filament\PanelProvider;
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskAdminPlugin;

class TestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('test')
            ->path('test')
            ->login()
            ->plugins([
                ServiceDeskAdminPlugin::make(),
            ])
            ->middleware([
                'web',
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
