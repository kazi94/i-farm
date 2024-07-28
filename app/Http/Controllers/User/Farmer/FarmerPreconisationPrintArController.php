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
        $preconisation->load(['preconisationItems.intrant', 'farmer', 'farm', 'createdBy']);

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
