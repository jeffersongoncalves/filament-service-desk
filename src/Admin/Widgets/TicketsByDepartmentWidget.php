<?php

namespace JeffersonGoncalves\FilamentServiceDesk\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use JeffersonGoncalves\ServiceDesk\Models\Department;

class TicketsByDepartmentWidget extends ChartWidget
{
    protected ?string $heading = null;

    protected static ?int $sort = 3;

    public function getHeading(): ?string
    {
        return __('filament-service-desk::service-desk.widgets.tickets_by_department.heading');
    }

    protected function getData(): array
    {
        $departments = Department::withCount('tickets')
            ->where('is_active', true)
            ->orderByDesc('tickets_count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('filament-service-desk::service-desk.widgets.tickets_by_department.tickets'),
                    'data' => $departments->pluck('tickets_count')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#ef4444', '#f59e0b', '#10b981', '#8b5cf6',
                        '#ec4899', '#06b6d4', '#84cc16', '#f97316', '#6366f1',
                    ],
                ],
            ],
            'labels' => $departments->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
