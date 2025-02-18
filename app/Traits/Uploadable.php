<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait Uploadable {
    public function upload($file, $directory) {
        if ($file) {
            $name = Str::ulid().".".$file->clientExtension();
            Storage::disk('s3')->put("$directory/$name", $file->getContent(), 'public');
            return $name;
        }
    }
}