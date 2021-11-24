<?php

namespace App\Http\Controllers;

use App\Http\Requests\FirstContactRequest;
use App\Models\FirstContact;
use Illuminate\Http\Request;

class FirstContactController extends Controller
{
    public function index()
    {

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
        foreach ($request->validated() as $field => $value) {
            $firstContact->$field = $value;
        }

        $firstContact->save();

        return redirect()->back()->with('success', "Votre demande d'inscription a été soumise avec succès. Nous reviendrons vers vous dans les plus brefs délais.");
    }

    public function delete()
    {

    }

    public function registerFromForm()
    {

    }
}
