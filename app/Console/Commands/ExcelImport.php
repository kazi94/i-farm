<?php

namespace App\Console\Commands;

use App\Imports\FirstSheetImport;
use App\Imports\Intrants2017Import;
use App\Imports\Intrants2022Import;
use Illuminate\Console\Command;

class ExcelImport extends Command
{
    protected $signature = 'import:excel';

    protected $description = 'Laravel Excel importer';

    public function handle()
    {

        $this->output->title('Starting import');
        //(new FirstSheetImport)->withOutput($this->output)->import('intrants-2022.xlsx');
        (new Intrants2022Import)->withOutput($this->output)->import('intrants-2022.xlsx');

        //(new FirstSheetImport)->withOutput($this->output)->import('intrants-2017.xlsx');
        (new Intrants2017Import)->withOutput($this->output)->import('intrants-2017.xlsx');

        $this->output->success('Import successful');
    }
}
