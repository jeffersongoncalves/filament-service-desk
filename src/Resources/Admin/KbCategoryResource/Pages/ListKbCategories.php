<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\KbCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\KbCategoryResource;

class ListKbCategories extends ListRecords
{
    protected static string $resource = KbCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
