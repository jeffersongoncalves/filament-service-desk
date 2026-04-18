# Filament Service Desk Development

## Skill Description
Develop and extend the Filament Service Desk plugin, which provides admin, agent, and user panels for the laravel-service-desk package.

## Key Concepts

### Three Plugins, One Package
The package provides 3 independent Filament plugins that can be used in different panels:
- `ServiceDeskAdminPlugin` (Admin) - Full management capabilities
- `ServiceDeskAgentPlugin` (Agent) - Ticket handling and response
- `ServiceDeskUserPlugin` (User) - Self-service and ticket creation

### Resource Namespacing
Each context (Admin/Agent/User) has its own `TicketResource` with different schemas and behaviors:
- **Admin TicketResource**: Full CRUD, all fields, relation managers for comments/attachments/history/watchers
- **Agent TicketResource**: Scoped to assigned tickets, limited editing (status/priority), comments/attachments
- **User TicketResource**: Scoped to own tickets, create/view only, public comments only

### Service Delegation Pattern
Never use Eloquent directly for mutations. Always delegate to the service layer:

```php
// Correct - uses service layer
protected function handleRecordCreation(array $data): Model
{
    return app(TicketService::class)->create($data, auth()->user());
}

// Wrong - bypasses events and business rules
protected function handleRecordCreation(array $data): Model
{
    return Ticket::create($data);
}
```

### Dynamic Form Fields (Service Catalog)
The `CreateServiceRequest` page uses a wizard with dynamic form fields:
- Step 1: Select service
- Step 2: Dynamic form mapped from `ServiceFormField` → Filament components
- Step 3: Review and submit

The `mapFormField()` method converts `FormFieldType` enums to Filament form components.

## Common Tasks

### Adding a New Admin Resource
1. Create the resource class in `src/Admin/Resources/`
2. Create Pages (List, Create, Edit) in a subdirectory
3. Create RelationManagers if needed
4. Register in `ServiceDeskAdminPlugin::register()`
5. Add translation keys

### Extending a Resource
Override in `config/filament-service-desk.php` (panel-scoped shape):
```php
'admin' => [
    'resources' => [
        'ticket' => App\Filament\Resources\CustomTicketResource::class,
    ],
],
```

### Adding a Widget
1. Create widget class in `src/{Admin|Agent|User}/Widgets/`
2. Register in the corresponding plugin's `register()` method
3. Add translation keys for heading/labels

## File Structure
```
src/
├── ServiceDeskAdminPlugin.php     # Admin plugin
├── ServiceDeskAgentPlugin.php     # Agent plugin
├── ServiceDeskUserPlugin.php      # User plugin
├── Concerns/HasServiceDeskPluginConfig.php  # Shared trait
├── Admin/
│   ├── Resources/                 # 13 resources
│   └── Widgets/                   # Overview, SLA, Departments
├── Agent/
│   ├── Pages/                     # Queue, Dashboard
│   ├── Resources/                 # 2 resources
│   └── Widgets/                   # Stats, Breaches
└── User/
    ├── Pages/                     # Knowledge Base
    ├── Resources/                 # 2 resources
    └── Widgets/                   # My Tickets
```

## Dependencies
- `filament/filament: ^4.0`
- `jeffersongoncalves/laravel-service-desk: ^1.0`
- `spatie/laravel-package-tools: ^1.14.0`

## Testing
```bash
vendor/bin/pest
```
Tests use Orchestra Testbench with a `TestPanelProvider` fixture.
