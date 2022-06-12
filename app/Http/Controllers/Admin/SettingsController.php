<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};
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
        $settings->put('details', $request->get('details'));
        $settings->put('process', $request->get('process'));
        $settings->put('organization', $request->get('organization'));
        $settings->put('conditions', $request->get('conditions'));

        return redirect()->back();
    }

    public function holidays(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'dates' => ['array', 'required'],
            'dates.*.date' => ['required', 'string'],
            'dates.*.reason' => ['required', 'string']
        ],
        [],
        [
            'dates.*.date' => 'Date',
            'dates.*.reason' => 'Raison'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        $settings = $this->instanciateSettings();
        $customHolidays = $settings->get('holidays', []);

        foreach ($request->get('dates') as $date) {
            $cDate = Carbon::parse($date['date'])->format('Y-m-d');
            if (!array_key_exists($cDate, $customHolidays)) {
                $customHolidays[$cDate] = $date['reason'];
            }
        }

        $settings->put('holidays', $customHolidays);

        return redirect()->back();
    }

    private function instanciateSettings()
    {
        return Valuestore::make(storage_path('app/settings.json'));
    }
}
