<?php

namespace App\Filament\Resources\FarmerResource\Widgets;

use App\Models\Farmer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Agriculteurs', Farmer::count()),
        ];
    }
}
