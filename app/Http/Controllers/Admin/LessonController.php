<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Inertia\Response;

class LessonController extends Controller
{

    public function index()
    {
        return Inertia::render('Admin/Lessons/Index', ['lessons' => Lesson::all()]);
    }

    public function create()
    {
        return Inertia::render('Admin/Lessons/Create');
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
        return Inertia::render('Admin/Lessons/Edit', ['lesson' => $cour]);
    }

    public function update(LessonRequest $request, Lesson $cour): RedirectResponse
    {
        $this->handleLesson($cour, $request, true);
        return redirect()->route('cours.index')->with('success', 'Cours mit à jour avec succès.');
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
        $fields = array_merge(
            $request->validated(),
            ['year' => now()->year . ' - ' . now()->addYear()->year]
        );

        $lesson->$function($fields);

        /* TODO: change code by this for PHP v8.1
         ...$request->validated(),
        'year' => now()->year . '-' . now()->addYear()->year,
        */
    }
}
