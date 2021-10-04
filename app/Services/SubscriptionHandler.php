<?php

namespace App\Services;

use App\Casts\Status;
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
        $yearData->health_data = $request->get('health_data');

        $yearData->save();

        if ($request->hasFile('medical_certificate')) {
            $file = $request->file('medical_certificate');
            $yearData->save();

            $media = new Media([
                'title' => $file->getClientOriginalName(),
                'url' => Storage::disk('s3')->putFileAs("user/{$user_id}", $file, $file->getClientOriginalName()),
            ]);

            $yearData->file()->save($media);
        }

        Subscription::create([
            'status' => Subscription::PENDING,
            'user_id' => $user_id,
            'lesson_id' => $request->get('lesson_id'),
            'selected_time' => $request->get('schedule_choice1'),
            'fallback_time' => $request->get('schedule_choice2'),
            'invites' => $request->get('invites') ?? [],
        ]);
    }

    public function update()
    {

    }
}
