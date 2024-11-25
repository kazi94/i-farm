<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;


class TestController extends Controller
{
    public function __invoke()
    {


        $fullName = "fullname";
        $fileName = 'hello.png';
        $filePath = storage_path('app/public/' . $fileName);
        // Generate the QR code with the full name
        return $qrCode = QrCode::encoding('UTF-8')->format('png')->size(400)->generate($fullName, $filePath);
        // Generate the PDF
        $image = file_get_contents($filePath);
        // $pdf = Pdf::loadView('pdf', compact('fullName', 'image'));
        // Download the generated PDF
        // return $pdf->download($fullName . '_GP.pdf');
    }
}
