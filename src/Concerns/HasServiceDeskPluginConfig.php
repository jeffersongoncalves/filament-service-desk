<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Concerns;

use BackedEnum;

trait HasServiceDeskPluginConfig
{
    protected bool $knowledgeBaseEnabled = true;

    protected bool $slaEnabled = true;

    protected bool $emailChannelsEnabled = true;

    protected bool $serviceCatalogEnabled = true;

    protected ?string $navigationGroup = null;

    protected ?int $navigationSort = null;

    protected string|BackedEnum|null $navigationIcon = null;

    public function knowledgeBase(bool $enabled = true): static
    {
        $this->knowledgeBaseEnabled = $enabled;

        return $this;
    }

    public function hasKnowledgeBase(): bool
    {
        return $this->knowledgeBaseEnabled && config('filament-service-desk.features.knowledge_base', true);
    }

    public function sla(bool $enabled = true): static
    {
        $this->slaEnabled = $enabled;

        return $this;
    }

    public function hasSla(): bool
    {
        return $this->slaEnabled && config('filament-service-desk.features.sla', true);
    }

    public function emailChannels(bool $enabled = true): static
    {
        $this->emailChannelsEnabled = $enabled;

        return $this;
    }

    public function hasEmailChannels(): bool
    {
        return $this->emailChannelsEnabled && config('filament-service-desk.features.email_channels', true);
    }

    public function serviceCatalog(bool $enabled = true): static
    {
        $this->serviceCatalogEnabled = $enabled;

        return $this;
    }

    public function hasServiceCatalog(): bool
    {
        return $this->serviceCatalogEnabled && config('filament-service-desk.features.service_catalog', true);
    }

    public function navigationGroup(string $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function getNavigationGroup(): ?string
    {
        return $this->navigationGroup;
    }

    public function navigationSort(?int $sort): static
    {
        $this->navigationSort = $sort;

        return $this;
    }

    public function getNavigationSort(): ?int
    {
        return $this->navigationSort;
    }

    public function navigationIcon(string|BackedEnum $icon): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function getNavigationIcon(): string|BackedEnum|null
    {
        return $this->navigationIcon;
    }
}
