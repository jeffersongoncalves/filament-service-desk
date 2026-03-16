<?php

use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursScheduleResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\CannedResponseResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\CategoryResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\DepartmentResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannelResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRuleResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticleResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbCategoryResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\ServiceCategoryResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\ServiceResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\SlaPolicyResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\TagResource;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\TicketResource;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequestResource;

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    'navigation' => [
        'admin' => [
            'group' => 'Service Desk',
            'sort' => null,
            'icon' => 'heroicon-o-lifebuoy',
        ],
        'agent' => [
            'group' => 'Service Desk',
            'sort' => null,
            'icon' => 'heroicon-o-headset',
        ],
        'user' => [
            'group' => 'Support',
            'sort' => null,
            'icon' => 'heroicon-o-ticket',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Toggle features on/off globally. These can also be toggled per-plugin
    | using fluent methods on the plugin classes.
    |
    */

    'features' => [
        'knowledge_base' => true,
        'sla' => true,
        'email_channels' => true,
        'service_catalog' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | Override the default resource classes used by the plugins.
    | Set to null to disable a resource completely (removes navigation and routes).
    |
    */

    'resources' => [
        'admin' => [
            'department' => DepartmentResource::class,
            'category' => CategoryResource::class,
            'tag' => TagResource::class,
            'canned_response' => CannedResponseResource::class,
            'ticket' => TicketResource::class,
            'sla_policy' => SlaPolicyResource::class,
            'escalation_rule' => EscalationRuleResource::class,
            'business_hours_schedule' => BusinessHoursScheduleResource::class,
            'email_channel' => EmailChannelResource::class,
            'kb_article' => KbArticleResource::class,
            'kb_category' => KbCategoryResource::class,
            'service' => ServiceResource::class,
            'service_category' => ServiceCategoryResource::class,
        ],
        'agent' => [
            'ticket' => JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\TicketResource::class,
            'canned_response' => JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\CannedResponseResource::class,
        ],
        'user' => [
            'ticket' => JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource::class,
            'service_request' => ServiceRequestResource::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    |
    | Override the default widget classes. Set to null to use the default.
    |
    */

    'widgets' => [
        'admin' => [
            'overview' => null,
            'sla_compliance' => null,
            'tickets_by_department' => null,
        ],
        'agent' => [
            'ticket_stats' => null,
            'sla_breach' => null,
        ],
        'user' => [
            'my_tickets_overview' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Attachments
    |--------------------------------------------------------------------------
    |
    | Configure file upload settings for ticket attachments.
    | The allowed_extensions are resolved to MIME types using Symfony MimeTypes.
    |
    */

    'attachments' => [
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'zip', 'rar'],
        'max_file_size' => 10240, // in KB (10MB)
        'disk' => 'local',
    ],

];
