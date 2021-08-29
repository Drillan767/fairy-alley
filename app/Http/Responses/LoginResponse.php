<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{

    public function toResponse($request): RedirectResponse
    {
        $home = auth()->user()->hasRole('administrator') ? '/administration' : '/profil';
        return redirect()->intended($home);
    }

}
