<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets\Schemas;

use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use JeffersonGoncalves\ServiceDesk\Enums\TicketPriority;
use JeffersonGoncalves\ServiceDesk\Services\KnowledgeBaseService;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(null)
            ->schema([
                Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.new_ticket'))
                    ->schema([
                        Forms\Components\Select::make('department_id')
                            ->label(__('filament-service-desk::service-desk.fields.department'))
                            ->relationship('department', 'name', fn ($query) => $query->where('is_active', true))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Schemas\Components\Utilities\Set $set) => $set('category_id', null)),
                        Forms\Components\Select::make('category_id')
                            ->label(__('filament-service-desk::service-desk.fields.category'))
                            ->relationship(
                                'category',
                                'name',
                                fn ($query, Schemas\Components\Utilities\Get $get) => $query
                                    ->where('department_id', $get('department_id'))
                                    ->where('is_active', true)
                            )
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-service-desk::service-desk.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->live(debounce: 500)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->label(__('filament-service-desk::service-desk.fields.description'))
                            ->required()
                            ->live(debounce: 500)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('suggested_articles')
                            ->hiddenLabel()
                            ->html()
                            ->state(function (Schemas\Components\Utilities\Get $get) {
                                $query = trim(($get('title') ?? '').' '.strip_tags((string) ($get('description') ?? '')));

                                if ($query === '') {
                                    return null;
                                }

                                $articles = app(KnowledgeBaseService::class)->search($query, ['limit' => 3]);

                                return $articles->isEmpty() ? null : view('filament-service-desk::components.kb-suggestions', ['articles' => $articles]);
                            })
                            ->visible(fn (Schemas\Components\Utilities\Get $get) => filled($get('title')))
                            ->columnSpanFull(),
                        Forms\Components\Select::make('priority')
                            ->label(__('filament-service-desk::service-desk.fields.priority'))
                            ->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()]))
                            ->default(TicketPriority::Medium->value)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
