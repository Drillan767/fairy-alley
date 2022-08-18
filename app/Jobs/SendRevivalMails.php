<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\RevivalReport;
use App\Notifications\ReviveAirHeads;
use App\Notifications\ReviveCheapUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendRevivalMails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public string $type)
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
        if ($this->type === 'unsubscribed') {
            $users = User::query()
                ->whereNull('resubscription_status')
                ->whereNull('resubscribed_at')
                ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['first_contact', 'presubscribed', 'subscriber', 'substitute']);
                })
                ->get(['firstname', 'lastname', 'email']);

            foreach ($users as $user) {
                Notification::route('mail', $user->email)->notifyNow(new ReviveAirHeads());
            }

        } else {
            $users = collect();

            User::query()
                ->with('currentYearData:user_id,total,payments')
                ->where(function ($query) {
                    $query
                        ->whereNotNull('resubscribed_at')
                        ->orWhereNotNull('resubscription_status');
                })
                ->whereHas('currentYearData', function ($query) {
                    $query
                        ->whereYear('created_at', now()->year)
                        ->whereNotNull('total')
                    ;
                })
                ->get(['id', 'firstname', 'lastname', 'email'])
                ->map(function ($user) use (&$users) {
                    $total = 0;

                    foreach ($user->currentYearData->payments as $payment) {
                        $total += intval($payment['amount']);
                    }

                    if ($total < $user->currentYearData->total) {
                        $users->push($user);
                    }
                });

            foreach ($users as $user) {
                Log::info($user->email);
                Notification::route('mail', $user->email)->notifyNow(new ReviveCheapUsers());
            }
        }

        Notification::sendNow(User::role('administrator')->get(), new RevivalReport($users->toArray(), $this->type));
    }
}
