<?php

use App\Http\Controllers\Admin\{AdminController, PageController, LessonController};
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

        Route::post('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::resource('pages', PageController::class)->except(['show', 'update']);

        Route::post('/cours/{lesson}', [LessonController::class, 'update'])->name('cours.update');
        Route::resource('cours', LessonController::class)->except(['update']);

        Route::post('/groupes/{group}', [GroupController::class, 'update'])->name('groupes.update');
        Route::resource('groupes', GroupController::class)->except(['update']);
    });

    Route::middleware(['role:subscriber'])->group(function() {
        Route::get('/profil', [SubscriptionController::class, 'index'])->name('profile.index');
        Route::get('/souscription/{lesson}', [SubscriptionController::class, 'create'])->name('subscription.create');


    });
});

Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');
