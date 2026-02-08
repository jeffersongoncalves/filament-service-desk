<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannels\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;

class EmailChannelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.general'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament-service-desk::service-desk.fields.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('department_id')
                            ->label(__('filament-service-desk::service-desk.fields.department'))
                            ->relationship('department', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('email_address')
                            ->label(__('filament-service-desk::service-desk.fields.email_address'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('driver')
                            ->label(__('filament-service-desk::service-desk.fields.driver'))
                            ->options([
                                'imap' => 'IMAP',
                                'mailgun' => 'Mailgun',
                                'sendgrid' => 'SendGrid',
                                'resend' => 'Resend',
                                'postmark' => 'Postmark',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament-service-desk::service-desk.fields.is_active'))
                            ->default(true),
                    ])
                    ->columns(2),

                Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.imap_settings'))
                    ->schema([
                        Forms\Components\TextInput::make('settings.host')
                            ->label(__('filament-service-desk::service-desk.fields.host'))
                            ->required(),
                        Forms\Components\TextInput::make('settings.port')
                            ->label(__('filament-service-desk::service-desk.fields.port'))
                            ->numeric()
                            ->default(993),
                        Forms\Components\Select::make('settings.encryption')
                            ->label(__('filament-service-desk::service-desk.fields.encryption'))
                            ->options([
                                'ssl' => 'SSL',
                                'tls' => 'TLS',
                                'none' => 'None',
                            ])
                            ->default('ssl'),
                        Forms\Components\TextInput::make('settings.username')
                            ->label(__('filament-service-desk::service-desk.fields.username'))
                            ->required(),
                        Forms\Components\TextInput::make('settings.password')
                            ->label(__('filament-service-desk::service-desk.fields.password'))
                            ->password()
                            ->required(),
                        Forms\Components\TextInput::make('settings.folder')
                            ->label(__('filament-service-desk::service-desk.fields.folder'))
                            ->default('INBOX'),
                    ])
                    ->columns(2)
                    ->visible(fn (Schemas\Components\Utilities\Get $get) => $get('driver') === 'imap'),

                Schemas\Components\Section::make(__('filament-service-desk::service-desk.sections.api_settings'))
                    ->schema([
                        Forms\Components\TextInput::make('settings.api_key')
                            ->label(__('filament-service-desk::service-desk.fields.api_key'))
                            ->password()
                            ->required(),
                        Forms\Components\TextInput::make('settings.domain')
                            ->label(__('filament-service-desk::service-desk.fields.domain'))
                            ->visible(fn (Schemas\Components\Utilities\Get $get) => $get('driver') === 'mailgun'),
                        Forms\Components\TextInput::make('settings.webhook_secret')
                            ->label(__('filament-service-desk::service-desk.fields.webhook_secret'))
                            ->password(),
                    ])
                    ->columns(2)
                    ->visible(fn (Schemas\Components\Utilities\Get $get) => in_array($get('driver'), ['mailgun', 'sendgrid', 'resend', 'postmark'])),
            ]);
    }
}
