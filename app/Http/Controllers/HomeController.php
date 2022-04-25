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

        $video = env('MEDIAS_URL') . '/videos/2%20-%20Exo%20de%20base%20Statique%203-2.AVI';
        return view($view, compact('services', 'video'));
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
