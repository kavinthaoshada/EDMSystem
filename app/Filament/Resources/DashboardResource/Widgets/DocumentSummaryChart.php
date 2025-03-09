<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Document;
use Illuminate\Support\Carbon;

class DocumentSummaryChart extends ChartWidget
{
    protected static ?string $heading = 'Document Summary';
    protected static string $type = 'doughnut';

    protected function getData(): array
    {
        $expiredCount = Document::whereNotNull('expiry_date')
            ->where('expiry_date', '<', Carbon::today())
            ->count();

        $validCount = Document::where(function ($query) {
            $query->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', Carbon::today());
        })->count();

        return [
            'datasets' => [
                [
                    'data' => [$expiredCount, $validCount],
                    'backgroundColor' => ['#23AF69', '#9DBD0D'],
                    'hoverBackgroundColor' => ['#1C8C54', '#85A00B'],
                ],
            ],
            'labels' => ['Expired Documents', 'Valid Documents'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'cutout' => '70%',
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'animation' => [
                'animateRotate' => true,
                'animateScale' => true,
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
