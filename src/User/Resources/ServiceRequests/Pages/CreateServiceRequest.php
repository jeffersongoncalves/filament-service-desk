<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequests\Pages;

use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequests\ServiceRequestResource;
use JeffersonGoncalves\ServiceDesk\Enums\FormFieldType;
use JeffersonGoncalves\ServiceDesk\Models\Service;
use JeffersonGoncalves\ServiceDesk\Services\ServiceRequestService;

class CreateServiceRequest extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = ServiceRequestResource::class;

    public function form(Schema $schema): Schema
    {
        return parent::form($schema)
            ->schema([
                Wizard::make([
                    Wizard\Step::make(__('filament-service-desk::service-desk.wizard.select_service'))
                        ->icon(Heroicon::Squares2x2)
                        ->schema([
                            Forms\Components\Select::make('service_id')
                                ->label(__('filament-service-desk::service-desk.fields.service'))
                                ->options(
                                    Service::query()
                                        ->where('is_active', true)
                                        ->pluck('name', 'id')
                                )
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(fn (Schemas\Components\Utilities\Set $set) => $set('form_data', [])),
                            Infolists\Components\TextEntry::make('service_description')
                                ->label(__('filament-service-desk::service-desk.fields.description'))
                                ->state(function (Schemas\Components\Utilities\Get $get) {
                                    $service = Service::find($get('service_id'));

                                    return $service?->description ?? '';
                                })
                                ->visible(fn (Schemas\Components\Utilities\Get $get) => filled($get('service_id'))),
                        ]),

                    Wizard\Step::make(__('filament-service-desk::service-desk.wizard.fill_form'))
                        ->icon(Heroicon::DocumentText)
                        ->schema(function (Schemas\Components\Utilities\Get $get): array {
                            $service = Service::find($get('service_id'));

                            if (! $service) {
                                return [
                                    Infolists\Components\TextEntry::make('no_service')
                                        ->state(__('filament-service-desk::service-desk.wizard.select_service_first')),
                                ];
                            }

                            return $service->formFields()
                                ->ordered()
                                ->get()
                                ->map(fn ($field) => self::mapFormField($field))
                                ->filter()
                                ->all();
                        }),

                    Wizard\Step::make(__('filament-service-desk::service-desk.wizard.review'))
                        ->icon(Heroicon::CheckCircle)
                        ->schema([
                            Infolists\Components\TextEntry::make('review_service')
                                ->label(__('filament-service-desk::service-desk.fields.service'))
                                ->state(fn (Schemas\Components\Utilities\Get $get) => Service::find($get('service_id'))?->name ?? '—'),
                            Forms\Components\Textarea::make('notes')
                                ->label(__('filament-service-desk::service-desk.fields.notes'))
                                ->maxLength(65535),
                        ]),
                ])
                    ->columnSpanFull(),
            ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $service = Service::findOrFail($data['service_id']);

        return app(ServiceRequestService::class)->create(
            $service,
            auth()->user(),
            $data['form_data'] ?? [],
            $data['notes'] ?? null,
        );
    }

    protected static function mapFormField($field): ?Forms\Components\Component
    {
        $component = match ($field->type) {
            FormFieldType::Text => Forms\Components\TextInput::make("form_data.{$field->name}"),
            FormFieldType::Textarea => Forms\Components\Textarea::make("form_data.{$field->name}"),
            FormFieldType::Select => Forms\Components\Select::make("form_data.{$field->name}")
                ->options($field->options ?? []),
            FormFieldType::Checkbox => Forms\Components\Checkbox::make("form_data.{$field->name}"),
            FormFieldType::Radio => Forms\Components\Radio::make("form_data.{$field->name}")
                ->options($field->options ?? []),
            FormFieldType::Date => Forms\Components\DatePicker::make("form_data.{$field->name}"),
            FormFieldType::DateTime => Forms\Components\DateTimePicker::make("form_data.{$field->name}"),
            FormFieldType::File => Forms\Components\FileUpload::make("form_data.{$field->name}"),
            FormFieldType::Number => Forms\Components\TextInput::make("form_data.{$field->name}")->numeric(),
            FormFieldType::Email => Forms\Components\TextInput::make("form_data.{$field->name}")->email(),
            FormFieldType::Url => Forms\Components\TextInput::make("form_data.{$field->name}")->url(),
            FormFieldType::Tel => Forms\Components\TextInput::make("form_data.{$field->name}")->tel(),
            FormFieldType::Toggle => Forms\Components\Toggle::make("form_data.{$field->name}"),
            default => null,
        };

        if (! $component) {
            return null;
        }

        $component->label($field->label);

        if ($field->is_required) {
            $component->required();
        }

        if ($field->placeholder) {
            $component->placeholder($field->placeholder);
        }

        if ($field->help_text) {
            $component->helperText($field->help_text);
        }

        if ($field->default_value) {
            $component->default($field->default_value);
        }

        return $component;
    }
}
