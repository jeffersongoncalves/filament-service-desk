# Changelog

All notable changes to `filament-service-desk` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 2.4.0 - 2026-09-21

### What's Changed

* ci: run the changelog updater on this branch, backfill 2.3.0 by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-service-desk/pull/52
* feat: back the Agent Kanban board with jeffersongoncalves/filament-kanban by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-service-desk/pull/55

**Full Changelog**: https://github.com/jeffersongoncalves/filament-service-desk/compare/2.3.0...2.4.0

## 2.3.0 - 2026-09-21

Ports everything shipped on `3.x` in v3.3.0 (see #39, #42).

### Added

- **Multi-app support**: `app_key` column + filter on the Admin Tickets table, for installs sharing one database across multiple apps.
- **Satellite mode**: the User panel (create/view/close/reopen a ticket, attachments) now fully works under `service-desk.ticket.transport = api`. Admin/Agent panels refuse to register under that transport with a clear error instead of silently rendering broken.
- Optional Kanban board for the Agent panel (drag-and-drop).
- Knowledge Base deflection MVP on ticket creation — suggested articles as you type.
- "Test Connection" action on the EmailChannel form (Admin).
- Status pipeline stepper on the User ticket view.
- Inline claim action on the Agent tickets table, standardized empty states across User/Agent, and a visual highlight for internal notes on the comments list.
- CI: lint/PHPStan/Pest pipeline testing against Laravel 13.

### Fixed

- `seo_keywords` on Knowledge Base articles no longer throws "Array to string conversion".
- `DayOfWeek` `TypeError` when listing Business Hours time slots.

### Changed

- Requires `jeffersongoncalves/laravel-service-desk: ^1.1`.

### Known issue

- The Kanban board uses a hand-rolled drag-and-drop instead of `jeffersongoncalves/filament-kanban` (the package the sibling `filament-help-desk` uses for this) -- tracked in #49.

## [Unreleased]

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
