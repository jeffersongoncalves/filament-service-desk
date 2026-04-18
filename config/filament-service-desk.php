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
use JeffersonGoncalves\FilamentServiceDesk\Admin\Widgets\ServiceDeskOverviewWidget;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Widgets\SlaComplianceWidget;
use JeffersonGoncalves\FilamentServiceDesk\Admin\Widgets\TicketsByDepartmentWidget;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Widgets\AgentTicketStatsWidget;
use JeffersonGoncalves\FilamentServiceDesk\Agent\Widgets\SlaBreachWidget;
use JeffersonGoncalves\FilamentServiceDesk\User\Resources\ServiceRequestResource;
use JeffersonGoncalves\FilamentServiceDesk\User\Widgets\MyTicketsOverviewWidget;

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Configuration
    |--------------------------------------------------------------------------
    */
    'admin' => [
        'navigation_group' => 'Service Desk',
        'navigation_icon' => 'heroicon-o-lifebuoy',
        'navigation_sort' => null,
        'resources' => [
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
        'widgets' => [
            ServiceDeskOverviewWidget::class,
            SlaComplianceWidget::class,
            TicketsByDepartmentWidget::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Agent Panel Configuration
    |--------------------------------------------------------------------------
    */
    'agent' => [
        'navigation_group' => 'Service Desk',
        'navigation_icon' => 'heroicon-o-headset',
        'navigation_sort' => null,
        'resources' => [
            'ticket' => JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\TicketResource::class,
            'canned_response' => JeffersonGoncalves\FilamentServiceDesk\Agent\Resources\CannedResponseResource::class,
        ],
        'widgets' => [
            AgentTicketStatsWidget::class,
            SlaBreachWidget::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Panel Configuration
    |--------------------------------------------------------------------------
    */
    'user' => [
        'navigation_group' => 'Support',
        'navigation_icon' => 'heroicon-o-ticket',
        'navigation_sort' => null,
        'resources' => [
            'ticket' => JeffersonGoncalves\FilamentServiceDesk\User\Resources\TicketResource::class,
            'service_request' => ServiceRequestResource::class,
        ],
        'widgets' => [
            MyTicketsOverviewWidget::class,
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
