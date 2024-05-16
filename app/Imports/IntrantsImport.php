<?php

namespace App\Imports;

use App\Models\Product;
use App\Imports\FirstSheetImport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class IntrantsImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'INSECTICIDES' => new FirstSheetImport(),
        ];
    }

}
