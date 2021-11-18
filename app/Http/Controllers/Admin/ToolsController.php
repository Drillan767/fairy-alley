<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImportUsersRequest;
use App\Http\Controllers\Controller;
use App\Services\UserImportHandler;
use Inertia\Inertia;
use Inertia\Response;

class ToolsController extends Controller
{

    public function __construct(protected UserImportHandler $importHandler)
    {}

    public function importForm(): Response
    {
        return Inertia::render('Admin/Tools/Import');
    }

    public function importUsers(ImportUsersRequest $request)
    {
        list($success, $errors) = $this->importHandler->handle($request->file('file'));
        return redirect()
            ->back()
            ->with('success', $success)
            ->with('error', $errors ?? null);
    }
}
