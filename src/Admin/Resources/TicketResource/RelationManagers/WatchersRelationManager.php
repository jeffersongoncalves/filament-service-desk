<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\TicketResource\RelationManagers;

use Filament\Actions;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;

class WatchersRelationManager extends RelationManager
{
    protected static string $relationship = 'watchers';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.watchers');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('watcher.name')
            ->columns([
                Tables\Columns\TextColumn::make('watcher.name')
                    ->label(__('filament-service-desk::service-desk.fields.name')),
                Tables\Columns\TextColumn::make('watcher.email')
                    ->label(__('filament-service-desk::service-desk.fields.email')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.watching_since'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->headerActions([
                Actions\Action::make('addWatcher')
                    ->label(__('filament-service-desk::service-desk.actions.add_watcher'))
                    ->form([
                        \Filament\Forms\Components\Select::make('watcher_id')
                            ->label(__('filament-service-desk::service-desk.fields.user'))
                            ->options(fn () => config('service-desk.models.user')::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $userModel = config('service-desk.models.user');
                        $watcher = $userModel::findOrFail($data['watcher_id']);
                        app(TicketService::class)->addWatcher($this->getOwnerRecord(), $watcher);
                    }),
            ])
            ->recordActions([
                Actions\DeleteAction::make()
                    ->label(__('filament-service-desk::service-desk.actions.remove_watcher'))
                    ->action(function ($record) {
                        app(TicketService::class)->removeWatcher($this->getOwnerRecord(), $record->watcher);
                    }),
            ]);
    }
}
