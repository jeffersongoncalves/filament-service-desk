<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRules\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRules\EscalationRuleResource;

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
