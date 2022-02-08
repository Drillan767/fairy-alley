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
            'videos' => Storage::disk('s3')->allFiles('Videos Gym'),
            'photos' => Storage::disk('s3')->allFiles('Photos'),
            'musiques' => []
        };

        // Must return the following structure:
        /*
            [
                [
                    'title' => 'title',
                    'src' => 'original src',
                    'thumb' => 'thumbnail from vid or resized image',

            ]
         */

        $files = collect($media)->map(function ($file) use ($mediaType) {
            if ($mediaType === 'photos') {
                return env('MEDIAS_URL') . Storage::disk('s3')->path($file);
            } else {
                return basename($file);
            }
        });

        return Inertia::render('Admin/Media/Index', [
            'files' => $files,
            'type' => $mediaType,
        ]);
    }
}
