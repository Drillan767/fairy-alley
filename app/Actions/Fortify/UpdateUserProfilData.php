<?php

namespace App\Actions\Fortify;

use App\Http\Requests\UserCoordinatesRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateUserProfilData
{

    public function updateCoordinates(UserCoordinatesRequest $request, User $user)
    {
        foreach (['address1', 'address2', 'zipcode', 'city', 'phone', 'pro'] as $field) {
            $user->$field = $request->get($field);
        }

        $user->save();

        return redirect()->back();
    }

    public function updateOtherData(Request $request, User $user)
    {
        $user->other_data = $request->get('other_data');
        $user->save();

        return redirect()->back();
    }
}
