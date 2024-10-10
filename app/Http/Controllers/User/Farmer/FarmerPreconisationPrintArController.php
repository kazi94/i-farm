<?php

namespace App\Http\Controllers\User\Farmer;

use App\Models\Farmer;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Preconisation;
use App\Http\Controllers\Controller;

class FarmerPreconisationPrintArController extends Controller
{
    public function __invoke(Farmer $farmer, Preconisation $preconisation)
    {
        $rest = $preconisation->load([
            'preconisationItems.intrant',
            'farmer',
            'farm',
            'createdBy',
            'preconisationItems.unit',
            'preconisationItems.intrant.intrantsCultures.depredateur',
        ]);
        $rest = [
            'id' => $preconisation->id,
            'date_preconisation' => $preconisation->date_preconisation,
            'total_amount' => $preconisation->total_amount,
            'note' => $preconisation->note,
            'createdBy' => $preconisation->createdBy,
            'farmer' => $preconisation->farmer,
            'farm' => $preconisation->farm,
            'preconisationItems' => $preconisation->preconisationItems->map(function ($item) use ($preconisation) {
                return [
                    'depredateur' => [
                        'id' => $item->intrant->intrantsCultures->where('culture_id', $preconisation->culture_id)->first()->depredateur->id,
                        'name' => $item->intrant->intrantsCultures->where('culture_id', $preconisation->culture_id)->first()->depredateur->name,
                    ],
                    'traitments' => [
                        [
                            'intrant' => $item->intrant->name_ar,
                            'dose_ar' => $item->dose_ar,
                            'usage_mode' => $item->usage_mode,
                            'price' => $item->price,
                            'unit' => $item->unit,
                            'quantity' => $item->quantity,
                        ],
                    ],
                ];
            })->groupBy('depredateur.id')->map(function ($items) {
                return [
                    'depredateur' => $items->first()['depredateur'],
                    'traitments' => $items->flatMap(function ($item) {
                        return $item['traitments'];
                    })->values()->toArray(),
                ];
            })->values()->toArray(),
        ];
        return view('users.farmers.pdfs.preconisation-fr', [
            'receipt' => $rest,
        ]);
        // $pdf = PDF::loadView('users.farmers.pdfs.preconisation-ar', [
        //     'receipt' => $preconisation,
        //     'items' => $preconisation->preconisationItems
        // ]);

        // return $pdf->stream($this->generateName($preconisation));

        return view('users.farmers.pdfs.preconisation-ar', [
            'receipt' => $preconisation,
            'items' => $preconisation->preconisationItems
        ]);

    }


    private function generateName(Preconisation $preconisation): string
    {
        return "preconisation-{$preconisation->farmer->fullname}-{$preconisation->preconisation_date}.pdf";
    }
}
