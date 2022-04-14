<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use Illuminate\Support\Collection;
use App\Models\{Lesson, Movement, Queue, User};
use App\Services\{LessonDateDisplayHandler, SubscriptionHandler};
use Carbon\Carbon;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\Storage;
use Inertia\{Inertia, Response};

class SubscriptionController extends Controller
{
    public function __construct(public SubscriptionHandler $subscriptionHandler)
    {
    }

    public function index(LessonDateDisplayHandler $displayHandler): Response
    {
        $lessonDays = [];
        $nextLessons = [];
        /** @var User $user */
        $user = auth()->user();
        $headlines = collect(config('lesson.headlines'))->firstWhere('status_id', $user->subscription->status);


        if ($user->hasAnyRole('subscriber', 'guest', 'substitute')) {
            $user->load('suggestions.thumbnail', 'suggestions.page', 'lesson');

            $nextLessons = $this->defineNextLessons($user);

            $movements = $user
                ->movements()
                ->with('lesson')
                ->where('lesson_time', '>', now())
                ->get();

            $lessonDays = $displayHandler->calculate($user, $movements);
        }

        return Inertia::render('User/Landing', compact('headlines', 'lessonDays', 'nextLessons'));
    }

    public function create(Lesson $lesson): Response|RedirectResponse
    {
        if (auth()->user()->subscription !== null || auth()->user()->lesson_id !== null) {
            return redirect()->route('profile.index')->with('error', 'Votre inscription a déjà été enregistrée.');
        }

        $lessons = Lesson::all('title');
        $details = config('lesson.tos');

        return Inertia::render(
            'User/Subscription/Create',
            compact('lesson', 'lessons', 'details')
        );
    }

    public function edit(Lesson $lesson): Response
    {
        $user = auth()->user()->load('subscription', 'currentYearData.file');
        $lessons = Lesson::all('title');
        $details = config('lesson.tos');

        return Inertia::render(
            'User/Subscription/Edit',
            compact('lesson', 'lessons', 'details', 'user')
        );
    }

    public function store(SubscriptionRequest $request, SubscriptionHandler $subscriptionHandler): RedirectResponse
    {
        $subscriptionHandler->create($request);

        return redirect()->route('profile.index')->with('success', 'Votre inscription a bien été prise en compte');
    }

    public function update(SubscriptionRequest $request, SubscriptionHandler $subscriptionHandler): RedirectResponse
    {
        $subscriptionHandler->update($request);

        return redirect()->route('profile.index')->with('success', 'Votre inscription a bien été prise en compte');
    }

    public function lessonDetail(Request $request): JsonResponse
    {
        $request->validate(['date' => ['required', 'date']]);
        $lessons = Lesson::where('schedule', 'like', "%{$request->get('date')}%")
            ->limit(4)
            ->get();

        return response()->json($lessons);
    }

    private function handleHolidays(): array
    {
        $file = Storage::disk('s3')->get('system/holidays.json');
        $holidays = json_decode($file, true);

        return array_keys($holidays);
    }

    public function retrieveUserLessonDate(Request $request): JsonResponse
    {
        $schedule = collect($request->user()->lesson->schedule)
        ->filter(function ($date) {
            return Carbon::parse($date['date'])->isFuture();
        })
        ->mapWithKeys(function($date) {
            return [
                $date['date'] => Carbon::parse($date['date'])->format('d/m/Y'),
            ];
        });

        return response()->json($schedule);
    }

    public function movement(Request $request)
    {
        // Run job to check the waiting list...

        $action = $request->get('action');
        $user = $request->user();

        if ($action === 'leave') {
            $movement = new Movement();
            $movement->user_id = auth()->id();
            $movement->action = $action;
            $movement->lesson_id = $user->lesson_id;
            $cancelledDate = Carbon::parse($request->get('date'));
            $movement->lesson_time = $this->retrieveTimestamp($user->lesson_id, $cancelledDate);
            $movement->save();

            $message = "Votre présence au cours du {$cancelledDate->format('d/m/Y')} a bien été annulée.";

        } else {

            // TODO: check if user can subscribe to a lesson without needing to be on queue list.

            $lesson_id = $request->get('lesson');

            $joining = Queue::firstOrNew(['lesson_id' => $lesson_id]);
            $joining->datetime = $this->retrieveTimestamp($lesson_id, Carbon::parse($request->get('picked')));
            $joining->lesson_id = $request->get('lesson');

            $joining->joining = $joining->exists ? array_merge($joining->joining, [$user->id]) : [$user->id];

            $joining->save();

            if ($request->get('decision') === 'keep my place') {
                $leaving = Queue::firstOrNew(['lesson_id' => $user->lesson_id]);
                $leaving->datetime = $request->get('cancel');
                $leaving->leaving = $leaving->exists ? array_merge($leaving->leaving, [$user->id]) : [$user->id];
                $leaving->save();
            }

            $message = "Mise en liste d'attente effectuée avec succès.";
        }

        return redirect()->route('profile.index')->with('success', $message);
    }

    private function retrieveTimestamp($lesson_id, Carbon $date): string
    {
        $lesson = Lesson::select(['schedule'])->find($lesson_id);
        $schedule = collect($lesson->schedule)->filter(function($schedule) use ($date) {
            $scheduleDate = Carbon::parse($schedule['date']);
            return $scheduleDate->isSameDay($date);
        });

        return $schedule->first()['date'];
    }

    private function defineNextLessons(User $user): Collection
    {
        // replaced = d/m/Y
        $result = collect([]);
        $defaultLessons = collect($user->lesson->schedule)->filter(function ($schedule) {
            return $schedule['status'] !== 'cancelled' && Carbon::parse($schedule['date'])->isFuture();
        })
            ->values();

        for ($i = 0; $i < 16; $i++) {
            $result = $result->push([
                'title' => $user->lesson->title,
                'description' => $user->lesson->description,
                'time' => Carbon::parse($defaultLessons[$i]['date']),
            ]);
        }

        $user
            ->getFutureLessons()
            ->each(function ($lesson) use (&$result) {
                if ($lesson->action === 'join') {
                    $result->push([
                        'title' => $lesson->lesson->title,
                        'description' => $lesson->lesson->description,
                        'time' => Carbon::parse($lesson->lesson_time),
                    ]);
                }

                if ($lesson->action === 'leave') {
                    $result = $result->reject(function ($r) use($lesson) {
                        $lessonTime = Carbon::parse($lesson->lesson_time);
                        return $lessonTime->isSameDay($r['time']);
                    });
                }
            });

        return $result
            ->sortBy(fn ($obj) => $obj['time']->getTimeStamp())
            ->take(8)
            ->map(function ($r) {
                $r['time'] = $r['time']->format('d/m/Y');
                return $r;
            })
            ->values();
    }
}
