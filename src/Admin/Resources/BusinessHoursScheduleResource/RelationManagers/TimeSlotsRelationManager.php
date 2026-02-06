<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursScheduleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\ServiceDesk\Enums\DayOfWeek;

class TimeSlotsRelationManager extends RelationManager
{
    protected static string $relationship = 'timeSlots';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.time_slots');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('day_of_week')
                    ->label(__('filament-service-desk::service-desk.fields.day_of_week'))
                    ->options(collect(DayOfWeek::cases())->mapWithKeys(fn ($d) => [$d->value => $d->label()]))
                    ->required(),
                Forms\Components\TimePicker::make('start_time')
                    ->label(__('filament-service-desk::service-desk.fields.start_time'))
                    ->required()
                    ->seconds(false),
                Forms\Components\TimePicker::make('end_time')
                    ->label(__('filament-service-desk::service-desk.fields.end_time'))
                    ->required()
                    ->seconds(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('day_of_week')
            ->columns([
                Tables\Columns\TextColumn::make('day_of_week')
                    ->label(__('filament-service-desk::service-desk.fields.day_of_week'))
                    ->formatStateUsing(fn ($state) => DayOfWeek::from($state)->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label(__('filament-service-desk::service-desk.fields.start_time')),
                Tables\Columns\TextColumn::make('end_time')
                    ->label(__('filament-service-desk::service-desk.fields.end_time')),
            ])
            ->defaultSort('day_of_week')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
