<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Service;
use App\Notifications\ContactForm;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function landing()
    {
        $view = auth()->check() ? 'home.landing' : 'home.building';
        $services = Service::with('thumbnail', 'page')
            ->where('homepage', true)
            ->orderBy('order')
            ->get();

        $echauffement = env('MEDIAS_URL') . 'videos/1%20-%20Echauffement%201-Etirements%20Allong%C3%A9s%20Au%20Sol.m4v';
        $visite = env('MEDIAS_URL') . 'videos/descente-sur-maison.mp4';
        return view($view, compact('services', 'echauffement', 'visite'));
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
