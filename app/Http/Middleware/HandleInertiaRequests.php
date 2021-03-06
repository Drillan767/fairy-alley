<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $settings = Settings::make(storage_path('app/settings.json'));
        $array = array_merge(parent::share($request), [
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
            ],
            'logo' => env('MEDIAS_URL') . 'system/logo.png',
            'BASE_URL' => env('APP_URL'),
            'settings' => $settings->all(),
            'status' => [
                Subscription::PENDING           => 'En attente',
                Subscription::VALIDATED         => 'Validé',
                Subscription::NEEDS_INFOS       => "En attente d'informations",
                Subscription::AWAITING_PAYMENT  => 'En attente du paiement',
                Subscription::SUBSCRIPTION_OVER => 'Fin des cours',
            ],
        ]);

        if (auth()->user()?->hasRole('administrator')) {
            $array['tiny'] = env('TINY_SECRET');
        }

        return $array;
    }
}
