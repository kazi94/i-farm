<?php


namespace App\Actions;


use App\Models\Preconisation;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;


class ArabicPrintPreconisationAction
{
    public static function create(): Action
    {
        return Action::make('printPreconisation(ar)')
            ->url(fn(Model $record) => route('farmer.preconisation-ar.print', [$record->farmer_id, $record->id]))
            ->label('طباعة')
            ->color('')
            ->icon('heroicon-o-printer')
            ->openUrlInNewTab();

    }
}
