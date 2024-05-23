<?php

namespace App\Filament\Resources\PreconisationResource\Pages;

use App\Filament\Resources\PreconisationResource;
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
}
