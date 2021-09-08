<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PageRequest;
use App\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Pages/Index', Page::all());
    }

    public function create()
    {
        return Inertia::render('Admin/Pages/Create', [
            'tiny' => env('TINY_SECRET'),
        ]);
    }

    public function store(PageRequest $request)
    {
        $page = new Page();
        $page->fill($request->validated());
        $page->save();

        return Inertia::render('Admin/Pages/Index')->with('success', "Page enregistrée avec succès");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        //
    }
}
