<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileUploadRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class FileController extends Controller
{
    public function index(Request $request): Response
    {
        $mediaType = $request->media;
        $media = match ($mediaType) {
            'videos' => Storage::disk('s3')->allFiles('videos'),
            'photos' => Storage::disk('s3')->allFiles('photos'),
            'musiques' => Storage::disk('s3')->allFiles('musiques'),
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

    public function upload(FileUploadRequest $request): RedirectResponse
    {

        $nbFiles = 0;
        $grouped = collect($request->file('files'))->groupBy(function ($file) {
            preg_match('/(.*)\/.*/', $file->getClientMimeType(), $matches);
            return $matches[1];
        });

        $directories = [
            'audio' => 'musiques',
            'video' => 'videos',
            'image' => 'photos',
        ];

        foreach ($grouped as $type => $files) {

            if (!in_array($type, ['audio', 'image', 'video'])) {
                unset($grouped[$type]);
            } else {
                $nbFiles += $files->count();
                $files->each(fn ($file) => Storage::disk('s3')->putFileAs(
                    $directories[$type],
                    $file,
                    $file->getClientOriginalName())
                );
            }
        }

        $msg = $nbFiles > 1 ? "$nbFiles fichiers ont été uploadés." : '1 fichier a été uploadé.';

        return redirect()->back()->with($msg);
    }
}
