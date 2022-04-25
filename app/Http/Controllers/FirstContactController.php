<?php

namespace App\Http\Controllers;

use App\Http\Requests\FirstContactRequest;
use App\Models\Lesson;
use App\Services\FirstContactHandler;
use Illuminate\Http\JsonResponse;

class FirstContactController extends Controller
{
    public function create()
    {
        return view('register', [
            'lessons' => Lesson::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function store(FirstContactRequest $request, FirstContactHandler $firstContactHandler)
    {
        $firstContactHandler->store($request);
        return redirect()->back()->with('success', "Votre demande d'inscription a été soumise avec succès. Nous reviendrons vers vous dans les plus brefs délais.");
    }

    public function relatedLessons(string $gender): JsonResponse
    {
        $lessons = Lesson::query()
            ->whereJsonContains('gender', $gender)
            ->orderBy('title')
            ->get(['id', 'title']);
        return response()->json($lessons);
    }
}
