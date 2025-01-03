<?php

namespace App\Imports;

use App\Imports\FirstSheetImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class IntrantsImport implements WithMultipleSheets, WithProgressBar
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
            'INSECTICIDES (2)' => new FirstSheetImport(),
            'ACARICIDES (2)' => new SecondSheetImport(),
            'FONGICIDES (2)' => new ThirdSheetImport(),
            'HERBICIDES (2)' => new FourthSheetImport(),
            'LIMATICIDES (2)' => new FifthSheetImport(),
            'ADJUVANTS (2)' => new SixthSheetImport(),
            'STIMULANTS_2' => new SevenSheetImport(),
            'RODENTICIDES (2)' => new EighttSheetImport(),
            'NEMATICIDES (2)' => new NineSheetImport(),
            'AUTRES (2)' => new TenSheetImport(),
            'ENGRAIS' => new EngraisImport(),
        ];
    }

}
