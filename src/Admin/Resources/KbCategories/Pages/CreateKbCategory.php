<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbCategories\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbCategories\KbCategoryResource;

class CreateKbCategory extends CreateRecord
{
    protected static string $resource = KbCategoryResource::class;
}
