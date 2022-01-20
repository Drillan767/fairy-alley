<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all users except the administrators';

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
        $admins = User::role('administrator')->pluck('id');
        User::whereNotIn('id', $admins)->delete();
        return 0;
    }
}
