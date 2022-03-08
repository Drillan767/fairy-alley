<?php

namespace App\Jobs;

use App\Http\Requests\PageRequest;
use App\Models\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AsyncUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * When trying to upload everything at once to MinIO, got the following error:.
     *
     * A sha256 checksum could not be calculated for the provided upload body, because it was not seekable.
     * To prevent this error you can either
     * 1) include the ContentMD5 or ContentSHA256 parameters with your request,
     * 2) use a seekable stream for the body, or
     * 3) wrap the non-seekable stream in a GuzzleHttp\Psr7\CachingStream object.
     * You should be careful though and remember that the CachingStream utilizes PHP temp streams.
     * This means that the stream will be temporarily stored on the local disk.
     *
     * The common solution found on SO is to load files to MinIO from the disk, not the request body.
     *
     * @return void
     */
    public function __construct(protected PageRequest $request, protected Page $page)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $content = $this->request->get('content');
        $file = $this->request->file('illustration');
        $blobs = Arr::flatten($this->request->get('medias'));
        $images = Arr::flatten($this->request->file('medias'));
        $imgsInContent = array_combine($blobs, $images);

        // Step 1: Store everything in storage directory.

        $files = [];
        foreach ($imgsInContent as $find => $replace) {
            if (Str::contains($content, $find)) {
                $url = env('MEDIAS_URL') . "pages/{$this->page->id}/content/{$replace->getClientOriginalName()}";
                Storage::disk('local')->putFileAs("pages/{$this->page->id}", $replace, $replace->getClientOriginalName());
                $content = Str::replace($find, $url, $content);
                $files[] = $url;
            }
        }

        $thumbUrl = env('MEDIAS_URL') . "pages/{$this->page->id}/illustration/{$file->getClientOriginalName()}";
        Storage::disk('local')->putFileAs("pages/{$this->page->id}", $file, $file->getClientOriginalName());
        $files[] = $thumbUrl;

        $this->page->illustration = $thumbUrl;
        $this->page->content = $content;
        $this->page->save();

        // Step 2: Move all the files to MinIO, clean everything behind.

        Log::debug($files);
        $model = '';

        foreach ($files as $file) {
            preg_match('/.*\/medias\/(.*)\/([0-9]+)\/(.*)\/(.*)/', $file, $matches);
            list(, $model, $id, $subDir, $fileName) = $matches;
            $img = Storage::disk('local')->get("$model/$id/$fileName");
            Storage::disk('s3')->put("$model/$id/$subDir/$fileName", $img);
        }

        Storage::disk('local')->deleteDirectory($model);
    }
}
