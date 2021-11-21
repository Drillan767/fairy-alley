<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Service;
use App\Notifications\ContactForm;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    public function landing()
    {
        $view = auth()->check() ? 'home.landing' : 'home.building';
        $services = Service::with('file', 'page')
            ->orderBy('order')
            ->get();
        return view($view, compact('services'));
    }

    public function contact(ContactRequest $request)
    {
        Notification::route('mail', env('MAIL_SEND_TO'))->notify(new ContactForm($request->all()));

        return response()->json('Merci pour votre message, nous reviendrons vers vous très rapidement !');
    }

    public function displayPage($slug)
    {

    }
}
