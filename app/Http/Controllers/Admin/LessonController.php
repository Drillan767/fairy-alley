<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use App\Models\User;
use App\Services\LessonTempUsersHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Valuestore\Valuestore;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::all();

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

    public function users(int $lid, LessonTempUsersHandler $tempUsersHandler)
    {
        $lesson = Lesson::select('id', 'title')->find($lid);
        $users = User::select('id', 'firstname', 'lastname')
            ->with('currentYearData:user_id,total,payments')
            ->where('lesson_id', $lid)
            ->whereNotNull('resubscribed_at')
            ->get();

        $tempUsers = $tempUsersHandler->getUsers($lid);

        return Inertia::render('Admin/Lessons/Users', compact('lesson', 'users', 'tempUsers'));
    }

    private function handleLesson(Lesson $lesson, LessonRequest $request, bool $update = false)
    {
        $function = $update ? 'update' : 'create';
        if ($function === 'create') {
            $fields = array_merge(
                $request->validated(),
                ['year' => now()->year . ' - ' . now()->addYear()->year]
            );
        }
        else {
            $fields = $request->validated();
        }

        $lesson->$function($fields);
    }

    private function handleHolidays(): array
    {
        $settings = Valuestore::make(storage_path('app/settings.json'));
        $customVacations = $settings->get('holidays');
        $file = Storage::disk('s3')->get('system/holidays.json');
        $holidays = json_decode($file, true);

        foreach ($customVacations as $date => $reason) {
            if (!array_key_exists($date, $holidays)) {
                $holidays[$date] = $reason;
            }
        }

        return array_keys($holidays);
    }
}
