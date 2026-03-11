<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\Tickets\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\ServiceDesk\Enums\CommentType;
use JeffersonGoncalves\ServiceDesk\Services\CommentService;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament-service-desk::service-desk.relations.comments');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->columns([
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('filament-service-desk::service-desk.fields.author')),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament-service-desk::service-desk.fields.type'))
                    ->badge()
                    ->formatStateUsing(fn (CommentType $state) => match ($state) {
                        CommentType::Reply => __('filament-service-desk::service-desk.comment_types.reply'),
                        CommentType::Note => __('filament-service-desk::service-desk.comment_types.note'),
                        CommentType::System => __('filament-service-desk::service-desk.comment_types.system'),
                    })
                    ->color(fn (CommentType $state) => match ($state) {
                        CommentType::Reply => 'info',
                        CommentType::Note => 'warning',
                        CommentType::System => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_internal')
                    ->label(__('filament-service-desk::service-desk.fields.is_internal'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('body')
                    ->label(__('filament-service-desk::service-desk.fields.body'))
                    ->html()
                    ->limit(100),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Actions\Action::make('addReply')
                    ->label(__('filament-service-desk::service-desk.actions.add_reply'))
                    ->schema([
                        Forms\Components\RichEditor::make('body')
                            ->label(__('filament-service-desk::service-desk.fields.body'))
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        app(CommentService::class)->addReply(
                            $this->getOwnerRecord(),
                            auth()->guard()->user(),
                            $data['body'],
                        );
                    }),
                Actions\Action::make('addNote')
                    ->label(__('filament-service-desk::service-desk.actions.add_note'))
                    ->color('warning')
                    ->schema([
                        Forms\Components\RichEditor::make('body')
                            ->label(__('filament-service-desk::service-desk.fields.body'))
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        app(CommentService::class)->addNote(
                            $this->getOwnerRecord(),
                            auth()->guard()->user(),
                            $data['body'],
                        );
                    }),
            ])
            ->recordActions([]);
    }
}
