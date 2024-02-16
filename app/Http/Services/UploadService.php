<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;

class UploadService
{
    public function uplaod($image)
    {

        $path = Storage::disk('s3')->put('images', $image);
        return $path;
    }
}
