<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImportUsersRequest;
use App\Http\Controllers\Controller;
use App\Services\UserImportHandler;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\{Inertia, Response};
use SimpleXLSXGen;

class ToolsController extends Controller
{

    public function __construct(protected UserImportHandler $importHandler)
    {}

    public function importForm(): Response
    {
        return Inertia::render('Admin/Tools/Import');
    }

    public function importUsers(ImportUsersRequest $request): RedirectResponse
    {
        list($success, $errors) = $this->importHandler->handle($request->file('file'));
        return redirect()
            ->back()
            ->with('success', $success)
            ->with('error', $errors ?? null);
    }

    public function exportHolidays()
    {
        $jsonFile = Storage::disk('s3')->get('system/holidays.json');
        $holidays = json_decode($jsonFile);

        $result = [];

        foreach($holidays as $day => $event) {
            $result[$event][] = $day;
        }

        $file[] = array_keys($result);
        $nbRows = count(reset($result));

        for ($i = 0; $i <= $nbRows; $i++) {
            $line = [];

            foreach($result as $day) {

                if (isset($day[$i])) {
                    $line[] = Carbon::parse($day[$i])->format('d/m/Y');
                } else {
                    $line[] = '';
                }
            }

            $file[] = $line;
        }

        $xlsx = SimpleXLSXGen::fromArray($file);
        $xlsx->downloadAs('vacances.xlsx');
    }
}
