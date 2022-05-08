<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use Illuminate\Support\Collection;
use App\Models\{Lesson, Movement, User};
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

    public function index(LessonDateDisplayHandler $displayHandler): Response|RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $lessonDays = [];
        $nextLessons = [];

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
            $lessonDays[] = [
                'dates' => '2022-05-03',
                'highlight' => [
                    'color' => 'blue',
                    'fillMode' => 'light'
                ],
                'order' => 1,
            ];
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

    public function movement(Request $request): RedirectResponse
    {
        $lessonId = intval($request->get('lesson'));
        $action = $request->get('action');

        /** @var User $user */
        $user = $request->user();
        $movements = $user->movements();

        $actionDate = Carbon::parse($request->get('date'));
        $lesson = $action === 'leave'
            ? $user->lesson
            : Lesson::query()
            ->select('id', 'title', 'schedule')
            ->find($request->get('lesson'));

        $timestamp = $this->retrieveTimestamp($lesson->schedule, $actionDate);

        // Check if movement doesn't already exist.
        $movementExists = $movements->where([
                ['user_id', $user->id],
                ['action', $action],
                ['lesson_id', $lessonId ?? $lesson->id],
                ['lesson_time', $timestamp]
            ])
            ->count();

        if ($movementExists) {
            $userAction = $action === 'join' ? 'présence' : 'absence';
            return redirect()->back()->with('error', "Vous avez déjà indiqué votre $userAction à ce cours.");
        }

        // Check if user is already subscribed to this lesson and if they're supposed to be there.
        if ($lessonId === $user->lesson_id) {
            $lastRelatedMovement = Movement::query()
                ->where([
                    ['user_id', $user->id],
                    ['action', 'leave'],
                    ['lesson_id', $lessonId],
                    ['lesson_time', '2022-05-16 19:15:00.0'],
                ])
                ->latest()
                ->first();

            if (!$lastRelatedMovement) {
                return redirect()->back()->with('error', 'Vous tentez de rejoindre un cours auquel vous êtes déjà inscrit(e).');
            }
        }

        Movement::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'lesson_id' => $lesson->id,
            'lesson_time' => $timestamp,
        ]);

        $message = $action === 'join'
            ? "Vous avez été inscrit au cours de \"$lesson->title\" avec succès."
            : "Votre présence au cours du {$actionDate->format('d/m/Y')} a bien été annulée.";

        return redirect()->route('profile.index')->with('success', $message);
    }

    private function retrieveTimestamp(array $schedule, Carbon $date): string
    {
        $dates = collect($schedule)->filter(function($schedule) use ($date) {
            $scheduleDate = Carbon::parse($schedule['date']);
            return $scheduleDate->isSameDay($date);
        });

        return $dates->first()['date'];
    }

    private function defineNextLessons(User $user): Collection
    {
        // replaced = d/m/Y
        $result = collect([]);
        $defaultLessons = collect($user->lesson->schedule)->filter(function ($schedule) {
            return Carbon::parse($schedule['date'])->isFuture();
        })
            ->values();

        foreach($defaultLessons as $lesson) {
            $result = $result->push([
                'title' => $user->lesson->title,
                'description' => $user->lesson->description,
                'time' => Carbon::parse($lesson['date']),
            ]);
        }

        $user
            ->getFutureLessons()
            ->each(function ($movement) use (&$result) {

                if ($movement->action === 'leave') {
                    $result = $result->reject(function ($r) use($movement) {
                        $lessonTime = Carbon::parse($movement->lesson_time);
                        return $lessonTime->isSameDay($r['time']);
                    });
                }

                $result->push([
                    'title' => $movement->lesson->title,
                    'description' => $movement->lesson->description,
                    'action' => $movement->action,
                    'time' => Carbon::parse($movement->lesson_time),
                ]);
            });

        return $result
            ->sortBy(fn ($obj) => $obj['time']->getTimeStamp())
            ->take(8)
            ->map(function ($r) {
                $format = 'd/m/Y';

                if (isset($r['action']) && $r['action'] === 'join') {
                    $format = 'd/m/Y à H:i';
                }

                $r['time'] = $r['time']->format($format);
                return $r;
            })
            ->values();
    }
}
