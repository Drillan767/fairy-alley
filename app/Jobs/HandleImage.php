<?php

namespace App\Jobs;

use App\Models\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class HandleImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected string $path)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /*
         * TODO:
         * 1. x Parse path, retrieve service id, load it
         * 2. x Get image, resize it, upload it to s3
         * 2.5. x Keep aspect ratio
         * 3. x Delete original(s)
         * 4. x Create / update related media
         * 5. Ensure nothing breaks afterwards after editing / showing / deleting service
         * 6. Cleanup Service model
         */

        /*
         $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = str_replace(' ', '-', $filename);

            if ($update) {
                $service->files()->delete();
            }

            $paths = [];

            foreach (['thumbnail', 'banner'] as $format) {
                $img = Image::make($file)
                    ->resize(...$this->dimensions[$format])
                    ->save(storage_path("{$filename}_$format.{$file->getClientOriginalExtension()}"));

                $uploadedFile = Storage::get(storage_path($img->filename));
                $test = Storage::disk('s3')->put("service/$service->id/$img->filename", $uploadedFile);
                dd($test);
                $paths[$format] = $path;
            }

            $media = new Media([
                'title' => $file->getClientOriginalName(),
                'url' => json_encode($paths),
            ]);

            $service->$format()->save($media);
         */
    }
}
