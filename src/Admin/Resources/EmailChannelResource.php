<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Resources;

use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannelResource\Pages;
use JeffersonGoncalves\ServiceDesk\Models\EmailChannel;

class EmailChannelResource extends Resource
{
    protected static ?string $model = EmailChannel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;
    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): ?string
    {
        return __('filament-service-desk::service-desk.navigation.admin.group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.email_channel.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-service-desk::service-desk.resources.email_channel.plural_label');
    }

    public static function form(Schema $schema): Schema
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

    public static function table(Table $table): Table
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailChannels::route('/'),
            'create' => Pages\CreateEmailChannel::route('/create'),
            'edit' => Pages\EditEmailChannel::route('/{record}/edit'),
        ];
    }
}
