<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $mediaType = $request->media;
        $media = match ($mediaType) {
            'videos' => Storage::disk('s3')->allFiles('videos'),
            'photos' => Storage::disk('s3')->allFiles('photos'),
            'musiques' => Storage::disk('s3')->allFiles('musics'),
        };

        $files = collect($media)->map(function ($file) use ($mediaType) {
            return [
                'title' => basename($file),
                'src' => env('MEDIAS_URL') . Storage::disk('s3')->path($file),
            ];
        });

        return Inertia::render('Admin/Media/Index', [
            'files' => $files,
            'type' => $mediaType,
        ]);
    }
}
