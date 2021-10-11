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
        $services = Service::with('page', 'file')->get();
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
        $this->handleServices($request, new Service());

        return redirect()->route('services.index')->with('success', 'Service enregistré avec succès.');
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

    public function update(ServiceRequest $request, Service $service)
    {
        $this->handleServices($request, $service, true);
        return redirect()->back()->with('success', 'Service mit à jour avec succès.');
    }

    public function destroy(Service $service)
    {
        if (auth()->user()->hasRole('administrator')) {
            $service->delete();
        } else {
            abort(403);
        }

    }

    private function handleServices(ServiceRequest $request, Service $service, $update = false)
    {
        $method = $update ? 'update' : 'create';

        $service->$method([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'page_id' => $request->get('page_id'),
        ]);

        if ($request->hasFile('illustration')) {
            $file = $request->file('illustration');

            if ($update) {
                $service->file()->delete();
            }

            $media = new Media([
                'title' => $file->getClientOriginalName(),
                'url' => Storage::disk('s3')->putFileAs("service/{$service->id}", $file, $file->getClientOriginalName()),
            ]);

            $service->file()->save($media);
        }
    }
}
