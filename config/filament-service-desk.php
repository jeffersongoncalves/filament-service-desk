<?php

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
            'department' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Departments\DepartmentResource::class,
            'category' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Categories\CategoryResource::class,
            'tag' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tags\TagResource::class,
            'canned_response' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\CannedResponses\CannedResponseResource::class,
            'ticket' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Tickets\TicketResource::class,
            'sla_policy' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\SlaPolicies\SlaPolicyResource::class,
            'escalation_rule' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EscalationRules\EscalationRuleResource::class,
            'business_hours_schedule' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\BusinessHoursSchedules\BusinessHoursScheduleResource::class,
            'email_channel' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\EmailChannels\EmailChannelResource::class,
            'kb_article' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbArticles\KbArticleResource::class,
            'kb_category' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\KbCategories\KbCategoryResource::class,
            'service' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\Services\ServiceResource::class,
            'service_category' => \JeffersonGoncalves\FilamentServiceDesk\Admin\Resources\ServiceCategories\ServiceCategoryResource::class,
        ],
        'agent' => [
            'ticket' => \JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\Tickets\TicketResource::class,
            'canned_response' => \JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\CannedResponses\CannedResponseResource::class,
        ],
        'user' => [
            'ticket' => \JeffersonGoncalves\FilamentServiceDesk\User\Resources\Tickets\TicketResource::class,
            'service_request' => \JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequests\ServiceRequestResource::class,
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

];
