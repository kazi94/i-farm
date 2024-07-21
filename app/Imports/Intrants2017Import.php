<?php

namespace App\Imports;

use App\Imports\TenSheetImport;
use App\Imports\NineSheetImport;
use App\Imports\FifthSheetImport;
use App\Imports\FirstSheetImport;
use App\Imports\SevenSheetImport;
use App\Imports\SixthSheetImport;
use App\Imports\ThirdSheetImport;
use App\Imports\EighttSheetImport;
use App\Imports\ElevenSheetImport;
use App\Imports\FourthSheetImport;
use App\Imports\SecondSheetImport;
use App\Imports\TwelveSheetImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Intrants2017Import implements WithMultipleSheets, WithProgressBar
{

    use Importable;

    public function sheets(): array
    {
        return [
            'INSECTICIDES' => new FirstSheetImport(),
            'ACARICIDES' => new SecondSheetImport(),
            'FONGICIDES' => new ThirdSheetImport(),
            'HERBICIDES' => new FourthSheetImport(),
            'LIMATICIDES' => new FifthSheetImport(),
            'ADJUVANTS' => new SixthSheetImport(),
            'STIMULANTS' => new SevenSheetImport(),
            'RODENTICIDES' => new EighttSheetImport(),
            'NEMATICIDES' => new NineSheetImport(),
            'REGULATEURS DE CROISSANCES ' => new TenSheetImport(),
            'INHIBITEURS DE LA GERMINATION' => new ElevenSheetImport(),
            'AUTRES' => new TwelveSheetImport(),
        ];
    }

}
