<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementRequest;
use App\Models\Movement;
use Carbon\Carbon;
use Illuminate\Http\Client\Request;

class MovementController extends Controller
{
    // TODO: Move main functions from SubscriptionController to here.

    public function flow(MovementRequest $request)
    {
        $fields = ['user_id', 'lesson_id', 'action'];
        $movement = new Movement();

        if ($request->has('by_admin')) {
            $fields[] = 'by_admin';
        }

        foreach($fields as $field) {
            $movement->$field = $request->get($field);
        }

        $movement->lesson_time = Carbon::parse($request->get('lesson_time'));

        $movement->save();
    }

    public function lock(MovementRequest $request)
    {

    }
}
