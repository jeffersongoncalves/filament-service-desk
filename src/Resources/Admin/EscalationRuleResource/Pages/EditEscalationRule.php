<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\EscalationRuleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentServiceDesk\Resources\Admin\EscalationRuleResource;

class EditEscalationRule extends EditRecord
{
    protected static string $resource = EscalationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
