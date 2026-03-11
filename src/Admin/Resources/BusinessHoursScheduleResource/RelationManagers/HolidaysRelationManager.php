<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursScheduleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class HolidaysRelationManager extends RelationManager
{
    protected static string $relationship = 'holidays';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.holidays');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('filament-service-desk::service-desk.fields.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->label(__('filament-service-desk::service-desk.fields.date'))
                    ->required(),
                Forms\Components\Toggle::make('is_recurring')
                    ->label(__('filament-service-desk::service-desk.fields.is_recurring'))
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-service-desk::service-desk.fields.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('filament-service-desk::service-desk.fields.date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_recurring')
                    ->label(__('filament-service-desk::service-desk.fields.is_recurring'))
                    ->boolean(),
            ])
            ->defaultSort('date')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
