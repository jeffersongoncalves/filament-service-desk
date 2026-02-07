<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbCategoryResource\Pages;
use JeffersonGoncalves\ServiceDesk\Models\KbCategory;

class KbCategoryResource extends Resource
{
    protected static ?string $model = KbCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?int $navigationSort = 31;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.admin.kb_group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.kb_category.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.kb_category.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label(__('filament-service-desk::service-desk.fields.parent_category'))
                            ->relationship('parent', 'name', fn ($query) => $query->whereNull('parent_id'))
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament-service-desk::service-desk.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('icon')
                            ->label(__('filament-service-desk::service-desk.fields.icon'))
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament-service-desk::service-desk.fields.description'))
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('visibility')
                            ->label(__('filament-service-desk::service-desk.fields.visibility'))
                            ->options([
                                'public' => __('filament-service-desk::service-desk.visibility.public'),
                                'internal' => __('filament-service-desk::service-desk.visibility.internal'),
                            ])
                            ->default('public'),
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
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-service-desk::service-desk.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label(__('filament-service-desk::service-desk.fields.parent_category'))
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('visibility')
                    ->label(__('filament-service-desk::service-desk.fields.visibility'))
                    ->badge(),
                Tables\Columns\TextColumn::make('articles_count')
                    ->label(__('filament-service-desk::service-desk.fields.articles_count'))
                    ->counts('articles'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament-service-desk::service-desk.fields.is_active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('filament-service-desk::service-desk.fields.sort_order'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKbCategories::route('/'),
            'create' => Pages\CreateKbCategory::route('/create'),
            'edit' => Pages\EditKbCategory::route('/{record}/edit'),
        ];
    }
}
