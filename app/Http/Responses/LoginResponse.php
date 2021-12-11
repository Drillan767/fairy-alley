<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{

    public function toResponse($request): RedirectResponse
    {
        $role = auth()->user()->getRoleNames()->first();
        switch ($role) {
            case 'administrator':
                return redirect()->intended('/administration');
            case 'first_contact':
                auth()->logout();
                return redirect()->route('redirect.home');
            default:
                return redirect()->intended('/profil');
        }
    }
}
