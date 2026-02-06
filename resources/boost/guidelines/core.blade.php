{{-- Filament Service Desk - Core Guidelines --}}

# Filament Service Desk Plugin

## Overview
This is a Filament plugin that integrates `jeffersongoncalves/laravel-service-desk` with Filament, providing 3 separate panel plugins:

- **ServiceDeskPlugin** - Admin panel with full CRUD for all resources
- **ServiceDeskAgentPlugin** - Agent panel for ticket handling
- **ServiceDeskUserPlugin** - User panel for self-service

## Installation

```bash
composer require jeffersongoncalves/filament-service-desk
```

## Plugin Registration

### Admin Panel
```php
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskPlugin;

$panel->plugin(ServiceDeskPlugin::make()
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
- `Resources\Admin\*` - Admin resources (13 resources)
- `Resources\Agent\*` - Agent resources (2 resources)
- `Resources\User\*` - User resources (2 resources)
- `Pages\Agent\*` - Agent pages (queue, dashboard)
- `Pages\User\*` - User pages (knowledge base)
- `Widgets\Admin\*` - Admin widgets (overview, SLA, departments)
- `Widgets\Agent\*` - Agent widgets (stats, breaches)
- `Widgets\User\*` - User widgets (my tickets)

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
Published to `config/filament-service-desk.php`:
- Navigation groups and sorting
- Feature toggles
- Resource class overrides
- Widget class overrides
