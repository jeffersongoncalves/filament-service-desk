<div class="filament-hidden">

![Filament Service Desk](https://raw.githubusercontent.com/jeffersongoncalves/filament-service-desk/2.x/art/jeffersongoncalves-filament-service-desk.png)

</div>

# Filament Service Desk

A Filament plugin for [jeffersongoncalves/laravel-service-desk](https://github.com/jeffersongoncalves/laravel-service-desk) that provides Admin, Agent, and User panels for complete service desk management.

## Compatibility

| Version | Filament | PHP | Laravel | Tailwind |
|---------|----------|-----|---------|----------|
| 1.x | ^3.0 | ^8.1 | ^10.0 | 3.x |
| 2.x | ^4.0 | ^8.2 | ^11.0 | 4.x |
| 3.x | ^5.0 | ^8.2 | ^11.28 | 4.x |

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/filament-service-desk:"^2.0"
```

Publish the configuration (optional):

```bash
php artisan vendor:publish --tag="filament-service-desk-config"
```

## Usage

### Admin Panel

Full management capabilities: departments, categories, tags, tickets, SLA, email channels, knowledge base, and service catalog.

```php
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ServiceDeskPlugin::make()
                ->knowledgeBase(true)
                ->sla(true)
                ->emailChannels(true)
                ->serviceCatalog(true)
                ->navigationGroup('Service Desk'),
        ]);
}
```

**Resources:** Department, Category, Tag, Canned Response, Ticket (with comments, attachments, history, watchers), SLA Policy (with targets, escalation rules), Business Hours Schedule (with time slots, holidays), Email Channel, KB Article (with versions, feedback), KB Category, Service (with form fields), Service Category.

**Widgets:** Service Desk Overview, SLA Compliance Chart, Tickets by Department Chart.

### Agent Panel

Ticket handling and response for support agents.

```php
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskAgentPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ServiceDeskAgentPlugin::make(),
        ]);
}
```

**Resources:** Ticket (scoped to assigned), Canned Responses (read-only).

**Pages:** Ticket Queue (claim unassigned tickets), Agent Dashboard.

**Widgets:** Agent Ticket Stats, SLA Breach Table.

### User Panel

Self-service portal for end users.

```php
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskUserPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ServiceDeskUserPlugin::make()
                ->knowledgeBase(true)
                ->serviceCatalog(true),
        ]);
}
```

**Resources:** Ticket (create/view own), Service Request (wizard with dynamic form).

**Pages:** Knowledge Base (search, browse, feedback).

**Widgets:** My Tickets Overview.

## Feature Toggles

Each plugin supports fluent feature toggles:

| Method | Default | Description |
|--------|---------|-------------|
| `knowledgeBase(bool)` | `true` | KB articles and categories |
| `sla(bool)` | `true` | SLA policies, targets, escalation |
| `emailChannels(bool)` | `true` | Email channel management |
| `serviceCatalog(bool)` | `true` | Service catalog and requests |

Features can also be toggled globally in `config/filament-service-desk.php`.

## Localization

Translations are provided for:
- English (`en`)
- Brazilian Portuguese (`pt_BR`)

Publish translations to customize:

```bash
php artisan vendor:publish --tag="filament-service-desk-translations"
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
