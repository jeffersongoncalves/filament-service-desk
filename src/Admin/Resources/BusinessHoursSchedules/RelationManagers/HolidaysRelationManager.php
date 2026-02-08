<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursSchedules\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class HolidaysRelationManager extends RelationManager
{
    protected static string $relationship = 'holidays';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.holidays');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
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
                Actions\CreateAction::make(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }
}
