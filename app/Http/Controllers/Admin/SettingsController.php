<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Valuestore\Valuestore;

class SettingsController extends Controller
{
    public function edit(): Response
    {
        $settings = $this->instanciateSettings();
        return Inertia::render('Admin/Settings/Edit', [
            'settings' => $settings->all()
        ]);
    }

    public function renewal(Request $request)
    {
        $settings = $this->instanciateSettings();
        $settings->put('subscription_start', Carbon::parse($request->get('start'))->startOfDay());
        $settings->put('subscription_end', Carbon::parse($request->get('end'))->startOfDay());
        $settings->put('price_full', $request->get('price_full'));
        $settings->put('price_quarterly', $request->get('price_quarterly'));

        return redirect()->back();
    }

    public function update()
    {
        // ...
    }

    private function instanciateSettings()
    {
        return Valuestore::make(storage_path('app/settings.json'));
    }
}
