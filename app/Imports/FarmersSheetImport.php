<?php

namespace App\Imports;

use App\Models\Daira;
use App\Models\Farmer;
use App\Models\Wilaya;
use App\Models\Commune;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class FarmersSheetImport implements ToCollection, WithHeadingRow, WithProgressBar
{
    use Importable;
    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            $wilaya_id = $row['wilaya'] ? Wilaya::where('name', $row['wilaya'])->first()->id : null;
            $commune_id = $row['commune'] ? Commune::where('name', $row['commune'])->first()->id : null;
            $daira_id = $row['daira'] ? Daira::where('name', $row['daira'])->first()->id : null;

            // Check if farmer exists, then update or create
            $farmer = Farmer::where('fullname', $row['nom_complet'])->first();
            if ($farmer) {
                $farmer->update([
                    'fullname' => $row['nom_complet'],
                    'wilaya_id' => $wilaya_id,
                    'daira_id' => $daira_id,
                    'commune_id' => $commune_id,
                    'note' => $row['note'],
                    'latitude' => $row['latitude'],
                    'longitude' => $row['longitude'],
                ]);
            } else {
                if ($row['nom_complet']) {
                    $farmer = Farmer::create([
                        'code' => Farmer::generateCode(),
                        'fullname' => $row['nom_complet'],
                        'wilaya_id' => $wilaya_id,
                        'daira_id' => $daira_id,
                        'commune_id' => $commune_id,
                        'note' => $row['note'],
                        'latitude' => $row['latitude'],
                        'longitude' => $row['longitude'],
                    ]);

                    // Create new contact
                    $farmer->contacts()->create([
                        'name' => $row['nom_complet'],
                        'phone' => $row['tel'],
                        'email' => $row['email'],
                    ]);
                }
            }
        }
    }
}
