<?php

namespace App\Imports;

use App\Models\Firm;
use App\Models\Unit;
use App\Models\Culture;
use App\Models\Intrant;
use App\Models\Depredateur;
use App\Models\Distributor;
use App\Models\PrincipeActif;
use App\Models\IntrantCategory;
use Illuminate\Support\Collection;
use App\Models\IntrantSousCategory;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class EngraisImport implements ToCollection, WithHeadingRow, WithProgressBar
{
    use Importable;
    public function collection(Collection $rows)
    {
        // intrant belongs to many sousIntrantCategory
        $sousIntrantCateg = IntrantSousCategory::where('name', 'stimulants')->first();
        $prevIntrant = null;
        foreach ($rows as $row) {
            $intrant = strtolower($row['nom_commercial']);
            $principesAc = strtolower($row['matiere_active']);
            $depredateur = strtolower($row['type']);
            // check if intrant is not null
            if (!is_null($intrant)) {

                // Check if the current intrant is different from the previous intrant to avoid intrant duplicates
                if (is_null($prevIntrant) || strcmp($intrant, strtolower($prevIntrant->name_fr)) !== 0) {

                    // Get or create the intrant
                    $intrant = $this->getOrCreateIntrant($intrant, $sousIntrantCateg, null, null, null, null);

                    // Attach intrnat to Principes Actifs
                    $this->attachPrincipesActifs($intrant, $principesAc);

                } else {
                    $intrant = $prevIntrant;
                }


                $depredateur = $this->getOrCreateDepredateur($depredateur); // get or create the depredateur



                $intrant->intrantsCultures()->create(
                    [
                        'depredateur_id' => $depredateur->id,
                    ]
                );

                $prevIntrant = $intrant; // intrant is an ORM object
            }
        }
    }



    // Function to get or create a depredateur
    private function getOrCreateDepredateur($depredateur)
    {
        $depredateur = strtolower(trim($depredateur));

        // remove speciale character for example 'algérie' to 'algerie'
        $depredUpdated = preg_replace('/[^A-Za-z0-9\-]/', '', $depredateur);

        // retrieve Distributor from database
        $dbDepredateur = Depredateur::where('name', $depredateur)->first();

        if ($dbDepredateur) {
            $dbDepredateur->name = preg_replace('/[^A-Za-z0-9\-]/', '', $dbDepredateur->name);

            if ($depredUpdated == $dbDepredateur->name) { // check if the name of the Distributor in the database is the same as the name of the Distributor in the excel
                return $dbDepredateur;
            }

        }

        // create Distributor in database
        return Depredateur::firstOrCreate(['name' => $depredateur], ['name' => $depredateur]);

    }


    // Function to get or create an intrant
    private function getOrCreateIntrant($name, $sousIntrantCateg, $formulation, $homologationNumber, $firm, $representant)
    {
        $name = strtolower(trim($name));

        return Intrant::firstOrCreate([
            'name_fr' => $name,
            'intrant_category_id' => $sousIntrantCateg->intrant_category_id,
            'intrant_sous_category_id' => $sousIntrantCateg->id,
            'formulation' => $formulation,
            'homologation_number' => $homologationNumber,
            'firm_id' => $firm ? $firm->id : null,
            'distributor_id' => $representant ? $representant->id : null
        ], ['name' => $name]);
    }
    /**
     * Attach the given principes to the given intrant.
     *
     * @param  \App\Models\Intrant  $intrant
     * @param  string  $principes
     * @return void
     */
    private function attachPrincipesActifs($intrant, $principes, )
    {
        // concentrations is a string seperated by '+'
        $principes = explode('+', $principes);

        // the format of principe is  for example : 13k20
        // we want to separate the number from the unit and store them

        foreach ($principes as $principe) {
            $unit = null;
            $matches = [];
            preg_match('/(\d+)([a-zA-Z]+)/', $principe, $matches);
            $number = $matches[1] ?? null;
            $principeName = $matches[2] ?? null;

            if (str_contains($principeName, 'ppm')) {
                $unit = Unit::firstOrCreate(['name' => 'ppm']);
                $unit = $this->getOrCreateUnit($unit);
                $principeName = str_replace('ppm', '', $principeName);
            }

            $principeActif = $this->getOrCreatePrincipeActif($principeName);



            $intrant->intrantsPrincipesActifs()->create(
                [
                    'principe_actif_id' => $principeActif->id,
                    'concentration' => $number,
                    'unit_id' => $unit ? $unit->id : null
                ]
            );

        }


    }

    // Function to get or create a principeActif
    private function getOrCreatePrincipeActif($principe)
    {
        $principe = strtolower(trim($principe));
        // remove speciale character for example 'algérie' to 'algerie' and make all letters lowercase
        $principeUp = preg_replace('/[^A-Za-z0-9\-]/', '', $principe);

        // retrieve principe ac from database
        $dbPrincipe = PrincipeActif::where('name_fr', $principe)->first();

        if ($dbPrincipe) {
            $dbPrincipe->name_fr = preg_replace('/[^A-Za-z0-9\-]/', '', $dbPrincipe->name_fr);

            if ($principeUp == $dbPrincipe->name_fr) { // check if the name_fr of the principe ac in the database is the same as the name_fr of the principe ac in the excel
                return $dbPrincipe;
            }

        }

        // create principe ac in database
        return PrincipeActif::firstOrCreate(['name_fr' => $principe], ['name_fr' => $principe]);
    }

    // Function to get or create a unit
    private function getOrCreateUnit($unit)
    {
        $unit = strtolower(trim($unit));

        // remove speciale character for example 'algérie' to 'algerie' and make all letters lowercase
        $unitUp = preg_replace('/[^A-Za-z0-9\-]/', '', $unit);

        // retrieve unit from database
        $dbUnit = Unit::where('name', $unit)->first();

        if ($dbUnit) {
            $dbUnit->name = preg_replace('/[^A-Za-z0-9\-]/', '', $dbUnit->name);

            if ($unitUp == $dbUnit->name) { // check if the name of the unit in the database is the same as the name of the unit in the excel
                return $dbUnit;
            }

        }

        // create unit in database
        return Unit::firstOrCreate(['name' => $unit], ['name' => $unit]);

    }
}
