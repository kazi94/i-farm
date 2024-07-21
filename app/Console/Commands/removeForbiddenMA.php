<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class removeForbiddenMA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-forbidden-ma';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->comment('Remove forbidden MA');

        // read from txt file from database/data folder list of names
        $data = file('database/data/forbiddenMA.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $matiereActifDeletedCounter = 0;

        // for each name delete from DB and delete related intrants
        foreach ($data as $name) {
            $name = strtolower(trim($name));
            $matiereActif = \App\Models\PrincipeActif::where('name_fr', 'LIKE', '%' . $name . '%')->first();
            if ($matiereActif) {
                $matiereActifDeletedCounter++;
                $matiereActif->delete();
            } else {
                // display in terminal name not found
                // echo $name . "\n";
            }
        }
        $this->info($matiereActifDeletedCounter ? $matiereActifDeletedCounter . ' forbidden MA deleted' : 'No forbidden MA found');


        $this->comment('Remove intrants with no more principes actifs');
        $intrantsDeletedCounter = 0;
        // Check if intrant has no more related intrantsPrincipesActifs then delete the intrant
        $intrants = \App\Models\Intrant::all();
        foreach ($intrants as $intrant) {
            if ($intrant->intrantsPrincipesActifs->count() == 0) {
                // echo $intrant->name_fr . "\n";
                $intrantsDeletedCounter++;
                $intrant->delete();
            }
        }

        $this->info($intrantsDeletedCounter ? $intrantsDeletedCounter . ' intrants deleted' : 'No intrants found');
    }
}
