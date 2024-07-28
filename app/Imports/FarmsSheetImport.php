<?php

namespace App\Imports;

use App\Models\Farm;
use App\Models\Unit;
use App\Models\Farmer;
use App\Models\Culture;
use App\Models\CultureSetting;
use App\Models\CultureVariante;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class FarmSSheetImport implements ToCollection, WithHeadingRow, WithProgressBar
{
    use Importable;
    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            if ($row['agriculteur']) {

                $farmer_id = $row['agriculteur'] ? Farmer::where('fullname', $row['agriculteur'])->first()->id : null;
                $farm = Farm::create([
                    'code' => 'FARM0000' . Farm::count() + 1,
                    'area' => $row['superficie'] ?? 0,
                    'density' => $row['densite'],
                    'age ' => $row['age'],
                    'distance_tree' => $row['distance_arbre'],
                    'distance_line' => $row['distance_ligne'],
                    'culture_id' => Culture::where('name', $row['culture'])->first()->id ?? null,
                    'culture_setting_id' => CultureSetting::where('name', $row['parametre'])->first()->id ?? null,
                    'culture_variante_id' => CultureVariante::where('name', $row['famille'])->first()->id ?? null,
                    'unit_id' => Unit::where('name', $row['unite'])->first()->id ?? null,
                    'farmer_id' => $farmer_id,
                ]);
            }
        }
    }
}
