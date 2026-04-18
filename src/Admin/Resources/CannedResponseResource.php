<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\CannedResponseResource\Pages;
use JeffersonGoncalves\ServiceDesk\Models\CannedResponse;

class CannedResponseResource extends Resource
{
    protected static ?string $model = CannedResponse::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.admin.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.canned_response.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.canned_response.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-service-desk::service-desk.fields.title'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('department_id')
                            ->label(__('filament-service-desk::service-desk.fields.department'))
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\RichEditor::make('body')
                            ->label(__('filament-service-desk::service-desk.fields.body'))
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament-service-desk::service-desk.fields.is_active'))
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('filament-service-desk::service-desk.fields.sort_order'))
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
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
                    ->placeholder(__('filament-service-desk::service-desk.fields.all_departments'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament-service-desk::service-desk.fields.is_active'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('filament-service-desk::service-desk.fields.sort_order'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->label(__('filament-service-desk::service-desk.fields.department'))
                    ->relationship('department', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament-service-desk::service-desk.fields.is_active')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCannedResponses::route('/'),
            'create' => Pages\CreateCannedResponse::route('/create'),
            'edit' => Pages\EditCannedResponse::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-service-desk.admin.resources.canned_response') !== null;
    }
}
