<?php

namespace App\Imports;

use App\Models\Farmer;
use App\Imports\FarmsSheetImport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FarmersImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'AGRICULTEURS' => new FarmersSheetImport(),
            'CULTURES' => new FarmsSheetImport(),
        ];
    }
}
