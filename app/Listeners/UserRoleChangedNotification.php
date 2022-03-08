<?php

namespace App\Listeners;

use App\Events\UserRoleChanged;

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
