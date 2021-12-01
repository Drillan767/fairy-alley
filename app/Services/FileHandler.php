<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use App\Models\{Media, Service, YearData};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

    public function resizeServiceImage($path, Service $service)
    {
        $file = Storage::disk('local')->get($path);

        foreach (['thumbnail', 'banner'] as $format) {
            $filename = pathinfo($path, PATHINFO_FILENAME);
            $basename = basename(str_replace($filename, "{$filename}_$format", $path));

            list ($width, $height) = $this->dimensions[$format];
            $img = Image::make($file)
                ->resize($width, $height, function($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->stream()
                ->detach();

            $path = "service/$service->id/$basename";

            Storage::disk('s3')->put($path, $img);

            $media = new Media([
                'title' => $basename,
                'url' => $path,
            ]);

            $service->$format()->save($media);

            Storage::disk('local')->delete($path);
        }
    }
}
