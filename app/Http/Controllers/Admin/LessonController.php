<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::orderBy('title')->get();

        return Inertia::render('Admin/Lessons/Index', compact('lessons'));
    }

    public function create()
    {
        return Inertia::render(
            'Admin/Lessons/Create',
            [
                'holidays' => $this->handleHolidays(),
            ]
        );
    }

    public function store(LessonRequest $request)
    {
        $this->handleLesson(new Lesson(), $request);

        return redirect()->route('cours.index')->with('success', 'Cours enregistré avec succès.');
    }

    public function show()
    {
        abort(404);
    }

    public function edit(Lesson $cour): Response
    {
        return Inertia::render('Admin/Lessons/Edit', [
            'lesson' => $cour,
            'holidays' => $this->handleHolidays(),
        ]);
    }

    public function update(LessonRequest $request, Lesson $cour): RedirectResponse
    {
        $this->handleLesson($cour, $request, true);

        return redirect()->route('cours.index')->with('success', 'Cours mis à jour avec succès.');
    }

    public function destroy(Lesson $cour)
    {
        if (request()->user()->hasRole('administrator')) {
            $cour->delete();
        }
    }

    public function users(Lesson $cours): Response
    {
        return Inertia::render('Admin/Lessons/Users', ['lesson' => $cours->load('users')]);
    }

    private function handleLesson(Lesson $lesson, LessonRequest $request, bool $update = false)
    {
        $function = $update ? 'update' : 'create';
        // dd($request);
        $fields = array_merge(
            $request->validated(),
            [ 'year' => now()->year . ' - ' . now()->addYear()->year]
        );

        $lesson->$function($fields);
    }

    private function handleHolidays(): array
    {
        $file = Storage::disk('s3')->get('system/holidays.json');
        $holidays = json_decode($file, true);

        return array_keys($holidays);
    }
}
