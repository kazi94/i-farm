<?php


namespace App\Actions;


use App\Models\Preconisation;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;


class FrenchPrintPreconisationAction
{
    public static function create(): Action
    {
        return Action::make('printPreconisation')
            ->url(fn(Model $record) => route('farmer.preconisation-fr.print', [$record->farmer_id, $record->id]))
            ->label('Imprimer')
            ->color('secondary')
            ->icon('heroicon-o-printer')
            ->openUrlInNewTab();

    }
}
