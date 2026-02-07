<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Resources;

use BackedEnum;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\CannedResponseResource\Pages;
use JeffersonGoncalves\ServiceDesk\Models\CannedResponse;

class CannedResponseResource extends Resource
{
    protected static ?string $model = CannedResponse::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;
    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.agent.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.canned_response.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.canned_response.plural_label');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-service-desk::service-desk.fields.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label(__('filament-service-desk::service-desk.fields.department'))
                    ->placeholder(__('filament-service-desk::service-desk.fields.all_departments')),
                Tables\Columns\TextColumn::make('body')
                    ->label(__('filament-service-desk::service-desk.fields.body'))
                    ->html()
                    ->limit(100),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->label(__('filament-service-desk::service-desk.fields.department'))
                    ->relationship('department', 'name'),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCannedResponses::route('/'),
        ];
    }
}
