<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SendBackup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class DumpDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dump {--notify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dumps the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dumpFile = 'dump-' . now()->format('d-m-Y') . '.sql';

        exec(
            'mysqldump -u' . env('DB_USERNAME') .
            ' --add-drop-database --add-drop-table' .
            ' -p' . env('DB_PASSWORD')
            . ' -h ' . env('DB_HOST') . ' ' .
            env('DB_DATABASE') . ' > ' .
            storage_path('app/' . $dumpFile)
        );

        if ($this->option('notify')) {
            Notification::sendNow(User::firstWhere('lastname', 'Muraz'), new SendBackup($dumpFile));
            Storage::disk('local')->delete($dumpFile);
        }

        return self::SUCCESS;
    }
}
