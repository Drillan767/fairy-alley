<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceSubscription;
use App\Models\User;
use App\Notifications\ServiceAsked;
use App\Services\FileHandler;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function __construct(public FileHandler $fileHandler)
    {
    }

    public function index()
    {
        $services = Service::with('page', 'banner', 'thumbnail')
            ->orderBy('order')
            ->get();
        $pages = Page::all(['id', 'title']);

        return Inertia::render('Admin/Services/Index', compact('services', 'pages'));
    }

    public function subscriptions()
    {
        $subscriptions = ServiceSubscription::with('service:id,title', 'user:id,firstname,lastname')->get();
        return Inertia::render('Admin/Services/Subscriptions', compact('subscriptions'));
    }

    public function askService(Request $request)
    {
        ServiceSubscription::create([
            'user_id' => $request->user()->id,
            'service_id' => $request->get('service')
        ]);

        Notification::sendNow(User::role('administrator')->get(), new ServiceAsked($request->user()->id, $request->get('service')));

        return redirect()->back()->with('success', 'Votre demande pour ce service a bien été prise en compte');
    }

    public function updateSubscription(Request $request)
    {

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
        foreach (['title', 'description', 'page_id', 'ref', 'homepage', 'type'] as $field) {
            $service->$field = $request->get($field);
        }

        $service->save();

        if ($request->hasFile('illustration')) {
            $file = $request->file('illustration');
            $path = Storage::disk('local')
                ->putFileAs(
                    "tmp/service/$service->id",
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
