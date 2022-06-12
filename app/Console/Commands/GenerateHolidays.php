<?php

namespace App\Console\Commands;

use Carbon\CarbonPeriod;
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
    public function handle(): int
    {
        $currentYear = now()->year;
        $nextYear = now()->addYear()->year;

        $holidays = Http::get('https://calendrier.api.gouv.fr/jours-feries/metropole.json')
            ->collect()
            ->filter(fn ($value, $key) => str_starts_with($key, $currentYear) || str_starts_with($key, $nextYear))
        ;

        $vacations = Http::get('https://data.education.gouv.fr/api/records/1.0/search/', [
            'dataset' => 'fr-en-calendrier-scolaire',
            'refine.annee_scolaire' => "$currentYear-$nextYear",
            'refine.location' => 'Grenoble'
        ])->json();


        foreach ($vacations['records'] as $vacation) {
            $period = CarbonPeriod::create($vacation['fields']['start_date'], $vacation['fields']['end_date']);

            foreach ($period as $p) {
                $date = $p->addDay()->format('Y-m-d');
                if (!$holidays->has($date)) {
                    $holidays->put($date, $vacation['fields']['description']);
                }
            }
        }

         Storage::disk('s3')->delete('system/holidays.json');
         Storage::disk('s3')->put('system/holidays.json', $holidays->toJson());

        return self::SUCCESS;
    }
}
