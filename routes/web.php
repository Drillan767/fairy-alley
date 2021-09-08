<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\PageController;
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
    });

    Route::middleware(['role:subscriber'])->group(function() {
        Route::get('/profil', fn() => Inertia::render('Test/Profile'))->name('profile.index');

    });
});

Route::resource('pages', PageController::class);
