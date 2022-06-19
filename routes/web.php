<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ToolsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FirstContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\User\RenewalController;
use App\Http\Controllers\User\SubscriptionController;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Spatie\Valuestore\Valuestore;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$settings = Valuestore::make(storage_path('app/settings.json'));
$start = Carbon::parse($settings->get('subscription_start'));
$end = Carbon::parse($settings->get('subscription_end'));

Route::get('/', [HomeController::class, 'landing'])->name('landing');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact');

Route::middleware(['auth:sanctum', 'verified'])->group(function () use ($start, $end) {

    Route::post('/flow', [MovementController::class, 'flow'])->name('movement.flow');

    Route::middleware(['role:administrator'])->group(function () use ($start, $end) {
        Route::controller(AdminController::class)->group(function() {
            Route::get('/administration', 'index')->name('admin.index');
            Route::get('/admin/lesson/list', 'lessonList')->name('admin.lesson.list');
            Route::post('/admin/lesson/detail', 'details')->name('lesson.details');
            Route::post('/users', 'getUsers')->name('users');
            Route::post('/admin/subscribe', 'subscribe')->name('admin.subscribe');
            Route::post('/admin/unsubscribe', 'unsubscribe')->name('admin.unsubscribe');
            Route::post('/admin/lock', 'lock')->name('admin.lock');
        });

        Route::controller(SettingsController::class)->group(function () {
            Route::get('/parametres', 'edit')->name('settings.edit');
            Route::post('/settings-renewal', 'renewal')->name('settings.renewal');
            Route::post('/settings-holidays', 'holidays')->name('settings.holidays');
        });

        Route::controller(MovementController::class)->group(function() {
            Route::post('/movement/lock', 'lock')->name('movement.lock');
        });

        Route::post('/services/order', [ServiceController::class, 'order'])->name('services.order');

        Route::resource('premiers-contacts', FirstContactController::class)
            ->except('edit', 'update')
            ->parameters(['premiers-contacts' => 'contact']);

        Route::controller(UserController::class)->group(function () use ($start, $end) {
            Route::get('/utilisateurs', 'index')->name('utilisateurs.index');
            Route::post('/change-lesson', 'changeLesson')->name('utilisateurs.change-lesson');
            Route::post('/admin/reset-password', 'resetPassword')->name('utilisateurs.reset-password');
            Route::post('/change-role', 'changeRole')->name('utilisateurs.change-role');
            Route::post('/preinscription', 'subscribe')->name('utilisateurs.subscribe');
            Route::post('archive/{user}', 'archiveUser')->name('utilisateurs.archive');
            Route::get('/preinscriptions', 'preSubscribed')->name('utilisateurs.presubscribed');
            Route::post('/preinscription/update-decision', 'updateDecision')->name('utilisateurs.updateDecision');
            Route::get('/preinscription/{user}/editer', 'subscribing')->name('utilisateurs.subscribing');
            Route::put('/preinscription/{user}', 'updateSubscription')->name('utilisateurs.updateSubscription');

            if (($start->isToday() || $start->isPast()) && $end->isFuture()) {
                Route::get('/réinscriptions', 'renewalIndex')->name('utilisateur.renewal.index');
                Route::get('/réinscription/{user}', 'renewal')->name('utilisateur.renewal.show');
                Route::post('/store-renewal', 'storeRenewal')->name('utilisateur.renewal.store');
            }

        });

        Route::controller(FileController::class)->prefix('/media')->group(function () {
            Route::get('/{media}', 'index')
                ->where('media', '(musiques|photos|videos)')
                ->name('files.index');
            Route::post('/media', 'upload')->name('media.upload');
        });

        Route::get('/cours/{cours}/utilisateurs', [LessonController::class, 'users'])->name('cours.users');

        Route::controller(ToolsController::class)->group(function () {
            Route::get('/importer-utilisateurs', 'importForm')->name('import.form');
            Route::post('/import-users', 'importUsers')->name('import.store');
            Route::get('/export-holidays', 'exportHolidays')->name('export.holidays');
        });

        Route::resources([
            'pages' => PageController::class,
            'cours' => LessonController::class,
            'utilisateurs' => UserController::class,
        ]);

        Route::get('/services/souscriptions', [ServiceController::class, 'subscriptions'])->name('services.subscriptions.index');
        Route::post('services/subscriptions', [ServiceController::class, 'updateSubscription'])->name('services.subscriptions.update');

        Route::apiResources([
            'services' => ServiceController::class,
        ]);
    });

    Route::controller(SubscriptionController::class)->group(function() {
        Route::get('/profil', 'index')->name('profile.index');
        Route::post('lessonDetail', 'lessonDetail')->name('lesson-detail');
    });

    Route::middleware(['role:subscriber'])->group(function () use ($start, $end) {
        Route::controller(SubscriptionController::class)->group(function () {
            Route::get('/inscription-cours/{lesson}', 'create')->name('subscription.create');
            Route::get('/inscription/cours/{lesson}/editer', 'edit')->name('subscription.edit');
            Route::post('/subscription', 'store')->name('subscription.store');
            Route::put('/subscription', 'update')->name('subscription.update');
            Route::post('/lesson-date', 'retrieveUserLessonDate')->name('user-lesson-date');
            Route::post('/movement', 'movement')->name('lesson-movement');
            Route::post('/user/change-password', 'swalUpdatePassword')->name('change.password');
        });

        Route::post('ask-service', [ServiceController::class, 'askService'])->name('service.ask');

        if (($start->isToday() || $start->isPast()) && $end->isFuture()) {
            Route::controller(RenewalController::class)->group(function() {
                Route::get('/réinscription', 'index')->name('renewal.index');
                Route::post('/renewal', 'update')->name('renewal.update');
            });
        }
    });
});

Route::get('/home', [UserController::class, 'redirectHome'])->name('redirect.home');

Route::post('/inscription', [FirstContactController::class, 'store'])->name('fc.store');
Route::get('/inscription', [FirstContactController::class, 'create'])->name('fc.create');
Route::get('related-lessons/{gender}', [FirstContactController::class, 'relatedLessons']);

Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');

require_once __DIR__ . '/fortify.php';
