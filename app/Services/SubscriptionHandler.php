<?php

namespace App\Services;

use App\Http\Requests\SubscriptionRequest;
use App\Models\{Media, Subscription, YearData};
use Illuminate\Support\Facades\Storage;

class SubscriptionHandler
{
    public function create(SubscriptionRequest $request)
    {
        $user_id = $request->get('user_id');
        $yearData = new YearData();
        $yearData->user_id = $user_id;
        $yearData->possibility_1 = $request->get('schedule_choice1');
        $yearData->possibility_2 = $request->get('schedule_choice2');
        $yearData->health_data = $request->get('health_data');

        $yearData->save();

        if ($request->hasFile('medical_certificate')) {
            $file = $request->file('medical_certificate');

            $media = Media::create([
                'title' => $file->getClientOriginalName(),
                'url' => Storage::disk('s3')->putFileAs("user/{$user_id}", $file, $file->getClientOriginalName()),
                'illustrable_id' => $user_id,
                'illustrable_type' => 'App\Models\User'
            ]);

            $yearData->media_id = $media->id;
            $yearData->save();
        }

        Subscription::create([
            'status' => Subscription::PENDING,
            'user_id' => $user_id,
            'lesson_id' => $request->get('lesson_id'),
            'possibility_1' => $request->get('schedule_choice1'),
            'possibility_2' => $request->get('schedule_choice2'),
            'invites' => $request->get('invites') ?? [],
        ]);
    }

    public function update()
    {

    }
}
