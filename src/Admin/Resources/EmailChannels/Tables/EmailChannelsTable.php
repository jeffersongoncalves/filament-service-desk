<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannels\Tables;

use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class EmailChannelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-service-desk::service-desk.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label(__('filament-service-desk::service-desk.fields.department'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_address')
                    ->label(__('filament-service-desk::service-desk.fields.email_address'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('driver')
                    ->label(__('filament-service-desk::service-desk.fields.driver'))
                    ->badge(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament-service-desk::service-desk.fields.is_active'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_polled_at')
                    ->label(__('filament-service-desk::service-desk.fields.last_polled_at'))
                    ->dateTime()
                    ->sortable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('last_error')
                    ->label(__('filament-service-desk::service-desk.fields.last_error'))
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('driver')
                    ->label(__('filament-service-desk::service-desk.fields.driver'))
                    ->options([
                        'imap' => 'IMAP',
                        'mailgun' => 'Mailgun',
                        'sendgrid' => 'SendGrid',
                        'resend' => 'Resend',
                        'postmark' => 'Postmark',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament-service-desk::service-desk.fields.is_active')),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
