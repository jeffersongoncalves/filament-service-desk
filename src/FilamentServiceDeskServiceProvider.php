<?php

namespace JeffersonGoncalves\FilamentServiceDesk;

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
}
