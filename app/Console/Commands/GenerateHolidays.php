<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GenerateHolidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:holidays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a command that will either generate or replace a JSON file containing all holidays dates in France.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::get('https://calendrier.api.gouv.fr/jours-feries/metropole.json')->body();
        Storage::disk('s3')->delete('system/holidays.json');
        Storage::disk('s3')->put('system/holidays.json', $response);
        return 0;
    }
}
