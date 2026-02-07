<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\ServiceResource\Pages;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\ServiceResource\RelationManagers;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;
use JeffersonGoncalves\ServiceDesk\Models\Service;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 40;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.admin.catalog_group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.service.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.service.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('filament-service-desk::service-desk.sections.general'))
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('filament-service-desk::service-desk.fields.name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Forms\Components\Textarea::make('description')
                                    ->label(__('filament-service-desk::service-desk.fields.description'))
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                                Forms\Components\RichEditor::make('long_description')
                                    ->label(__('filament-service-desk::service-desk.fields.long_description'))
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('filament-service-desk::service-desk.sections.settings'))
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label(__('filament-service-desk::service-desk.fields.category'))
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                                Forms\Components\Select::make('department_id')
                                    ->label(__('filament-service-desk::service-desk.fields.department'))
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                                Forms\Components\TextInput::make('icon')
                                    ->label(__('filament-service-desk::service-desk.fields.icon'))
                                    ->maxLength(255),
                                Forms\Components\Select::make('default_priority')
                                    ->label(__('filament-service-desk::service-desk.fields.default_priority'))
                                    ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()]))
                                    ->nullable(),
                                Forms\Components\Select::make('visibility')
                                    ->label(__('filament-service-desk::service-desk.fields.visibility'))
                                    ->options([
                                        'public' => __('filament-service-desk::service-desk.visibility.public'),
                                        'internal' => __('filament-service-desk::service-desk.visibility.internal'),
                                    ])
                                    ->default('public'),
                                Forms\Components\Toggle::make('requires_approval')
                                    ->label(__('filament-service-desk::service-desk.fields.requires_approval'))
                                    ->default(false),
                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('filament-service-desk::service-desk.fields.is_active'))
                                    ->default(true),
                                Forms\Components\TextInput::make('expected_duration_minutes')
                                    ->label(__('filament-service-desk::service-desk.fields.expected_duration'))
                                    ->numeric()
                                    ->suffix(__('filament-service-desk::service-desk.fields.minutes'))
                                    ->nullable(),
                                Forms\Components\TextInput::make('sort_order')
                                    ->label(__('filament-service-desk::service-desk.fields.sort_order'))
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-service-desk::service-desk.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('filament-service-desk::service-desk.fields.category'))
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label(__('filament-service-desk::service-desk.fields.department'))
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('visibility')
                    ->label(__('filament-service-desk::service-desk.fields.visibility'))
                    ->badge(),
                Tables\Columns\IconColumn::make('requires_approval')
                    ->label(__('filament-service-desk::service-desk.fields.requires_approval'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament-service-desk::service-desk.fields.is_active'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('filament-service-desk::service-desk.fields.sort_order'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label(__('filament-service-desk::service-desk.fields.category'))
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament-service-desk::service-desk.fields.is_active')),
            ])
            ->recordActions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\FormFieldsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
