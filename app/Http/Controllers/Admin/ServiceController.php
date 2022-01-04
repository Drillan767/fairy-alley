<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ServiceRequest;
use App\Services\FileHandler;
use Illuminate\Http\RedirectResponse;
use App\Models\{Page, Service};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Storage};
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function __construct(public FileHandler $fileHandler)
    {}

    public function index()
    {
        $services = Service::with('page', 'banner', 'thumbnail')
            ->orderBy('order')
            ->get();
        $pages = Page::all(['id', 'title']);
        return Inertia::render('Admin/Services/Index', compact('services', 'pages'));
    }

    public function store(ServiceRequest $request)
    {
        $this->handleServices($request, new Service());

        return redirect()->route('services.index')->with('success', 'Service enregistré avec succès.');
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $this->handleServices($request, $service, true);
        return redirect()->back()->with('success', 'Service mis à jour avec succès.');
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
        $service->ref = $request->get('ref');

        $service->save();

        if ($request->hasFile('illustration')) {
            $file = $request->file('illustration');
            $path = Storage::disk('local')
                ->putFileAs(
                    storage_path("tmp/service/$service->id"),
                    $file,
                    $file->getClientOriginalName()
                );
            $this->fileHandler->resizeServiceImage($path, $service, $update);
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
