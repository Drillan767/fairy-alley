<?php

namespace App\Listeners;

use App\Events\FirstContactCreated;
use App\Models\User;
use App\Notifications\NewRegistration;
use App\Notifications\RegistrationAcknowledged;
use Illuminate\Support\Facades\Notification;
use function Psy\debug;

class SendFirstContactEmails
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
     * @param FirstContactCreated $event
     * @return void
     */
    public function handle(FirstContactCreated $event)
    {
        Notification::sendNow(User::role('administrator')->get(), new NewRegistration($event->user));
        $event->user->notify(new RegistrationAcknowledged());
    }
}
