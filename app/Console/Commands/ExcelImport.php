<?php

namespace App\Console\Commands;

use App\Imports\FirstSheetImport;
use App\Imports\IntrantsImport;
use Illuminate\Console\Command;

class ExcelImport extends Command
{
    protected $signature = 'import:excel';

    protected $description = 'Laravel Excel importer';

    public function handle()
    {
        $this->output->title('Starting import');
        // (new FirstSheetImport)->withOutput($this->output)->import('intrants.xlsx');
        (new IntrantsImport)->withOutput($this->output)->import('intrants.xlsx');
        $this->output->success('Import successful');
    }
}
