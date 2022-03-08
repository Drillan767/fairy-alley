<?php

use App\Actions\Fortify\UpdateUserProfilData;
use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\Inertia\CurrentUserController;
use Laravel\Jetstream\Http\Controllers\Inertia\PrivacyPolicyController;
use Laravel\Jetstream\Http\Controllers\Inertia\TermsOfServiceController;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;
use Laravel\Jetstream\Jetstream;

Route::group(['middleware' => config('jetstream.middleware', ['web'])], function () {
    if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
        Route::get('/terms-of-service', [TermsOfServiceController::class, 'show'])->name('terms.show');
        Route::get('/privacy-policy', [PrivacyPolicyController::class, 'show'])->name('policy.show');
    }

    Route::group(['middleware' => ['auth', 'verified']], function () {
        // User & Profile...
        Route::get('/profil/editer', [UserProfileController::class, 'show'])->name('profile.show');
        Route::post('/profile/{user}/coordinates', [UpdateUserProfilData::class, 'updateCoordinates'])->name('user-profile-coordinates.update');
        Route::post('/profile/{user}/other-data', [UpdateUserProfilData::class, 'updateOtherData'])->name('user-profile-data.update');

        if (Jetstream::hasAccountDeletionFeatures()) {
            Route::delete('/user', [CurrentUserController::class, 'destroy'])->name('current-user.destroy');
        }
    });
});
