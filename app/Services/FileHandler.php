<?php

namespace App\Services;

use App\Models\{Media, Service, YearData};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileHandler
{
    private array $dimensions = [
        'banner'    => [2000, 500],
        'thumbnail' => [305, 160],
    ];

    public function uploadOrReplace(UploadedFile $file, YearData $yearData, $user)
    {
        // Ensure $yearData exists or is created to link the file to it.
        $yearData->save();
        $media = $yearData->file ?? new Media();
        if (Storage::exists("user/$user->id/$media->title")) {
            Storage::delete("user/$user->id/$media->title");
        }
        $media->title = $file->getClientOriginalName();
        $media->url = Storage::putFileAs("user/$user->id", $file, $file->getClientOriginalName());
        $yearData->file()->save($media);
    }

    public function resizeServiceImage($path, Service $service, $updating = false)
    {
        $file = Storage::disk('local')->get($path);
        $fileMeta = pathinfo($path);
        $filename = $fileMeta['filename'] . '.' . $fileMeta['extension'];

        if ($updating) {
            Storage::disk('s3')->deleteDirectory("service/$service->id");
        }

        foreach (['thumbnail', 'banner'] as $format) {
            $basename = str_replace($fileMeta['filename'], "{$fileMeta['filename']}_$format", $filename);
            $path = "service/$service->id/$basename";

            list($width, $height) = $this->dimensions[$format];
            $img = Image::make($file)
                ->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->stream()
                ->detach();

            if ($updating) {
                $media = $service->$format;
                $media->title = $basename;
                $media->url = $path;
                $media->save();
            } else {
                $media = new Media([
                    'title' => $basename,
                    'url' => $path,
                ]);
                $service->$format()->save($media);
            }

            Storage::disk('s3')->put($path, $img);
            Storage::disk('local')->deleteDirectory('tmp');
        }
    }
}
