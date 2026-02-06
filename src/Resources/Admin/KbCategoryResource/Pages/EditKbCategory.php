<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\KbCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\KbCategoryResource;

class EditKbCategory extends EditRecord
{
    protected static string $resource = KbCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
