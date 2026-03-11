<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\SlaPolicies\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;

class TargetsRelationManager extends RelationManager
{
    protected static string $relationship = 'targets';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.sla_targets');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(null)
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
                Actions\CreateAction::make(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }
}
