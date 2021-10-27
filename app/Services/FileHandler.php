<?php

namespace App\Services;

use App\Models\{Media, YearData};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileHandler
{
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
}
