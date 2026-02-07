<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
            ->modifyQueryUsing(fn (Builder $query) => $query->where('is_internal', false))
            ->columns([
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('filament-service-desk::service-desk.fields.author')),
                Tables\Columns\TextColumn::make('body')
                    ->label(__('filament-service-desk::service-desk.fields.body'))
                    ->html()
                    ->limit(150),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-service-desk::service-desk.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\Action::make('reply')
                    ->label(__('filament-service-desk::service-desk.actions.reply'))
                    ->form([
                        Forms\Components\RichEditor::make('body')
                            ->label(__('filament-service-desk::service-desk.fields.body'))
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        app(CommentService::class)->addReply(
                            $this->getOwnerRecord(),
                            auth()->user(),
                            $data['body'],
                        );
                    }),
            ])
            ->recordActions([]);
    }
}
