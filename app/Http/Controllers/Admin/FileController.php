<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\{Inertia, Response};

class FileController extends Controller
{
    public function index(Request $request): Response
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

    public function upload(FileUploadRequest $request)
    {
        $path = $request->get('type');
        foreach($request->file('files') as $file) {
            Storage::disk('s3')->putFileAs($path, $file, $file->getClientOriginalName());
        }

        $nbFiles = count($request->file('files'));
        $msg = $nbFiles > 1 ? "$nbFiles fichiers ont été uploadés." : '1 fichier a été uploadé.';

        return redirect()->back()->with($msg);
    }
}
