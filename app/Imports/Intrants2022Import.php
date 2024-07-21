<?php

namespace App\Imports;

use App\Imports\FirstSheetImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Intrants2022Import implements WithMultipleSheets, WithProgressBar
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
            'AUTRES' => new TenSheetImport(),
        ];
    }

}
