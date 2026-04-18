{{-- Filament Service Desk - Core Guidelines --}}

# Filament Service Desk Plugin

## Overview
This is a Filament plugin that integrates `jeffersongoncalves/laravel-service-desk` with Filament, providing 3 separate panel plugins:

- **ServiceDeskAdminPlugin** - Admin panel with full CRUD for all resources
- **ServiceDeskAgentPlugin** - Agent panel for ticket handling
- **ServiceDeskUserPlugin** - User panel for self-service

## Installation

```bash
composer require jeffersongoncalves/filament-service-desk
```

## Plugin Registration

### Admin Panel
```php
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskAdminPlugin;

$panel->plugin(ServiceDeskAdminPlugin::make()
    ->knowledgeBase(true)
    ->sla(true)
    ->emailChannels(true)
    ->serviceCatalog(true));
```

### Agent Panel
```php
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskAgentPlugin;

$panel->plugin(ServiceDeskAgentPlugin::make());
```

### User Panel
```php
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskUserPlugin;

$panel->plugin(ServiceDeskUserPlugin::make()
    ->knowledgeBase(true)
    ->serviceCatalog(true));
```

## Architecture

### Namespace Structure
- `Admin\Resources\*` - Admin resources (13 resources)
- `Agent\Resources\*` - Agent resources (2 resources)
- `User\Resources\*` - User resources (2 resources)
- `Agent\Pages\*` - Agent pages (queue, dashboard)
- `User\Pages\*` - User pages (knowledge base)
- `Admin\Widgets\*` - Admin widgets (overview, SLA, departments)
- `Agent\Widgets\*` - Agent widgets (stats, breaches)
- `User\Widgets\*` - User widgets (my tickets)

### Service Delegation
All mutations delegate to the underlying `laravel-service-desk` services:
- `TicketService` for ticket CRUD
- `CommentService` for comments/notes
- `AttachmentService` for file management
- `KnowledgeBaseService` for KB articles
- `ServiceRequestService` for service catalog requests

### Feature Toggles
Features can be toggled per-plugin:
- `knowledgeBase(bool)` - KB articles/categories
- `sla(bool)` - SLA policies/escalation
- `emailChannels(bool)` - Email channel management
- `serviceCatalog(bool)` - Service catalog/requests

## Configuration
Published to `config/filament-service-desk.php`, using a panel-scoped shape:

```php
return [
    'admin' => [
        'navigation_group' => 'Service Desk',
        'navigation_icon' => 'heroicon-o-lifebuoy',
        'navigation_sort' => null,
        'resources' => [/* per-resource overrides */],
        'widgets' => [/* admin widgets */],
    ],
    'agent' => [/* ... */],
    'user' => [/* ... */],
    'features' => [
        'knowledge_base' => true,
        'sla' => true,
        'email_channels' => true,
        'service_catalog' => true,
    ],
    'attachments' => [/* uploads config */],
];
```
