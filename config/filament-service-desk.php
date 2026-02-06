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
    | Set to null to use the default.
    |
    */

    'resources' => [
        'admin' => [
            'department' => null,
            'category' => null,
            'tag' => null,
            'canned_response' => null,
            'ticket' => null,
            'sla_policy' => null,
            'escalation_rule' => null,
            'business_hours_schedule' => null,
            'email_channel' => null,
            'kb_article' => null,
            'kb_category' => null,
            'service' => null,
            'service_category' => null,
        ],
        'agent' => [
            'ticket' => null,
            'canned_response' => null,
        ],
        'user' => [
            'ticket' => null,
            'service_request' => null,
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
