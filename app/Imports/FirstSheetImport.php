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
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class FirstSheetImport implements ToCollection, WithHeadingRow, WithProgressBar
{
    use Importable;
    public function collection(Collection $rows)
    {
        // intrant belongs to many sousIntrantCategory
        $sousIntrantCateg = IntrantSousCategory::where('name', 'insecticides')->first();
        $prevIntrant = null;
        foreach ($rows as $row) {
            $cultures[] = strtolower($row['firmes']);

            $intrant = strtolower($row['nom_commercial']);
            $principesAc = strtolower($row['matiere_active']);
            $concentrations = strtolower($row['concentration']);
            $formulation = $row['formulation'];
            $depredateurs = strtolower($row['depredateurs']);
            $cultures = strtolower($row['cultures']);
            $dosesUnit = strtolower($row['doses_dutilisation']);
            $dar = $row['dar'];
            $observation = $row['observation'] && $row['observation'] != ' ' ? $row['observation'] : null;
            $n_dhomologation = $row['n_dhomologation'];
            $firme = rtrim(strtolower($row['firmes']), '.');
            $representant = strtolower($row['representant']);
            // Check if the current intrant is different from the previous intrant to avoid intrant duplicates
            if (is_null($prevIntrant) || strcmp($intrant, strtolower($prevIntrant->name_fr)) !== 0) {


                // intrant belongs to firme, check if the firm exists then return id
                $firme = $firme ? $this->getOrCreateFirm($firme) : null;

                // intrant belongs to distributeur, check if the distributeur exists then return id
                $representant = $representant ? $this->getOrCreateDistributeur($representant) : null;

                if ($firme && $representant)
                    $representant->firms()->attach($firme); // representant belongs to many firms

                // Get or create the intrant
                $intrant = $this->getOrCreateIntrant($intrant, $sousIntrantCateg, $formulation, $n_dhomologation, $firme, $representant);

                // Attach intrnat to Principes Actifs
                $this->attachPrincipesActifs($intrant, $principesAc, $concentrations);

            } else {
                $intrant = $prevIntrant;
            }

            // depredateurs is a string seperated by '/'
            $depredateurs = explode('/', $depredateurs);

            // cultures is a string seperated by '/'
            $cultures = explode('/', $cultures);

            // doses is a string seperated by ' ' the first one is the value and the second one is the unit
            $dosesUnit = explode(' ', $dosesUnit);

            // doses is a string seperated by '-'
            $doses = explode('-', $dosesUnit[0]);
            $unit = isset($dosesUnit[1]) ? $dosesUnit[1] : null;

            // dar is a string seperated by '-'

            $dar = explode('-', $dar);




            foreach ($depredateurs as $depredateur) {

                $depredateur = $this->getOrCreateDepredateur($depredateur); // get or create the depredateur

                foreach ($cultures as $key => $culture) {

                    $culture = $this->getOrCreateCulture($culture); // get or create the culture

                    $doseMin = isset($doses[0]) ? floatval(str_replace(',', '.', $doses[0])) : null;
                    $doseMax = isset($doses[1]) ? floatval(str_replace(',', '.', $doses[1])) : floatval(str_replace(',', '.', $doses[0]));

                    $darMin = isset($dar[0]) && $dar[0] !== '' ? $dar[0] : null;
                    $darMax = isset($dar[1]) ? $dar[1] : (isset($dar[0]) && $dar[0] !== '' ? $dar[0] : null);


                    $intrant->intrantsCultures()->create(
                        [
                            'culture_id' => $culture->id,
                            'depredateur_id' => $depredateur->id,
                            'dose_min' => $doseMin,
                            'dose_max' => $doseMax,
                            'unit_id' => $unit ? $this->getOrCreateUnit($unit)->id : null,
                            'dar_min' => $darMin,
                            'dar_max' => $darMax,
                            'observation' => $observation,
                        ]
                    );
                }
            }

            $prevIntrant = $intrant; // intrant is an ORM object
        }
        // $tmpCulture = [];
        // // for each culture get culture seperated by '/'
        // foreach ($cultures as $value) {
        //     // retrieve words seperated by '/'
        //     $words = explode('/', $value);
        //     foreach ($words as $word) {

        //         $tmpCulture[] = strtolower($word);
        //     }
        // }

        // $tmpCulture = array_unique($tmpCulture);

        // sort($tmpCulture);

        // //var_dump($tmpCulture);
        // // write in to test.json file
        // $json = json_encode($tmpCulture, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // // if the file is not empty then append it else create it
        // if (file_exists(database_path('data/cultures.json'))) {
        //     // loop through the data in the file
        //     $data = file_get_contents(database_path('data/cultures.json'));

        //     $data = json_decode($data, true);

        //     $data = array_merge($data, $tmpCulture);

        //     $data = array_unique($data);
        //     sort($data);

        //     $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        //     file_put_contents(database_path('data/cultures.json'), $json);
        // } else {
        //     file_put_contents(database_path('data/cultures.json'), $json);
        // }


    }
    // Function to get or create a culture
    private function getOrCreateCulture($culture)
    {
        $culture = strtolower(trim($culture));

        // remove speciale character for example 'algèreie' to 'algerie'
        $cultureUpdated = preg_replace('/[^A-Za-z0-9\-]/', '', $culture);

        // retrieve culture from database
        $dbCulture = Culture::where('name', $culture)->first();

        if ($dbCulture) {
            $dbCulture->name = preg_replace('/[^A-Za-z0-9\-]/', '', $dbCulture->name);

            if ($cultureUpdated == $dbCulture->name) { // check if the name of the culture in the database is the same as the name of the culture in the excel
                return $dbCulture;
            }
        }

        // create culture in database
        return Culture::firstOrCreate(['name' => $culture], ['name' => $culture]);
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

    // Function to get or create a distributeur
    private function getOrCreateDistributeur($distributeur)
    {
        $distributeur = strtolower(trim($distributeur));
        // remove speciale character for example 'algérie' to 'algerie'
        $DistributorUp = preg_replace('/[^A-Za-z0-9\-]/', '', $distributeur);

        // retrieve Distributor from database
        $dbDistributor = Distributor::where('name', $distributeur)->first();

        if ($dbDistributor) {
            $dbDistributor->name = preg_replace('/[^A-Za-z0-9\-]/', '', $dbDistributor->name);

            if ($DistributorUp == $dbDistributor->name) { // check if the name of the Distributor in the database is the same as the name of the Distributor in the excel
                return $dbDistributor;
            }

        }

        // create Distributor in database
        return Distributor::firstOrCreate(['name' => $distributeur], ['name' => $distributeur]);


    }

    /**
     * Function to get or create a firm
     *
     * @param string|null $firmName The name of the firm
     * @return Firm|null The firm object or null if $firmName is null
     */
    private function getOrCreateFirm(?string $firmName): ?Firm
    {
        // Check if the firm name is null
        if (is_null($firmName)) {
            // If it is null, return null
            return null;
        }

        // Trim and convert the firm name to lowercase
        $firmName = strtolower(trim($firmName));

        // Remove any non-alphanumeric or hyphen characters from the firm name
        $cleanFirmName = preg_replace('/[^A-Za-z0-9\-]/', '', $firmName);

        // Retrieve the firm from the database by its name
        $firm = Firm::where('name', $firmName)->first();

        if ($firm) {
            // If the firm already exists in the database, clean its name and compare it with the clean firm name
            $cleanFirmNameDb = preg_replace('/[^A-Za-z0-9\-]/', '', $firm->name);
            if ($cleanFirmName === $cleanFirmNameDb) {
                // If the clean names match, return the firm object
                return $firm;
            }
        }

        // If the firm does not exist in the database or its name has changed, create a new firm object
        return Firm::firstOrCreate(
            // The attributes of the new firm object
            ['name' => $firmName],
            // The initial values for the attributes of the new firm object
            ['name' => $firmName]
        );
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
    private function attachPrincipesActifs($intrant, $principes, $concentrations)
    {
        // concentrations is a string seperated by '+'
        $concentrations = explode('+', $concentrations);
        $principes = explode('+', $principes);

        foreach ($principes as $key => $principe) {
            $principeActif = $this->getOrCreatePrincipeActif($principe);

            // get the unit from concentration, concentrations is a string seperated by space when the first at left is
            // the value of concentration and the second at right is the unit
            $value = explode(' ', $concentrations[$key])[0];
            $value = floatval(str_replace(',', '.', $value));

            $unit = explode(' ', $concentrations[$key]);
            $unit = isset($unit[1]) ? $unit[1] : null;

            // Check if the unit exist in unit table then create one and return id
            $unit = $this->getOrCreateUnit($unit);

            $intrant->intrantsPrincipesActifs()->create(

                [
                    'principe_actif_id' => $principeActif->id,
                    'concentration' => $value ? $value : null,
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
