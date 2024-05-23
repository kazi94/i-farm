<?php

namespace App\Filament\Resources\PreconisationResource\Pages;

use App\Filament\Resources\PreconisationResource;
use App\Filament\Resources\PreconisationResource\Widgets\StatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreconisations extends ListRecords
{
    protected static string $resource = PreconisationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class
        ];
    }
}
