<?php

namespace App\Listeners;

use App\Events\UserRoleChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserRoleChangedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserRoleChanged  $event
     * @return void
     */
    public function handle(UserRoleChanged $event)
    {
        //
    }
}
