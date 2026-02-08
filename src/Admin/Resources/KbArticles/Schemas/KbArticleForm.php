<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticles\Schemas;

use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use JeffersonGoncalves\ServiceDesk\Enums\ArticleStatus;
use JeffersonGoncalves\ServiceDesk\Enums\ArticleVisibility;
use JeffersonGoncalves\ServiceDesk\Models\KbArticle;

class KbArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.content'))
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('filament-service-desk::service-desk.fields.title'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Forms\Components\RichEditor::make('content')
                                    ->label(__('filament-service-desk::service-desk.fields.content'))
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('excerpt')
                                    ->label(__('filament-service-desk::service-desk.fields.excerpt'))
                                    ->maxLength(500)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                        Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.seo'))
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label(__('filament-service-desk::service-desk.fields.seo_title'))
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('seo_description')
                                    ->label(__('filament-service-desk::service-desk.fields.seo_description'))
                                    ->maxLength(255),
                                Forms\Components\TagsInput::make('seo_keywords')
                                    ->label(__('filament-service-desk::service-desk.fields.seo_keywords')),
                            ])
                            ->collapsed(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.settings'))
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label(__('filament-service-desk::service-desk.fields.category'))
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                                Forms\Components\Select::make('status')
                                    ->label(__('filament-service-desk::service-desk.fields.status'))
                                    ->options(collect(ArticleStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->name]))
                                    ->required()
                                    ->default(ArticleStatus::Draft->value),
                                Forms\Components\Select::make('visibility')
                                    ->label(__('filament-service-desk::service-desk.fields.visibility'))
                                    ->options(collect(ArticleVisibility::cases())->mapWithKeys(fn ($v) => [$v->value => $v->name]))
                                    ->required()
                                    ->default(ArticleVisibility::Public->value),
                                Forms\Components\Select::make('tags')
                                    ->label(__('filament-service-desk::service-desk.fields.tags'))
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label(__('filament-service-desk::service-desk.fields.published_at')),
                            ]),
                        Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.stats'))
                            ->schema([
                                Infolists\Components\TextEntry::make('view_count')
                                    ->label(__('filament-service-desk::service-desk.fields.view_count'))
                                    ->state(fn (?KbArticle $record) => $record?->view_count ?? 0),
                                Infolists\Components\TextEntry::make('helpful_count')
                                    ->label(__('filament-service-desk::service-desk.fields.helpful_count'))
                                    ->state(fn (?KbArticle $record) => $record?->helpful_count ?? 0),
                                Infolists\Components\TextEntry::make('not_helpful_count')
                                    ->label(__('filament-service-desk::service-desk.fields.not_helpful_count'))
                                    ->state(fn (?KbArticle $record) => $record?->not_helpful_count ?? 0),
                            ])
                            ->hiddenOn('create'),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
