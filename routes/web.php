<?php

use App\Http\Controllers\Admin\{AdminController,
    FileController,
    PageController,
    LessonController,
    ServiceController,
    UserController,
    ToolsController};
use App\Http\Controllers\FirstContactController;
use App\Http\Controllers\User\SubscriptionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;

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

Route::get('/', [HomeController::class,'landing'])->name('landing');

Route::post('/contact', [HomeController::class, 'contact'])->name('contact');

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::middleware(['role:administrator'])->group(function() {
        Route::get('/administration', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/services/order', [ServiceController::class, 'order'])->name('services.order');

        Route::resource('premiers-contacts', FirstContactController::class)
            ->except('edit', 'update')
            ->parameters(['premiers-contacts' => 'contact']);

        Route::controller(UserController::class)->group(function () {
            Route::get('/utilisateurs', 'index')->name('utilisateurs.index');
            Route::post('/change-lesson', 'changeLesson')->name('utilisateurs.change-lesson');

            Route::post('/change-role', 'changeRole')->name('utilisateurs.change-role');
            Route::post('/preinscription', 'subscribe')->name('utilisateurs.subscribe');
            Route::post('archive/{user}', 'archiveUser')->name('utilisateurs.archive');
            Route::get('/preinscriptions', 'preSubscribed')->name('utilisateurs.presubscribed');
            Route::get('/preinscription/{user}/editer', 'subscribing')->name('utilisateurs.subscribing');
            Route::put('/preinscription/{user}', 'updateSubscription')->name('utilisateurs.updateSubscription');
        });

        Route::controller(FileController::class)->prefix('/media')->group(function() {
            Route::get('/{media}', 'index')
                ->where('media', '(musiques|photos|videos)')
                ->name('files.index');
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

        Route::apiResources([
            'services' => ServiceController::class,
        ]);

    });

    Route::get('/profil', [SubscriptionController::class, 'index'])->name('profile.index');

    Route::middleware(['role:subscriber'])->group(function() {
        Route::controller(SubscriptionController::class)->group(function () {
            Route::get('/inscription-cours/{lesson}', 'create')->name('subscription.create');
            Route::get('/inscription/cours/{lesson}/editer', 'edit')->name('subscription.edit');
            Route::post('/subscription', 'store')->name('subscription.store');
            Route::put('/subscription', 'update')->name('subscription.update');
        });
    });
});

Route::get('/home', [UserController::class, 'redirectHome'])->name('redirect.home');
Route::get('/inscription', [FirstContactController::class, 'create'])->name('fc.create');
Route::post('/inscription', [FirstContactController::class, 'store'])->name('fc.store');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');

require_once __DIR__ . '/fortify.php';
