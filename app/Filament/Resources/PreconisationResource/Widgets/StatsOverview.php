<?php

namespace App\Filament\Resources\PreconisationResource\Widgets;

use App\Models\Preconisation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Préconisations', Preconisation::count()),
            Stat::make('Montants totals', Preconisation::totalAmount() . ' DA'),
            // Stat::make('Montants moyens', Preconisation::averageAmount()),
            // Stat::make('Dernière préconisation', Preconisation::lastOne() ? Preconisation::lastOne()->date_preconisation : 'Aucune'),
        ];
    }
}
