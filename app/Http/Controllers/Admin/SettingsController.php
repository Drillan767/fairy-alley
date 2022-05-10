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
        $dates = $request->get('dates');
        $settings->put('subscription_start', Carbon::parse($dates[0])->startOfDay());
        $settings->put('subscription_end', Carbon::parse($dates[1])->startOfDay());

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
