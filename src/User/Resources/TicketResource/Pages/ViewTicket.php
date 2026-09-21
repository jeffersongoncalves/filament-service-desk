<?php

namespace JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource\Pages;

use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\FilamentServiceDesk\Concerns\InteractsWithTicketApiTransport;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource;
use JeffersonGoncalves\ServiceDesk\Models\Ticket;
use JeffersonGoncalves\ServiceDesk\Services\TicketService;
use JeffersonGoncalves\ServiceDesk\Services\Transports\ApiTicketTransport;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ViewTicket extends ViewRecord
{
    use InteractsWithTicketApiTransport;

    protected static string $resource = TicketResource::class;

    /**
     * Under the API transport, tickets aren't stored locally -- the default
     * Eloquent route-model-binding lookup would always 404. Fetch the
     * transient, non-persisted Ticket the transport hydrates instead.
     */
    protected function resolveRecord(int|string $key): Model
    {
        if (! static::isTicketApiTransport()) {
            return parent::resolveRecord($key);
        }

        return app(TicketService::class)->findByUuid((string) $key);
    }

    protected function ticket(): Ticket
    {
        /** @var Ticket $ticket */
        $ticket = $this->getRecord();

        return $ticket;
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\Action::make('close')
                ->label(__('filament-service-desk::service-desk.actions.close'))
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record = app(TicketService::class)->close($this->ticket(), auth()->guard()->user());
                })
                ->visible(fn () => $this->ticket()->isOpen()),
            Actions\Action::make('reopen')
                ->label(__('filament-service-desk::service-desk.actions.reopen'))
                ->action(function () {
                    $this->record = app(TicketService::class)->reopen($this->ticket(), auth()->guard()->user());
                })
                ->visible(fn () => $this->ticket()->isClosed()),
        ];

        if (static::isTicketApiTransport()) {
            $actions[] = $this->uploadAttachmentAction();
        }

        return $actions;
    }

    /**
     * Attachments have no Eloquent relation under the API transport (see
     * TicketResource::getRelations()); talk to ApiTicketTransport directly,
     * same as TicketResource::attachmentsSection() reads them back.
     */
    protected function uploadAttachmentAction(): Actions\Action
    {
        return Actions\Action::make('uploadAttachment')
            ->label(__('filament-service-desk::service-desk.actions.upload_attachment'))
            ->icon('heroicon-o-paper-clip')
            ->form([
                Forms\Components\FileUpload::make('file')
                    ->label(__('filament-service-desk::service-desk.fields.file'))
                    ->storeFiles(false)
                    ->maxSize(config('service-desk.api.max_inline_attachment', 2048))
                    ->required(),
            ])
            ->action(function (array $data) {
                /** @var TemporaryUploadedFile $file */
                $file = $data['file'];

                app(ApiTicketTransport::class)->uploadAttachment(
                    $this->ticket(),
                    $file->getClientOriginalName(),
                    $file->getMimeType() ?: 'application/octet-stream',
                    $file->get(),
                    auth()->guard()->user(),
                );

                Notification::make()
                    ->title(__('filament-service-desk::service-desk.notifications.attachment_uploaded'))
                    ->success()
                    ->send();
            });
    }
}
