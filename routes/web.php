<?php

use App\Http\Controllers\Admin\{AdminController,
    PageController,
    LessonController,
    ServiceController,
    UserController,
    ToolsController,
};
use App\Http\Controllers\FirstContactController;
use App\Http\Controllers\User\SubscriptionController;
use App\Notifications\TestNotification;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Notification;
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

Route::get('/test', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::middleware(['role:administrator'])->group(function() {
        Route::get('/administration', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/services/order', [ServiceController::class, 'order'])->name('services.order');

        Route::resources([
            'pages' => PageController::class,
            'cours' => LessonController::class,
            'utilisateurs' => UserController::class,
            'services' => ServiceController::class,
        ]);

        Route::resource('premiers-contacts', FirstContactController::class)
            ->except('edit', 'update')
            ->parameters(['premiers-contacts' => 'contact']);


        Route::get('/cours/{cours}/utilisateurs', [LessonController::class, 'users'])->name('cours.users');

        Route::get('/utilisateurs', [UserController::class, 'index'])->name('utilisateurs.index');
        Route::post('/preinscription', [UserController::class, 'subscribe'])->name('utilisateurs.subscribe');
        Route::get('/preinscriptions', [UserController::class, 'preSubscribed'])->name('utilisateurs.presubscribed');
        Route::get('/preinscription/{user}/editer', [UserController::class, 'subscribing'])->name('utilisateurs.subscribing');
        Route::put('/preinscription/{user}', [UserController::class, 'updateSubscription'])->name('utilisateurs.updateSubscription');
        // Route::get('/premiers-contacts', [FirstContactController::class, 'index'])->name('firstcontact.index');
        // Route::get('/premiers-contacts/{contact}', [FirstContactController::class, 'show'])->name('firstcontact.show');

        Route::get('/importer-utilisateurs', [ToolsController::class, 'importForm'])->name('import.form');
        Route::post('/import-users', [ToolsController::class, 'importUsers'])->name('import.store');
    });

    Route::middleware(['role:subscriber'])->group(function() {
        Route::get('/profil', [SubscriptionController::class, 'index'])->name('profile.index');
        Route::get('/inscription-cours/{lesson}', [SubscriptionController::class, 'create'])->name('subscription.create');
        Route::get('/inscription/cours/{lesson}/editer', [SubscriptionController::class, 'edit'])->name('subscription.edit');
        Route::post('/subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
        Route::put('/subscription', [SubscriptionController::class, 'update'])->name('subscription.update');

    });
});

Route::get('/home', [UserController::class, 'redirectHome'])->name('redirect.home');
Route::get('/inscription', [FirstContactController::class, 'create'])->name('fc.create');
Route::post('/inscription', [FirstContactController::class, 'store'])->name('fc.store');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');

require_once __DIR__ . '/fortify.php';
