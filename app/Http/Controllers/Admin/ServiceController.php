<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ServiceRequest;
use App\Models\Media;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        $pages = Page::all(['id', 'title']);
        return Inertia::render('Admin/Services/Index', compact('services', 'pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    public function store(ServiceRequest $request)
    {
        $file = $request->file('illustration');

        $service = Service::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'page_id' => $request->get('page_id'),
        ]);

        $media = new Media([
            'title' => $file->getClientOriginalName(),
            'url' => Storage::disk('s3')->putFileAs("service/{$service->id}", $file, $file->getClientOriginalName()),
        ]);

        $service->file()->save($media);

        return redirect()->back()->with('success', 'Service enregistré avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        //
    }
}
