<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DBFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hotfixes stuff in db';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::table('year_data')
            ->where(['payments' => null])
            ->update(['payments' => '[]'])
        ;
        return self::SUCCESS;
    }
}
