<?php

namespace App\Filament\Resources\FarmerResource\Pages;

use Filament\Actions;
use App\Models\Farmer;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\FarmerResource;
use Filament\Resources\Pages\ContentTabPosition;

class EditFarmer extends EditRecord
{
    protected static ?string $badge = 'new';
    protected static string $resource = FarmerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabIcon(): ?string
    {
        return 'heroicon-m-cog';
    }

    public function getContentTabPosition(): ?ContentTabPosition
    {
        return ContentTabPosition::After;
    }
}
