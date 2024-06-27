<?php

namespace App\Http\Controllers\User\Preconisation;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use App\Models\Preconisation;
use App\Http\Controllers\Controller;

class PreconisationPrintController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Preconisation $preconisation)
    {
        $preconisation->load(['preconisationItems.intrant', 'farmer.wilaya', 'farm.culture', 'farm.unit', 'createdBy']);
        $pdf = PDF::loadView('users.preconisations.pdfs.preconisation', [
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
