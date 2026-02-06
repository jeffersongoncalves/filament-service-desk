<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRuleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRuleResource;

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
