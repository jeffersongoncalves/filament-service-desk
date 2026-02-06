<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\EscalationRuleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\EscalationRuleResource;

class ListEscalationRules extends ListRecords
{
    protected static string $resource = EscalationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
