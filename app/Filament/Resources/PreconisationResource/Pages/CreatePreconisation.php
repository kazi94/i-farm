<?php

namespace App\Filament\Resources\PreconisationResource\Pages;

use App\Models\Farm;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PreconisationResource;

class CreatePreconisation extends CreateRecord
{
    protected static string $resource = PreconisationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['created_by'] = auth()->id();
        $data['farm_id'] = Farm::where('culture_id', $data['culture_id'])->get()->first()->id;

        return static::getModel()::create($data);
    }
}
