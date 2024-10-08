<?php

namespace App\Http\Controllers\User\Farmer;

use App\Models\Farmer;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Preconisation;
use App\Http\Controllers\Controller;

class FarmerPreconisationPrintFrController extends Controller
{
    public function __invoke(Farmer $farmer, Preconisation $preconisation)
    {
        $preconisation->load(['preconisationItems.intrant', 'farmer', 'farm', 'createdBy', 'preconisationItems.unit']);

        $pdf = PDF::loadView('users.farmers.pdfs.preconisation-fr', [
            'receipt' => $preconisation,
            'items' => $preconisation->preconisationItems
        ]);

        return $pdf->stream($this->generateName($preconisation));

    }


    private function generateName(Preconisation $preconisation): string
    {
        return "preconisation-{$preconisation->farmer->fullname}-{$preconisation->preconisation_date}.pdf";
    }
}
