<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\ServiceCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\ServiceCategoryResource;

class EditServiceCategory extends EditRecord
{
    protected static string $resource = ServiceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
