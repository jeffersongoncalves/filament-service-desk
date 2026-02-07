<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use JeffersonGoncalves\ServiceDesk\Models\TicketSla;

class SlaComplianceWidget extends ChartWidget
{
    protected ?string $heading = null;

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament-service-desk::service-desk.widgets.sla_compliance.heading');
    }

    protected function getData(): array
    {
        $totalWithSla = TicketSla::count();

        if ($totalWithSla === 0) {
            return [
                'datasets' => [
                    [
                        'label' => __('filament-service-desk::service-desk.widgets.sla_compliance.compliance'),
                        'data' => [0, 0],
                    ],
                ],
                'labels' => [
                    __('filament-service-desk::service-desk.widgets.sla_compliance.met'),
                    __('filament-service-desk::service-desk.widgets.sla_compliance.breached'),
                ],
            ];
        }

        $breached = TicketSla::where('first_response_breached', true)
            ->orWhere('resolution_breached', true)
            ->count();

        $met = $totalWithSla - $breached;

        return [
            'datasets' => [
                [
                    'label' => __('filament-service-desk::service-desk.widgets.sla_compliance.compliance'),
                    'data' => [$met, $breached],
                    'backgroundColor' => ['#10b981', '#ef4444'],
                ],
            ],
            'labels' => [
                __('filament-service-desk::service-desk.widgets.sla_compliance.met'),
                __('filament-service-desk::service-desk.widgets.sla_compliance.breached'),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
