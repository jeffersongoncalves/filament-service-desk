<div class="filament-hidden">

![Filament Service Desk](https://raw.githubusercontent.com/jeffersongoncalves/filament-service-desk/3.x/art/jeffersongoncalves-filament-service-desk.png)

</div>

# Filament Service Desk

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/filament-service-desk.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-service-desk)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/filament-service-desk.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-service-desk)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/filament-service-desk.svg?style=flat-square)](LICENSE.md)

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
composer require jeffersongoncalves/filament-service-desk:"^3.0"
```

Publish the configuration (optional):

```bash
php artisan vendor:publish --tag="filament-service-desk-config"
```

## Usage

### Admin Panel

Full management capabilities: departments, categories, tags, tickets, SLA, email channels, knowledge base, and service catalog.

```php
use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskAdminPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            ServiceDeskAdminPlugin::make()
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

## Customizing Widgets

Each panel's dashboard widgets can be customized via the configuration file. Widgets are now scoped under each panel key (`admin`, `agent`, `user`):

```php
// config/filament-service-desk.php
'admin' => [
    // ...
    'widgets' => [
        \JeffersonGoncalves\FilamentServiceDesk\Admin\Widgets\ServiceDeskOverviewWidget::class,
        \JeffersonGoncalves\FilamentServiceDesk\Admin\Widgets\SlaComplianceWidget::class,
        \JeffersonGoncalves\FilamentServiceDesk\Admin\Widgets\TicketsByDepartmentWidget::class,
        // Add your custom widgets here
    ],
],
'agent' => [
    // ...
    'widgets' => [
        \JeffersonGoncalves\FilamentServiceDesk\Agent\Widgets\AgentTicketStatsWidget::class,
        \JeffersonGoncalves\FilamentServiceDesk\Agent\Widgets\SlaBreachWidget::class,
    ],
],
'user' => [
    // ...
    'widgets' => [
        \JeffersonGoncalves\FilamentServiceDesk\User\Widgets\MyTicketsOverviewWidget::class,
    ],
],
```

To disable all widgets for a panel, set its `widgets` key to an empty array:

```php
'admin' => [
    // ...
    'widgets' => [], // No widgets on admin dashboard
],
```

## Upgrade from 3.0.x

Breaking changes introduced in `3.2.0`:

- Class `ServiceDeskPlugin` was renamed to `ServiceDeskAdminPlugin`.
- Plugin ID changed from `filament-service-desk` to `filament-service-desk-admin`.
- The published `config/filament-service-desk.php` was restructured from the feature-scoped shape (`navigation.admin|agent|user`, `resources.admin|agent|user`, `widgets.admin|agent|user`) to a panel-scoped shape that matches `jeffersongoncalves/filament-help-desk`.

Update your panel providers:

```diff
-use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskPlugin;
+use JeffersonGoncalves\FilamentServiceDesk\ServiceDeskAdminPlugin;

 return $panel->plugins([
-    ServiceDeskPlugin::make(),
+    ServiceDeskAdminPlugin::make(),
 ]);
```

If you previously published the config, republish it with `--force`:

```bash
php artisan vendor:publish --tag="filament-service-desk-config" --force
```

## Attachments Configuration

Configure file upload settings for ticket attachments in the configuration file:

```php
// config/filament-service-desk.php
'attachments' => [
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'zip', 'rar'],
    'max_file_size' => 10240, // in KB (10MB)
    'disk' => 'local',
],
```

| Option | Default | Description |
|--------|---------|-------------|
| `allowed_extensions` | Common file types | File extensions resolved to MIME types via Symfony MimeTypes |
| `max_file_size` | `10240` (10MB) | Maximum file size in kilobytes |
| `disk` | `local` | Filesystem disk for storing attachments |

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
