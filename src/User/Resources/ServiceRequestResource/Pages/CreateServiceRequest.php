<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequestResource\Pages;

use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequestResource;
use JeffersonGoncalves\ServiceDesk\Enums\FormFieldType;
use JeffersonGoncalves\ServiceDesk\Models\Service;
use JeffersonGoncalves\ServiceDesk\Services\ServiceRequestService;

class CreateServiceRequest extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = ServiceRequestResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make([
                    Wizard\Step::make(__('filament-service-desk::service-desk.wizard.select_service'))
                        ->icon('heroicon-o-squares-2x2')
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
                                ->reactive()
                                ->afterStateUpdated(fn (Forms\Set $set) => $set('form_data', [])),
                            Forms\Components\Placeholder::make('service_description')
                                ->label(__('filament-service-desk::service-desk.fields.description'))
                                ->content(function (Forms\Get $get) {
                                    $service = Service::find($get('service_id')); // @phpstan-ignore staticMethod.notFound

                                    return $service?->description ?? '';
                                })
                                ->visible(fn (Forms\Get $get) => filled($get('service_id'))),
                        ]),

                    Wizard\Step::make(__('filament-service-desk::service-desk.wizard.fill_form'))
                        ->icon('heroicon-o-document-text')
                        ->schema(function (Forms\Get $get): array {
                            $service = Service::find($get('service_id')); // @phpstan-ignore staticMethod.notFound

                            if (! $service) {
                                return [
                                    Forms\Components\Placeholder::make('no_service')
                                        ->content(__('filament-service-desk::service-desk.wizard.select_service_first')),
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
                        ->icon('heroicon-o-check-circle')
                        ->schema([
                            Forms\Components\Placeholder::make('review_service')
                                ->label(__('filament-service-desk::service-desk.fields.service'))
                                ->content(fn (Forms\Get $get) => Service::find($get('service_id'))?->name ?? '—'), // @phpstan-ignore staticMethod.notFound
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
        $service = Service::findOrFail($data['service_id']); // @phpstan-ignore staticMethod.notFound

        return app(ServiceRequestService::class)->create(
            $service,
            auth()->guard()->user(),
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
