<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\ServiceCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\ServiceCategoryResource;

class ListServiceCategories extends ListRecords
{
    protected static string $resource = ServiceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
