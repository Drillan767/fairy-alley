<?php

use App\Http\Controllers\Admin\{AdminController, PageController, LessonController, UserController};
use App\Http\Controllers\GroupController;
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

Route::view('/', 'landing');

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

        Route::resources([
            'pages' => PageController::class,
            'cours' => LessonController::class,
            'cours.groupes' => GroupController::class,
            'utilisateurs' => UserController::class,
        ]);

        Route::get('/utilisateurs', [UserController::class, 'subscribed'])->name('utilisateurs.subscribed');
        Route::get('/preinscriptions', [UserController::class, 'preSubscribed'])->name('utilisateurs.presubscribed');
        Route::get('/preinscription/{user}/editer', [UserController::class, 'subscribing'])->name('utilisateurs.subscribing');
    });

    Route::middleware(['role:subscriber'])->group(function() {
        Route::get('/profil', [SubscriptionController::class, 'index'])->name('profile.index');
        Route::get('/inscription-cours/{lesson}', [SubscriptionController::class, 'create'])->name('subscription.create');
        Route::get('/inscription/cours/{lesson}/editer', [SubscriptionController::class, 'edit'])->name('subscription.edit');
        Route::post('/subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
    });
});

Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');
