<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources;

use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequestResource\Pages;
use JeffersonGoncalves\ServiceDesk\Enums\ServiceRequestStatus;
use JeffersonGoncalves\ServiceDesk\Models\ServiceRequest;

class ServiceRequestResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.user.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.service_request.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.service_request.plural_label');
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var Model $user */
        $user = auth()->guard()->user();

        return parent::getEloquentQuery()
            ->where('requester_id', $user->getKey())
            ->where('requester_type', $user->getMorphClass());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament-service-desk::service-desk.resources.service_request.label'))
                    ->schema([
                        Infolists\Components\TextEntry::make('service.name')
                            ->label(__('filament-service-desk::service-desk.fields.service')),
                        Infolists\Components\TextEntry::make('status')
                            ->label(__('filament-service-desk::service-desk.fields.status'))
                            ->badge()
                            ->formatStateUsing(fn (ServiceRequestStatus $state) => $state->name)
                            ->color(fn (ServiceRequestStatus $state) => match ($state) {
                                ServiceRequestStatus::Pending => 'warning',
                                ServiceRequestStatus::Approved => 'info',
                                ServiceRequestStatus::Rejected => 'danger',
                                ServiceRequestStatus::InProgress => 'primary',
                                ServiceRequestStatus::Fulfilled => 'success',
                                ServiceRequestStatus::Cancelled => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('ticket.reference_number')
                            ->label(__('filament-service-desk::service-desk.fields.ticket'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('notes')
                            ->label(__('filament-service-desk::service-desk.fields.notes'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        Infolists\Components\KeyValueEntry::make('form_data')
                            ->label(__('filament-service-desk::service-desk.fields.form_data'))
                            ->columnSpanFull()
                            ->visible(fn (ServiceRequest $record) => ! empty($record->form_data)),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label(__('filament-service-desk::service-desk.fields.created_at'))
                            ->since(),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label(__('filament-service-desk::service-desk.fields.updated_at'))
                            ->since(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label(__('filament-service-desk::service-desk.fields.service'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ticket.reference_number')
                    ->label(__('filament-service-desk::service-desk.fields.ticket'))
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament-service-desk::service-desk.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (ServiceRequestStatus $state) => $state->name)
                    ->color(fn (ServiceRequestStatus $state) => match ($state) {
                        ServiceRequestStatus::Pending => 'warning',
                        ServiceRequestStatus::Approved => 'info',
                        ServiceRequestStatus::Rejected => 'danger',
                        ServiceRequestStatus::InProgress => 'primary',
                        ServiceRequestStatus::Fulfilled => 'success',
                        ServiceRequestStatus::Cancelled => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament-service-desk::service-desk.fields.status'))
                    ->options(collect(ServiceRequestStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->name])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceRequests::route('/'),
            'create' => Pages\CreateServiceRequest::route('/create'),
            'view' => Pages\ViewServiceRequest::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-service-desk.user.resources.service_request') !== null;
    }
}
