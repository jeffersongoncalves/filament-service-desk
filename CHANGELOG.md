# Changelog

All notable changes to `filament-service-desk` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 3.4.0 - 2026-09-21

### What's Changed

* feat: replace custom Kanban board with jeffersongoncalves/filament-kanban by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-service-desk/pull/53

**Full Changelog**: https://github.com/jeffersongoncalves/filament-service-desk/compare/3.3.0...3.4.0

## 3.3.0 - 2026-09-21

### Added

- **Multi-app support**: `app_key` column + filter on the Admin Tickets table, for installs sharing one database across multiple apps. See #22.
- **Satellite mode**: the User panel (create/view/close/reopen a ticket, attachments) now fully works under `service-desk.ticket.transport = api`. Admin/Agent panels refuse to register under that transport with a clear error instead of silently rendering broken. See #23, #35.
- Optional Kanban board for the Agent panel (drag-and-drop). See #15.
- Knowledge Base deflection MVP on ticket creation — suggested articles as you type. See #19.
- "Test Connection" action on the EmailChannel form (Admin). See #20.
- Status pipeline stepper on the User ticket view. See #21.
- Inline claim action on the Agent tickets table, standardized empty states across User/Agent, and a visual highlight for internal notes on the comments list. See #16, #17, #18.
- CI: lint/PHPStan/Pest pipeline testing against Laravel 13, Dependabot (weekly, grouped, no auto-merge), automated `CHANGELOG.md` on release. See #12, #13, #14.

### Fixed

- `seo_keywords` on Knowledge Base articles no longer throws "Array to string conversion" (the core column is a plain string, the form now round-trips it correctly). See #10, #24.
- `DayOfWeek` `TypeError` when listing Business Hours time slots (the column is already cast to the enum by the model). See #11.
- Status pipeline stepper crashed the User ticket view (`$state` isn't available in a schema component's view — read it via `$getState()` instead). See #46.

### Changed

- Requires `jeffersongoncalves/laravel-service-desk: ^1.1`.

### Known issue

- The Kanban board uses a hand-rolled drag-and-drop instead of `jeffersongoncalves/filament-kanban` (the package the sibling `filament-help-desk` uses for this) -- tracked in #48.

## [Unreleased]

## [3.0.0] - 2026-02-06

### Changed

- **BREAKING:** Requires Filament ^5.0 (Livewire v4)
- **BREAKING:** Requires Laravel 11.28+
- Update `orchestra/testbench` to `^10.0|^11.0`

## [2.0.0] - 2026-02-06

### Changed

- **BREAKING:** Requires PHP ^8.2, Filament ^4.0, Laravel 11+
- **BREAKING:** Heroicon strings in `->icon()` calls replaced with `Filament\Support\Icons\Heroicon` enum
- **BREAKING:** Heroicon strings in Blade `icon=""` attributes replaced with `:icon="Heroicon::..."` enum binding
- Replace `->reactive()` with `->live()` across all form components
- Register CSS asset via `FilamentAsset` for Filament v4 compatibility
- Add `package.json` with PostCSS build pipeline for custom styles
- Add `pnpm-lock.yaml` to `.gitignore`

### Migration Guide

If upgrading from 1.x, ensure your project meets these requirements:

- PHP 8.2 or higher
- Laravel 11 or higher
- Filament 4.x

## [1.0.2] - 2026-02-06

### Added

- Dedicated `infolist()` for Admin TicketResource (2+1 column layout with SLA section)
- Dedicated `infolist()` for Agent TicketResource (2+1 column layout)
- Dedicated `infolist()` for User TicketResource (simple layout)
- Dedicated `infolist()` for User ServiceRequestResource (with KeyValueEntry for form_data)
- Agent ticket queue claim now redirects to the claimed ticket view page

## [1.0.1] - 2026-02-06

### Changed

- Restructure folders for multi-panel to fix duplicate URL slugs

## [1.0.0] - 2026-02-06

### Added

- Initial release
- **Admin Panel**: DepartmentResource, CategoryResource, TagResource, CannedResponseResource, TicketResource (with relation managers for comments, attachments, history, watchers), SLA resources, EmailChannelResource, KnowledgeBase resources, ServiceCatalog resources
- **Agent Panel**: TicketResource, CannedResponseResource, TicketQueuePage, AgentDashboardPage
- **User Panel**: TicketResource, ServiceRequestResource (with wizard), KnowledgeBasePage
- Widgets for all panels
- English and Portuguese (pt_BR) translations
- Laravel Boost integration
- Test infrastructure with Pest
- Service provider, config, and plugin classes
