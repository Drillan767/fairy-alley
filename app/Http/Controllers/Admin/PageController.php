<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PageRequest;
use App\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Pages/Index', ['pages' => Page::all()]);
    }

    public function create()
    {
        return Inertia::render('Admin/Pages/Create', [
            'tiny' => env('TINY_SECRET'),
        ]);
    }

    public function store(PageRequest $request)
    {
        $blobs = Arr::flatten($request->get('medias'));
        $images = Arr::flatten($request->file('medias'));
        $imgsInContent = array_combine($blobs, $images);

        $page = new P-age();
        $page->title = $request->get('title');
        $page->slug = $request->get('slug');
        $page->summary = $request->get('summary');
        $page->published = $request->get('published') === 1;
        $page->illustration = '';
        $page->content = '';
        $page->save();

        $file = $request->file('illustration');
        $page->illustration = env('MEDIAS_URL') . Storage::disk('s3')->putFileAs("pages/{$page->id}/illustration", $file, $file->getClientOriginalName());

        $content = $request->get('content');
        foreach($imgsInContent as $find => $replace) {
            if (Str::contains($content, $find)) {
                $url = Storage::disk('s3')->putFileAs("pages/{$page->id}/content", $replace, $replace->getClientOriginalName());
                $content = Str::replace($find, env('MEDIAS_URL') . $url, $content);
            }
        }

        $page->content = $content;

        $page->save();

        return Inertia::render('Admin/Pages/Index')->with('success', "Page enregistrée avec succès");
    }

    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->firstorFail();
        $user = Auth::user()?->load('roles');
        return Inertia::render('Admin/Pages/Show', compact('page', 'user'));
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

    public function testMinio()
    {
        // dd(Storage::disk('s3')->url('test/image.jpg'));
        return '<img src="' . env('MEDIAS_URL') . 'test/image.jpg" />';
        Storage::disk('s3')->put('test', Storage::disk('local')->get('/srv/public/img/logo.png'));
    }
}
