<?php

namespace JeffersonGoncalves\FilamentServiceDesk;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentServiceDeskServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-service-desk';

    public static string $viewNamespace = 'filament-service-desk';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews(static::$viewNamespace)
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('filament-service-desk', __DIR__.'/../resources/dist/filament-service-desk.css'),
        ], 'jeffersongoncalves/filament-service-desk');
    }
}
