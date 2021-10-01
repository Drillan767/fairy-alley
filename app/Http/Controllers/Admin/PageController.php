<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PageRequest;
use App\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\{Arr, Str};
use Illuminate\Support\Facades\{Auth, Storage};
use Inertia\{Inertia, Response};

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
        $this->handlePage($request, new Page());
        return redirect()->route('pages.index')->with('success', 'Page créée avec succès.');
    }

    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->firstorFail();
        if (request()->inertia()) {
            return Inertia::location(url()->current());
        }

        return view('page', compact('page'));
    }

    public function edit(Page $page)
    {
        return Inertia::render('Admin/Pages/Edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page)
    {
        $this->handlePage($request, $page, true);
        return redirect()->route('pages.index')->with('success', 'Page mise à jour avec succès,');

    }

    public function destroy(Page $page)
    {
        Storage::disk('s3')->deleteDirectory("pages/{$page->id}");
        $page->delete();
        return response()->json('ok');
    }

    private function handlePage(PageRequest $request, Page $page, $editing = false)
    {
        $content = $request->get('content');
        $file = $request->file('imgFile');
        $blobs = $request->get('medias') ? Arr::flatten($request->get('medias')) : [];
        $images = $request->file('medias') ? Arr::flatten($request->file('medias')) : [];
        $imgsInContent = array_combine($blobs, $images);

        $page->title = $request->get('title');
        $page->slug = $request->get('slug');
        $page->summary = $request->get('summary');
        $page->published = $request->get('published');
        if (!$editing) {
            $page->illustration = '';
            $page->content = '';
        }

        $page->save();

        foreach ($imgsInContent as $find => $replace) {
            if (Str::contains($content, $find)) {
                $url = Storage::disk('s3')->putFileAs("pages/{$page->id}/content", $replace, $replace->getClientOriginalName());
                $content = Str::replace($find, env('MEDIAS_URL') . $url, $content);
            }
        }

        $page->content = $content;
        if (($editing && $file) || $editing === false) {
            $page->illustration = env('MEDIAS_URL') . Storage::disk('s3')->putFileAs("pages/{$page->id}/illustration", $file, $file->getClientOriginalName());;
        }

        $page->save();
    }
}
