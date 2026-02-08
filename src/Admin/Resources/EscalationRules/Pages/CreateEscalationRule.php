<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRules\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRules\EscalationRuleResource;

class CreateEscalationRule extends CreateRecord
{
    protected static string $resource = EscalationRuleResource::class;
}
