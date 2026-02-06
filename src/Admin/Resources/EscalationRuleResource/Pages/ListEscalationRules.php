<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRuleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRuleResource;

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
