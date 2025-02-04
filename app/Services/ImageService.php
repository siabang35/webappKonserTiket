<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function uploadImage($image, $path, $width = null)
    {
        $img = Image::make($image);

        if ($width) {
            $img->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
        $fullPath = $path . '/' . $filename;

        Storage::put($fullPath, (string) $img->encode());

        return $fullPath;
    }

    public function deleteImage($path)
    {
        if ($path && Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
