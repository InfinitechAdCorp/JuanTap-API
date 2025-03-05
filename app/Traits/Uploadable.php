<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait Uploadable
{
    public function upload($directory, $file)
    {
        if ($file) {
            $fileName = strtolower(Str::ulid()) . "." . $file->clientExtension();
            Storage::disk('s3')->put("$directory/$fileName", $file->getContent(), 'public');
            return $fileName;
        }
    }
}
