<?php

namespace App\Http\Controllers;

use App\Http\Requests\FirstContactRequest;
use App\Models\Lesson;
use App\Services\FirstContactHandler;
use Illuminate\Http\JsonResponse;
use Spatie\Valuestore\Valuestore;

class FirstContactController extends Controller
{
    public function create()
    {
        $years = $this->handleYears();

        return view('register', [
            'lessons' => Lesson::query()
                ->where('type', 'lesson')
                ->where('year', $years)
                ->get(['id', 'title']),
        ]);
    }

    public function store(FirstContactRequest $request, FirstContactHandler $firstContactHandler)
    {
        $firstContactHandler->store($request);
        return redirect()->back()->with('success', "Votre demande d'inscription a été soumise avec succès. Nous reviendrons vers vous dans les plus brefs délais.");
    }

    public function relatedLessons(string $gender): JsonResponse
    {
        $years = $this->handleYears();
        $lessons = Lesson::query()
            ->whereJsonContains('gender', $gender)
            ->where('year', $years)
            ->where('type', 'lesson')
            ->get(['id', 'title']);
        return response()->json($lessons);
    }

    private function handleYears(): string
    {
        $settings = Valuestore::make(storage_path('app/settings.json'));
        return $settings->get('which_year') === 'current'
            ? now()->subYear()->year . ' - ' . now()->year
            : now()->year . ' - ' . now()->addYear()->year;
    }
}
