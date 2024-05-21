<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class truncateTables extends Command
{
    protected $signature = 'db:truncate-all';
    protected $description = 'Truncate all tables in the database';

    public function handle()
    {
        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        foreach ($tables as $table) {
            if ($table !== 'migrations') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                DB::table($table)->truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }
        }

        $this->info('All tables have been truncated.');
    }
}
