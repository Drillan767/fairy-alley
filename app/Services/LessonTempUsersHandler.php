<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\Valuestore\Valuestore;

class LessonTempUsersHandler
{
    public function getUsers(int $lid): Collection
    {
        $settings = Valuestore::make(storage_path('app/settings.json'));
        $start = Carbon::parse($settings->get('subscription_start'));
        $end = Carbon::parse($settings->get('subscription_end'));

        if (($start->isToday() || $start->isPast()) && $end->isFuture()) {
            $file = Valuestore::make(storage_path('app/renewal.json'));
            $uids = collect($file->all())
                ->filter(fn ($u) => $u['admin_decision'] === strval($lid))
                ->keys()
                ->map(fn ($u) => str_replace('user_', '', $u));

            return User::select(['id', 'firstname', 'lastname'])
                ->find($uids)
                ->map(function ($u) {
                    $u->temp = true;
                    return $u;
                });
        } else {
            return collect();
        }
    }
}
