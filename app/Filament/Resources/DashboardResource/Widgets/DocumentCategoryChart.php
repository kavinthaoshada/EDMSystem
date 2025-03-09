<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Document;
use App\Models\Category;

class DocumentCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Documents Per Category';
    protected static string $type = 'bar';

    protected function getData(): array
    {
        $categories = Category::withCount('documents')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Number of Documents',
                    'data' => $categories->pluck('documents_count')->toArray(),
                    'backgroundColor' => '#59C3C3',
                    'borderColor' => '#388E3C',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $categories->pluck('category_name')->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'animation' => [
                'animateScale' => true,
                'animateRotate' => true,
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
