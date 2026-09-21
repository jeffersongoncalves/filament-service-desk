<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannelResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannelResource;
use JeffersonGoncalves\ServiceDesk\Contracts\EmailDriver;
use JeffersonGoncalves\ServiceDesk\Mail\Drivers\ImapDriver;
use JeffersonGoncalves\ServiceDesk\Mail\Drivers\MailgunDriver;
use JeffersonGoncalves\ServiceDesk\Mail\Drivers\PostmarkDriver;
use JeffersonGoncalves\ServiceDesk\Mail\Drivers\ResendDriver;
use JeffersonGoncalves\ServiceDesk\Mail\Drivers\SendGridDriver;
use JeffersonGoncalves\ServiceDesk\Models\EmailChannel;

class EditEmailChannel extends EditRecord
{
    protected static string $resource = EmailChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('testConnection')
                ->label(__('filament-service-desk::service-desk.actions.test_connection'))
                ->icon('heroicon-o-signal')
                ->color('gray')
                ->action(function (EmailChannel $record) {
                    $driver = $this->resolveDriver($record->driver);

                    if ($driver->testConnection($record)) {
                        Notification::make()
                            ->title(__('filament-service-desk::service-desk.notifications.connection_successful'))
                            ->success()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title(__('filament-service-desk::service-desk.notifications.connection_failed'))
                        ->danger()
                        ->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function resolveDriver(string $driver): EmailDriver
    {
        return match ($driver) {
            'imap' => app(ImapDriver::class),
            'mailgun' => app(MailgunDriver::class),
            'sendgrid' => app(SendGridDriver::class),
            'resend' => app(ResendDriver::class),
            'postmark' => app(PostmarkDriver::class),
            default => app(ImapDriver::class),
        };
    }
}
