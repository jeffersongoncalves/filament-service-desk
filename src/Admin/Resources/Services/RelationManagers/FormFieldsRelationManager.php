<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Services\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\ServiceDesk\Enums\FormFieldType;

class FormFieldsRelationManager extends RelationManager
{
    protected static string $relationship = 'formFields';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.form_fields');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('filament-service-desk::service-desk.fields.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('label')
                    ->label(__('filament-service-desk::service-desk.fields.label'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label(__('filament-service-desk::service-desk.fields.type'))
                    ->options(collect(FormFieldType::cases())->mapWithKeys(fn ($t) => [$t->value => $t->name]))
                    ->required()
                    ->live(),
                Forms\Components\Toggle::make('is_required')
                    ->label(__('filament-service-desk::service-desk.fields.is_required'))
                    ->default(false),
                Forms\Components\KeyValue::make('options')
                    ->label(__('filament-service-desk::service-desk.fields.options'))
                    ->visible(fn (Schemas\Components\Utilities\Get $get) => in_array($get('type'), [
                        FormFieldType::Select->value,
                        FormFieldType::Radio->value,
                        FormFieldType::Checkbox->value,
                    ]))
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('placeholder')
                    ->label(__('filament-service-desk::service-desk.fields.placeholder'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('help_text')
                    ->label(__('filament-service-desk::service-desk.fields.help_text'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('default_value')
                    ->label(__('filament-service-desk::service-desk.fields.default_value'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('sort_order')
                    ->label(__('filament-service-desk::service-desk.fields.sort_order'))
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label(__('filament-service-desk::service-desk.fields.label'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-service-desk::service-desk.fields.name')),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament-service-desk::service-desk.fields.type'))
                    ->badge(),
                Tables\Columns\IconColumn::make('is_required')
                    ->label(__('filament-service-desk::service-desk.fields.is_required'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('filament-service-desk::service-desk.fields.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }
}
