# Changelog

All notable changes to `filament-service-desk` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed

- Add `build/` and `.claude/` to `.gitignore`

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
