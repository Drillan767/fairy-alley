<?php

namespace App\Jobs;

use App\Models\Lesson;
use App\Models\User;
use App\Notifications\UserRenewalFeedback;
use App\Notifications\UserRenewed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendRenewalEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $users, public array $decisions)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lessons = Lesson::select('id', 'title')->find($this->decisions);

        foreach ($this->users as $user) {
            $relatedLesson = $lessons->first( fn ($l) => $l->id === $this->decisions[$user->id]);
            Notification::route('mail', $user->email)->notifyNow(new UserRenewed($relatedLesson->title));
        }

        Notification::sendNow(User::role('administrator')->get(), new UserRenewalFeedback($this->users->toArray()));

    }
}
