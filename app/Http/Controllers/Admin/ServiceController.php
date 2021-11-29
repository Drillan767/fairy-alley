<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ServiceRequest;
use App\Jobs\HandleImage;
use App\Services\FileHandler;
use Intervention\Image\Facades\Image;
use App\Models\{Media, Page, Service};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('page', 'banner', 'thumbnail')
            ->orderBy('order')
            ->get();
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
        $service->title = $request->get('title');
        $service->description = $request->get('description');
        $service->page_id = $request->get('page_id');

        $service->save();

        if ($request->hasFile('illustration')) {
            $file = $request->file('illustration');
            $path = Storage::disk('local')
                ->putFileAs(
                    storage_path("tmp/service/$service->id"),
                    $file,
                    $file->getClientOriginalName()
                );
            (new FileHandler())->resizeServiceImage($path, $service);

            // HandleImage::dispatchAfterResponse($path);
        }
    }

    public function order(Request $request)
    {
        if ($request->user()->hasRole('administrator')) {
            foreach ($request->all() as $i => $service) {
                DB::table('services')
                    ->where('id', $service['id'])
                    ->update(['order' => ($i + 1)]);
            }
        }
    }
}
