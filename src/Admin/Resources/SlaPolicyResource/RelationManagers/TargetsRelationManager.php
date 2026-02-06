<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\SlaPolicyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;

class TargetsRelationManager extends RelationManager
{
    protected static string $relationship = 'targets';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.sla_targets');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('priority')
                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                    ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()]))
                    ->required(),
                Forms\Components\TextInput::make('first_response_time')
                    ->label(__('filament-service-desk::service-desk.fields.first_response_time'))
                    ->numeric()
                    ->suffix(__('filament-service-desk::service-desk.fields.minutes'))
                    ->required(),
                Forms\Components\TextInput::make('next_response_time')
                    ->label(__('filament-service-desk::service-desk.fields.next_response_time'))
                    ->numeric()
                    ->suffix(__('filament-service-desk::service-desk.fields.minutes'))
                    ->nullable(),
                Forms\Components\TextInput::make('resolution_time')
                    ->label(__('filament-service-desk::service-desk.fields.resolution_time'))
                    ->numeric()
                    ->suffix(__('filament-service-desk::service-desk.fields.minutes'))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('priority')
            ->columns([
                Tables\Columns\TextColumn::make('priority')
                    ->label(__('filament-service-desk::service-desk.fields.priority'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => TicketPriority::from($state)->label())
                    ->color(fn ($state) => match (TicketPriority::from($state)) {
                        TicketPriority::Low => 'gray',
                        TicketPriority::Medium => 'info',
                        TicketPriority::High => 'warning',
                        TicketPriority::Urgent => 'danger',
                    }),
                Tables\Columns\TextColumn::make('first_response_time')
                    ->label(__('filament-service-desk::service-desk.fields.first_response_time'))
                    ->suffix(' min'),
                Tables\Columns\TextColumn::make('next_response_time')
                    ->label(__('filament-service-desk::service-desk.fields.next_response_time'))
                    ->suffix(' min')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('resolution_time')
                    ->label(__('filament-service-desk::service-desk.fields.resolution_time'))
                    ->suffix(' min'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
