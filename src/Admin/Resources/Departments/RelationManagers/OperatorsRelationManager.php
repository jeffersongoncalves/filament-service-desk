<?php

declare(strict_types=1);

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Departments\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Who works a department's queue, and whether each of them gets the operator
 * emails for it. Uses the `operators` relation the plugin's service provider
 * adds to Department for the configured operator model.
 */
class OperatorsRelationManager extends RelationManager
{
    protected static string $relationship = 'operators';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.operators');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-service-desk::service-desk.fields.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('filament-service-desk::service-desk.fields.email'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label(__('filament-service-desk::service-desk.fields.role'))
                    ->getStateUsing(fn ($record): string => $record->pivot->role ?? 'operator')
                    ->formatStateUsing(fn (string $state): string => __("filament-service-desk::service-desk.department_roles.{$state}"))
                    ->badge()
                    ->color(fn (string $state): string => $state === 'manager' ? 'success' : 'info'),

                IconColumn::make('email_notifications')
                    ->label(__('filament-service-desk::service-desk.fields.email_notifications'))
                    ->getStateUsing(fn ($record): bool => (bool) ($record->pivot->email_notifications ?? true))
                    ->boolean(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label(__('filament-service-desk::service-desk.fields.operator')),

                        Select::make('role')
                            ->label(__('filament-service-desk::service-desk.fields.role'))
                            ->options([
                                'operator' => __('filament-service-desk::service-desk.department_roles.operator'),
                                'manager' => __('filament-service-desk::service-desk.department_roles.manager'),
                            ])
                            ->default('operator')
                            ->required(),

                        Toggle::make('email_notifications')
                            ->label(__('filament-service-desk::service-desk.fields.email_notifications'))
                            ->default(true),
                    ]),
            ])
            ->recordActions([
                // Updates the pivot row directly: the row's record is the
                // operator model, which has no such column of its own.
                Action::make('toggleEmailNotifications')
                    ->label(fn ($record): string => ($record->pivot->email_notifications ?? true)
                        ? __('filament-service-desk::service-desk.actions.disable_email_notifications')
                        : __('filament-service-desk::service-desk.actions.enable_email_notifications'))
                    ->icon(fn ($record): string => ($record->pivot->email_notifications ?? true) ? 'heroicon-o-bell-slash' : 'heroicon-o-bell')
                    ->color('gray')
                    ->action(function ($record): void {
                        $record->pivot->update([
                            'email_notifications' => ! ($record->pivot->email_notifications ?? true),
                        ]);
                    }),

                DetachAction::make()
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                DetachBulkAction::make(),
            ]);
    }
}
