<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        $role = auth()->user()->getRoleNames()->first();
        switch ($role) {
            case 'administrator':
                return redirect()->route('admin.index');
            case 'guest':
            case 'presubscribed':
            case 'subscriber':
            case 'substitute':
                $password = Hash::check('password', auth()->user()->password) ? ['password' => true] : [];
                return redirect()->route('profile.index', $password);

            default:
                auth()->logout();

                return redirect('/connexion')->withErrors(['email' => 'Votre rôle ne vous permet pas de vous connecter.']);
        }
    }

    /*
             $role = Auth::user()->role;

        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        if ($role === 'Manager') {
            Auth::guard('web')->logout();
        }

        return match ($role) {
            'admin' => redirect()->intended(config('fortify.home')),
            'host' => redirect()->intended('/host/dashboard'),
            'Manager' => redirect('/login')->withErrors(['email' => 'Votre rôle ne vous permet pas de vous connecter.']),
            default => redirect('/'),
        };
     */
}
