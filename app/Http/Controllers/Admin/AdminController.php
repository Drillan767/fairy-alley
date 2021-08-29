<?php

namespace App\Http\Controllers\Admin;

use Inertia\{Inertia, Response};

class AdminController
{
    public function index(): Response
    {
        return Inertia::render('Admin/Admin');
    }
}
