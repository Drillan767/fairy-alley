<?php

namespace App\Http\Controllers;

use App\Http\Requests\FirstContactRequest;
use App\Models\FirstContact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FirstContactController extends Controller
{
    public function index()
    {
        $contacts = FirstContact::all();
        return Inertia::render('Admin/Users/FirstContacts/Index', compact('contacts'));
    }

    public function show(FirstContact $contact)
    {
        return Inertia::render('Admin/Users/FirstContacts/Show', compact('contact'));
    }

    public function create()
    {
        if (env('ENABLE_SUBSCRIPTION')) {
            return view('register');
        } else {
            return redirect()->route('redirect.home');
        }
    }

    public function store(FirstContactRequest $request)
    {
        $firstContact = new FirstContact();
        foreach ($request->all() as $field => $value) {
            if ($field !== '_token') {
                $firstContact->$field = $value;
            }
        }

        $firstContact->save();

        return redirect()->back()->with('success', "Votre demande d'inscription a été soumise avec succès. Nous reviendrons vers vous dans les plus brefs délais.");
    }

    public function delete(FirstContact $contact)
    {
        $contact->delete();
    }

    public function createUser()
    {

    }
}
