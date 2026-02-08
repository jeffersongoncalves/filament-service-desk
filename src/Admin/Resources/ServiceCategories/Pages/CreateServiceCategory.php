<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\ServiceCategories\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\ServiceCategories\ServiceCategoryResource;

class CreateServiceCategory extends CreateRecord
{
    protected static string $resource = ServiceCategoryResource::class;
}
